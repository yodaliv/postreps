import helper from './helper'
import InstallPost from './install_post';

const Order = {
    status_received: 0,
    status_incomplete: 1,
    status_scheduled: 2,
    status_completed: 3,
    status_cancelled: 4,
    date: {
        asap: 1,
        desired_date_type: 2,
    },

    init() {
        this.datatable()
        this.showOrderEntries();
        this.orderSearchInput();
        window.editOrder = this.editOrder;
        window.eremoveFile = this.eremoveFile;
        window.orderCancel = this.orderCancel
        window.markOrderCompleted = this.markOrderCompleted;
        this.deleteOrders();
    },
    orderCancel(id) {
        helper.confirm("This action is irreversible!", () => {
            $.get(`/order/${id}/cancel`).done(res => {
                let tables = document.getAll(".ordersTable");
                tables.forEach((e, index) => {
                    window['orderTable' + index].api().draw();
                })
            })
        });
    },
    markOrderCompleted(id) {
        helper.confirm("This action is irreversible!", () => {
            $.get(`/order/${id}/mark-completed`).done(res => {
                let tables = document.getAll(".ordersTable");
                tables.forEach((e, index) => {
                    window['orderTable' + index].api().draw();
                });
            })
        });
    },
    showOrderEntries() {
        let selects = document.getAll(".showOrderEntries");
        selects.forEach(select => {
            select = $(select);
            select.on("change", (event) => {
                let selected = parseInt(event.target.value);
                Order.tables.forEach(table => {
                    table.api().context[0]._iDisplayLength = selected;
                    table.api().draw();
                })
            });

        })
    },
    orderSearchInput() {
        let inputs = document.getAll(".orderSearchInput");
        inputs.forEach(input => {
            input = $(input)
            input.on("keyup", (event) => {
                let input = event.target;
                Order.tables.forEach(table => {
                    table.fnFilter(input.value);
                })
            });
        })
    }
    ,
    tables: [],
    datatable() {
        let tables = document.getAll(".ordersTable");
        tables.forEach((e, index) => {
            let table = $(e)
            if (table.length) {
                window['orderTable' + index] = table.dataTable({
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                    },
                    pageLength: 10,
                    dom: "rtip",
                    ajax: helper.getSiteUrl("/datatable/orders"),
                    serverSide: true,

                    columns: [
                        {
                            data: "address",
                            defaultContent: "404",
                            title: "Address",
                            name: "orders.address",
                            visible: 1,
                        },
                        {
                            data: "office_name",
                            defaultContent: "404",
                            title: "Office - Agent",
                            name: "office.name",
                            visible: 0,
                        },
                        {
                            data: "agent_name",
                            defaultContent: "404",
                            title: "Office - Agent",
                            name: "agent.name",
                            visible: 0,
                        },
                        {
                            data: "",
                            defaultContent: "404",
                            title: "Office - Agent",
                            name: "",
                            visible: 1,
                            searchable: false,
                            orderable: false,
                            render(d, t, r) {

                                if (!r.agent_name) return `${r.office_name}`;
                                return `${r.office_name} - ${r.agent_name}`;
                            }

                        },
                        {
                            data: "status",
                            defaultContent: "...",
                            title: "Status",
                            name: "orders.status",
                            searchable: false,
                            orderable: false,
                            visible: 1,
                            render(d, t, r) {
                                if (d == Order.status_received) {
                                    return `<span class="badge badge-pill badge-primary">Received</span>`
                                } else if (d == Order.status_incomplete) {
                                    return `<span class="badge badge-pill badge-warning">Incomplete</span>`;
                                } else if (d == Order.status_scheduled) {
                                    return `<span class="badge badge-pill badge-info">Scheduled</span>`;
                                } else if (d == Order.status_completed) {
                                    return `<span class="badge badge-pill badge-success">Completed</span>`;
                                } else if (d == Order.status_cancelled) {
                                    return `<span class="badge badge-pill badge-danger">Cancelled</span>`;
                                }
                            }
                        }, {
                            data: "",
                            defaultContent: "404",
                            title: "Service Date",
                            name: "",
                            visible: 1,
                            searchable: false,
                            orderable: false,
                            render(d, t, r) {
                                let s = ''
                                if (r.desired_date_type == Order.date.asap) {
                                    s = "Rush Order";
                                } else {
                                    return moment(r.desired_date).format('MM/DD/YYYY');
                                }
                                return s;
                            }
                        },
                        {
                            // data: "address",
                            defaultContent: "...",
                            title: "Action",
                            visible: 1,
                            searchable: false,
                            orderable: false,
                            render(d, t, r) {
                                let action = ``;
                                if (r.status == Order.status_scheduled || r.status == Order.status_completed || r.status == Order.status_cancelled) {
                                    return "";
                                }

                                action += `<a class='link mx-1' onclick="window.editOrder(${r.id})">Edit</a>`
                                if (r.status != Order.status_cancelled) {
                                    action += `<a class='link text-danger mx-1' onclick="window.orderCancel(${r.id})">Cancel</a>`;
                                    action += `<br><a class='link text-success font-weight-bold mx-1' onclick="window.markOrderCompleted(${r.id})">Mark Completed</a>`;
                                }

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
                })
                Order.tables.push(table);
            }
        })
    },
    editOrder(id) {
        Order.showLoading();
        let modal = $("#install_post_modal");
        if (modal.length) {
            $.get(`/get/order/` + id).done(order => {
                InstallPost.resetInstallModalForm()
                InstallPost.createpost = false;
                InstallPost.order_id = order.id;
                InstallPost.upload_accessory_file = null;
                InstallPost._files = [];
                Order.setOfficeAndAgent(order)
                Order.setPropertyInfo(order)
                Order.setDate(order);
                Order.setComment(order)
                Order.setFiles(order);
                Order.setFooter();
                Order.hideLoading();
                modal.modal()
            })
        }
    },
    delay: 1500,
    //Need to come back to this and find a better way of doing it.
    setOfficeAndAgent(order) {
        let selectoffice = $(`[name="install_post_office"]`);
        selectoffice.find(`option[value="${order.office_id}"]`).prop('selected', true)
        selectoffice.get(0).dispatchEvent(new Event('change'))
        setTimeout(() => {
            let selectagent = $(`[name="install_post_agent"]`);
            if (order.agent_id) {
                selectagent.find(`option[value="${order.agent_id}"]`).prop('selected', true)
                selectagent.get(0).dispatchEvent(new Event('change'))
            }
            setTimeout(() => {
                $(`[name="install_post_post"][value="${order.post_id}"]`).trigger('click')
                $(`[name="install_post_panel"][value="${order.panel_id}"]`).trigger('click')
                order.accessories.forEach(accessory => {
                    $(`[name="install_post_accessories[]"][value="${accessory.accessory_id}"]`).trigger('click');
                })

                setTimeout(() => {
                    if (order.files.length) {
                        if (order.property_type == 2 || order.property_type == 3) {
                            $(".document-required-warning").addClass("d-block").removeClass('d-none');
                        } else {
                            console.log(order);

                            $(".document-required-warning").addClass("d-none").removeClass('d-block');

                        }

                        $("#attachments .alert").addClass('d-none').removeClass('d-block');

                        let accessories = order.accessories.map(a => a.accessory);
                        let filesAcessories = [];

                        order.files.forEach(file => {
                            filesAcessories.push(file.accessory_id)

                        })

                        let platMap = order.files.filter(f => f.plat_map == 1);
                        if (order.property_type == 2 || order.property_type == 3) {
                            if (!platMap.length) {
                                $(".document-required-warning").addClass("d-block").removeClass('d-none');
                            }
                        }
                        accessories.forEach(accessory => {
                            if (filesAcessories.includes(accessory.id) == false) {
                                if (accessory.prompt == 1) {
                                    let s = `[accessory_name_id_${accessory.id}]`;
                                    $(s).removeClass(`d-none`).addClass(`d-block`);
                                }
                            }
                        });
                    }
                    if (order.agent_own_sign) {
                        $(`[name="install_post_panel_type"][value="-1"]`).trigger('click');
                    }
                    if (order.sign_at_property) {
                        $(`[name="install_post_panel_type"][value="-2"]`).trigger('click');
                    }
                }, Math.floor(this.delay / 3))
            }, 3000)


        }, this.delay);

    },
    setPropertyInfo(order) {
        let addressInput = $("#address");
        if (addressInput.length) {
            addressInput.val(order.address)
            InstallPost.findThePlace(order.address, {lat: Number(order.latitude), lng: Number(order.longitude)}, true);
        }

        let propertyTypeSelect = $(`[name="install_post_property_type"]`);
        if (propertyTypeSelect.length) {
            propertyTypeSelect.find(`option[value="${order.property_type}"]`).prop('selected', true)
            if (order.property_type == 2 || order.property_type == 3) {
                $(".document-required-warning").addClass("d-block").removeClass('d-none');
            } else {
                $(".document-required-warning").addClass("d-none").removeClass('d-block');

            }
        }
    },

    setDate(order) {
        let datePicker = $("#selectdate_custom_date");
        if (order.desired_date_type == 1) {
            $(`[name="install_post_desired_date"][value="asap"]`).prop('checked', true);
            $('[name="install_post_desired_date"]').val('asap');
            datePicker.addClass("d-none");
        } else {
            $(`[name="install_post_desired_date"][value="custom_date"]`).trigger('click')
            let d = helper.parseDate(order.desired_date)

            //Need to review this part. Why does it need setTimeout?
            setTimeout(()=>{
                datePicker.removeClass("d-none");
                //datePicker.datepicker("setDate", d);
                InstallPost.updateCalendar(d);
                $(`[name="install_post_custom_desired_date"]`).val(order.desired_date)
            }, 1000);
        }
    },
    setComment(order) {
        $(`[name="install_post_comment"]`).text(order.comment)
    },
    setFiles(order) {
        $("#files_list").html(` `);
        order.files.forEach(file => {
            $("#files_list").append(`
                <li>
                    <span>
                    <a target="_blank" href="${helper.getSiteUrl(`/private/document/file/${file.name}`)}"><strong>${file.name}</strong></a> UPLOADED ${moment(file.created_at).format("YYYY-MM-DD ,hh:mm A")}
                    <a class="text-danger c-p" onclick="window.eremoveFile(event,${file.id})"><strong>REMOVE</strong></a>
                    </span>
                </li>
            `)
        })

    },
    setFooter() {
        $('#submitOrder').html('<strong class="text-white">UPDATE ORDER</strong>').prop('disabled', false);
    },
    eremoveFile(event, id) {
        $.get(`/order/delete/file/${id}`).done(res => {
            event.target.parentNode.parentNode.parentNode.remove();

            if (!$('ul#files_list li').length) {
                $(`.document-required-warning`).addClass('d-block').removeClass('d-none');
            }
        });
    },

    deleteOrders() {
        $('#deleteAllOrders').on('click', (e) => {
            const self = $(e.target);

            helper.confirm(
                "",
                () => {
                    const deletePath = helper.getSiteUrl("/order/delete/all");
                    const posting = $.post(deletePath)
                        .done((res) => {
                            if (res.type == "success") {
                                window.location.reload();
                            }
                        })
                        .fail((res) => {
                            console.error(res);
                        });
                },
                () => ""
            );

        });
    },
    showLoading() {
      document.querySelector('body').classList.add('loading');
    },
    hideLoading() {
        document.querySelector('body').classList.remove('loading');
    }

}


export default Order;
