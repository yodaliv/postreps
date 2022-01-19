import helper from './helper';
import global from "./global";

const RepairOrder = {
    order: {},
    zone: {},

    init() {
        this.loadCompletedInstallOrders();

        this.loadDatatable()
        this.showRepairOrderEntries();
        this.repairOrderSearchInput();
        window.createRepairOrder = this.createRepairOrder;
        this. onDesiredDateChange();
        this.initMap();

        //Disable holidays in calendar
        $.get(helper.getSiteUrl('/get/holidays')).done(holidays => {
            RepairOrder.holidays = holidays;
        });
    },

    loadCompletedInstallOrders() {
        $('.order-repair').on('click', () => {
            window.location.href = `${helper.getSiteUrl()}/repair`;
        });
    },

    loadDatatable() {
        RepairOrder.table = $('#repairOrdersTable').dataTable({
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search...",
            },
            pageLength: 10,
            dom: "rtip",
            ajax: helper.getSiteUrl("/repair/orders/datatable"),
            serverSide: true,
            columns: [
                {
                    data: "address",
                    defaultContent: "",
                    title: "Address",
                    name: "orders.address",
                    visible: 1,
                },
                {
                    data: "status",
                    defaultContent: "",
                    title: "Status",
                    name: "orders.status",
                    searchable: false,
                    orderable: false,
                    visible: 1,
                    render(d, t, r) {
                        let status = `<span class="font-weight-bold" style="color: #267F00">
                            INSTALLED ${helper.formatDate(r.desired_date)}
                        </span>`;

                        return status;
                    }
                },
                {
                    defaultContent: "...",
                    title: "Action",
                    visible: 1,
                    searchable: false,
                    orderable: false,
                    render(d, t, r) {
                        let action = `<a class='link mx-1' onclick="window.createRepairOrder(${r.id})">
                            <img src="./images/Repair_Icon.png" alt="Repair" class="width-px-40">
                        </a>`;

                        //Probably show Edit button if repair order was created.

                        return action;
                    }
                },
                {
                    data: "order_number",
                    defaultContent: "404",
                    title: "Order ID#",
                    name: "orders.order_number",
                    visible: 1,
                },
            ]
        });
    },

    showRepairOrderEntries() {
        const select = $("#showRepairOrderEntries");
        select.on("change", (event) => {
            const selected = parseInt(event.target.value);
            RepairOrder.table.api().context[0]._iDisplayLength = selected;
            RepairOrder.table.api().draw();
        });
    },

    repairOrderSearchInput() {
        let searchInput = $("#repairOrderSearchInput");
        searchInput.on("keyup", (event) => {
            RepairOrder.table.fnFilter(event.target.value);
        });
    },

    setOfficeAndAgent(order) {
        $('#repairOrderOffice').val(order.office.user.name);
        if (order.agent) {
            $('#repairOrderAgent').val(order.agent.user.name);
        }
    },

    googleKey: global.googleKey,
    initMap() {
        window.initMap = this.startMap;
        const src = `https://maps.googleapis.com/maps/api/js?key=${RepairOrder.googleKey}&callback=window.initMap&libraries=drawing,geometry,places&v=weekly`;
        $("body").append(window.e("script", { src, googlescript: true }));
    },
    startMap() {
        // The location of defaultLocation
        const defaultLocation = {
            lat: 43.633994,
            lng: -116.433707,
        };
        // The map, centered at defaultLocation
        const map = new google.maps.Map(document.getElementById("repairOrderMap"),
            {
                zoom: 11,
                center: defaultLocation,
            }
        );
        window.map = map;
    },

    loadAddressOnMap(address, position) {
        window.map.setCenter(position);

        if (window.addressMarker) {
            window.addressMarker.setMap(null);
        }

        let icon = {
            url: helper.getSiteUrl(`/storage/images/map_pin_verified.png`),
            anchor: new google.maps.Point(0, 50)
        };
        window.addressMarker = new google.maps.Marker({
            position,
            map,
            title: address,
            icon,
            draggable: false,
        });

        window.map.setZoom(17);
    },

    setPropertyInfo(order) {
        $('#repairOrderAddress').val(order.address);

        //Map
        RepairOrder.loadAddressOnMap(order.address, {lat: Number(order.latitude), lng: Number(order.longitude)});

        $('#repairOrderPropertyType').find(`option[value="${order.property_type}"]`).prop('selected', true);
    },

    async getRepairZone(orderId) {
        const zone = await $.get(helper.getSiteUrl(`/repair/get/zone/${orderId}`));

        return zone;
    },


    updateCalendar(savedDate) {
        $("#repairOrderDatePicker").datepicker("destroy");
        $("#repairOrderDatePicker").datepicker({
            onSelect: function (dateText) {
                console.log(dateText)
                $(`[name="repair_order_custom_desired_date"]`).val(dateText);
                return RepairOrder.updateCalendar(helper.parseUSDate(dateText));
            },
            beforeShowDay: function (date) {
                let year = date.getFullYear();
                let month = `${date.getMonth() + 1}`;
                let day = `${date.getDate()}`;
                if (day.length == 1) day = "0" + day;
                if (month.length == 1) month = "0" + month;
                let dateString = month + "/" + day + "/" + year;
                //console.log(RepairOrder.holidays)
                //console.log(dateString);
                if (RepairOrder.holidays.includes(dateString)) {
                    return [false];
                }

                //Disable past dates and current date
                let daysdiff = moment().diff(date, 'days');
                let cudate = moment().format('MM/DD/YYYY');
                let cdate = moment(date).format('MM/DD/YYYY');

                if (cudate == cdate) {
                    return [false]; //disable current date
                }

                if (daysdiff >= 1) {
                     return [false]; //disable past date
                }

                //Disable non working days from the selected zone
                if (RepairOrder.zone.data) {
                    let zone = RepairOrder.zone.data;
                    let su = zone.su;
                    let mo = zone.m;
                    let tu = zone.tu;
                    let we = zone.w;
                    let th = zone.th;
                    let fr = zone.f;
                    let sa = zone.sa;
                    //order is important
                    let week = [su, mo, tu, we, th, fr, sa];

                    for (let index = 0; index < week.length; index++) {
                        const day = week[index];
                        if (date.getDay() === index) {
                            return [day];
                        }
                    }
                    return [true];
                } else {
                    return [true];
                }
            },
        });
        if (savedDate) {
            $("#repairOrderDatePicker").datepicker('setDate', savedDate);
        }
    },

    setDate(order) {
        let datePicker = $("#repairOrderDatePicker");
        if (order.desired_date_type == 1) {
            $(`[name="repair_order_desired_date"][value="asap"]`).prop('checked', true).val('asap');
            datePicker.addClass("d-none");
        } else {
            $(`[name="repair_order_desired_date"][value="custom_date"]`).trigger('click')
            //Need to review this part. Why does it need setTimeout?
            setTimeout(()=>{
                datePicker.removeClass("d-none");
                //datePicker.datepicker("setDate", d);
                RepairOrder.updateCalendar(false);
            }, 1000);
        }
    },

    setPostSignAccessories(order) {
        $('label[for="repairOrderPost"]').html(order.post.post_name);

        //Load post repair options from settings
        const repairOptions = `
            <div class="form-check d-flex justify-content-between">
                <input
                    type="radio"
                    name="repair_options_post"
                    value="0"
                    class="form-check-input"
                    id="post_option_0"
                    onchange="window.onRepairPostChange(event)"
                >
                <label class="form-check-label text-dark" for="post_option_0">Replace/Repair Post</label>
                <span price="">$10</span>
            </div>
            <div class="form-check d-flex justify-content-between">
                <input
                    type="radio"
                    name="repair_options_post"
                    value="1"
                    class="form-check-input"
                    id="post_option_1"
                    onchange="window.onRepairPostChange(event)"
                >
                <label class="form-check-label text-dark" for="post_option_1">Relocate Post</label>
                <span price="">$20</span>
            </div>
        `;

        $('#repairOptionsContainer').html(repairOptions);
    },

    createRepairOrder(orderId) {
        $.get('/repair/get/order/' + orderId).done(async order => {
            RepairOrder.order = order;
            RepairOrder.zone = await RepairOrder.getRepairZone(RepairOrder.order.id);

            RepairOrder.setOfficeAndAgent(order);
            RepairOrder.setPropertyInfo(order);
            RepairOrder.setPostSignAccessories(order);
            RepairOrder.updateCalendar(false);

            $('#repairOrderModal').modal();
        });
    },

    editRepairOrder(orderId) {
        $.get('/repair/get/order/' + orderId).done(order => {
            /*RepairOrder.setOfficeAndAgent(order);
            RepairOrder.setPropertyInfo(order);
            RepairOrder.setPostSignAccessories(order);
            RepairOrder.setDate(order, false);*/

            $('#repairOrderModal').modal();
        });
    },

    onDesiredDateChange() {
        let dates_input = document.getAll(`[name="repair_order_desired_date"]`);
        let datepicker = $("#selectdate_custom_date");
        dates_input.forEach((d) => {
            d.onchange = (e) => {
                let type = e.target.value;
                $(`[name="repair_order_desired_date"]`).removeAttr('checked')
                $(e.target).attr('checked', "true");
                if (type === "custom_date") {
                    RepairOrder.setRushFee(0)
                    datepicker.removeClass("d-none");
                } else {
                    //$("#rushOrderModal").modal();
                    datepicker.addClass("d-none");
                }
            };
        });
    },

    setRushFee(value) {
        let rush_fee_input = $(`input[name="install_post_rush_fee"]`);
        rush_fee_input.val(value)
        rush_fee_input.trigger('change');
    }
}

$(() => {
    RepairOrder.init();
});
