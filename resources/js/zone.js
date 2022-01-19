import global from "./global";
import helper from "./helper";
import obs from '../../node_modules/observable-slim/observable-slim';




//watch this object

let _obj_ = {
    zones: [],
}

let proxy = obs.create(_obj_, true, function (changes) {
    if (_obj_.zones.length == Zone.zones_count) {
        let modal = $(`#search-place-modal`);
        let zones = _obj_.zones.filter(x => x != null);
        if (zones.length) {
            let lowest_zone_fee = null;
            zones.forEach(zone => {
                if (lowest_zone_fee == null) {
                    lowest_zone_fee = zone
                } else {
                    if (lowest_zone_fee.zone_fee > zone.zone_fee) lowest_zone_fee = zone;
                };
            })
            // //disply place
            // //center place in map
            window.searchMap.setCenter(lowest_zone_fee.weneed.place.geometry.location);

            // //create marker
            console.log({ position: lowest_zone_fee.weneed.place.geometry.location, map, title: lowest_zone_fee.weneed.query } );
            new google.maps.Marker({ position: lowest_zone_fee.weneed.place.geometry.location, map, title: lowest_zone_fee.weneed.query }).setMap(window.searchMap);
            console.log(lowest_zone_fee);
            // //show modal
            if (modal.length) {
                modal.find(`[address-query]`).html(lowest_zone_fee.weneed.query);
                modal.find(`[zone-name]`).html(lowest_zone_fee.name || lowest_zone_fee.color);
                modal.find(`[zone-fee]`).html("$" + lowest_zone_fee.zone_fee);
                modal.modal();
            }
            return;
        } else {
            let location = window.placeNotInZones.place?.geometry?.location;
            //center place in map
            window.searchMap.setCenter(location);
            //create marker
            new google.maps.Marker({
                position: location,
                map,
                title: window.placeNotInZones.query,
            }).setMap(window.searchMap);
            //show modal
            if (modal.length) {
                modal.find(`[address-query]`).html(window.placeNotInZones.query);
                modal.find(`[zone-name]`).html("Out of Area");
                modal.find(`[zone-fee]`).html("Service Unavailable for this Area");

            }
            return;
        }
    }
    return;
});


const Zone = {
    init() {
        console.log("zones");
        this.initMap();
        this.addArea();
        window.openSetting = this.openSetting;
        window.zoneFeeChanged = this.zoneFeeChanged;
        window.deleteZone = this.deleteZone;
        this.onUpdateSettings();
        this.findPlace();
        this.holidays()

    }, async holidays() {
        let calender = $("#holidays");
        // Maintain array of dates
        let dates = new Array();
        function addDate(date) {
            if (jQuery.inArray(date, dates) < 0)
                dates.push(date);
        }

        function removeDate(index) {
            dates.splice(index, 1);
        }

        // Adds a date if we don't have it yet, else remove it
        function addOrRemoveDate(date) {
            var index = jQuery.inArray(date, dates);
            if (index >= 0)
                removeDate(index);
            else
                addDate(date);
        }

        // Takes a 1-digit number and inserts a zero before it
        function padNumber(number) {
            var ret = new String(number);
            if (ret.length == 1)
                ret = "0" + ret;
            return ret;
        }
        if (calender.length) {
            let holidays = await $.get(helper.getSiteUrl('/get/holidays'));
            dates = holidays;
            calender.datepicker({
                onSelect: function (date, inst) {
                    addOrRemoveDate(date);
                    $.post(helper.getSiteUrl(`/set/holidays`), { date }).done(res => {
                        // helper.alertError(res.msg)
                    })
                },
                beforeShowDay: function (date) {
                    let year = date.getFullYear();
                    // months and days are inserted into the array in the form, e.g "01/01/2009", but here the format is "1/1/2009"
                    let month = padNumber(date.getMonth() + 1);
                    let day = padNumber(date.getDate());
                    // This depends on the datepicker's date format
                    let dateString = month + "/" + day + "/" + year;

                    let gotDate = jQuery.inArray(dateString, dates);
                    if (gotDate >= 0) {
                        // Enable date so it can be deselected. Set style to be highlighted
                        return [true, "ui-state-holiday"];
                    }
                    //get holidays
                    // console.log(holidays);


                    // Dates not in the array are left enabled, but with no extra style
                    return [true, ""];
                }
            })
        }
    },
    createSearchMap() {
        let mapContainer = document.getElementById("search-place-map");
        mapContainer.innerHTML = ""
        const map = new google.maps.Map(mapContainer, { zoom: 12, center: window.defaultLocation, });
        Zone.loadZonesOnMap(map, false);
        window.searchMap = map;
    },
    zones_count: 0,
    findPlace() {
        let button = $("#find-place");
        let place = $("#place-input");
        let modal = $(`#search-place-modal`);
        if (button.length) {
            button.on("click", () => {
                let query = place.val().trim();
                if (query == '') return;
                const previousHtml = button.html();
                button.prop('disabled', true).html('SEARCHING...');
                Zone.createSearchMap();
                $.get(helper.getSiteUrl(`/get/zones/orderby/zone_fee/desc`), (zones) => {
                    let delay = 100;
                    let counter = 0;
                    let found = false;
                    this.zones_count = zones.length;
                    proxy.zones = [];
                    window.placeNotInZones = {};
                    zones.map(zone => {
                        counter++;
                        setTimeout(() => { //NEED TO DELAY TO AVOID CROSSING 10 REQUESTS PER SECOND
                            //console.log(zone)
                            //parse path points into google polygon
                            let paths = helper.parseZonePoints(zone.points);
                            zone.zone = new google.maps.Polygon({ paths });
                            zone.weneed = {};
                            zone.weneed.query = query;
                            //init search service
                            let service = new google.maps.places.PlacesService(window.map);
                            let request = { query, fields: ["name", "geometry"] };
                            //start search proccess
                            service.findPlaceFromQuery(request, (results, status) => {
                                //if we have results make proccess
                                // console.log(results, query)
                                if (results) {
                                    found = true;
                                    //if service work successfully.
                                    let place = results[0];
                                    zone.weneed.place = place;
                                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                                        const inZone = google.maps.geometry.poly.containsLocation(place.geometry.location, zone.zone);
                                        if (inZone) {
                                            proxy.zones.push(zone)

                                        } else {
                                            proxy.zones.push(null)
                                            window.placeNotInZones.place = place;
                                            window.placeNotInZones.query = query;

                                        }
                                    }
                                } else {
                                    proxy.zones.push(null)
                                    if (!found) {
                                        helper.alertError('Address not found. Please verify property address.');
                                        return false;
                                    }
                                }
                            })

                            button.prop('disabled', false).html(previousHtml);
                        }, delay * counter);

                        return zone;
                    })
                })
            });
        }
    },
    addArea() {
        let addAreaButton = $("#add-area");
        if (addAreaButton.length) {
            addAreaButton.on("click", () => {
                window.dm.setDrawingMode("polygon");
            });
        }
    },
    googleKey: global.googleKey,
    initMap() {
        window.initMap = this.startZonesMap;
        const src = `https://maps.googleapis.com/maps/api/js?key=${Zone.googleKey}&callback=window.initMap&libraries=drawing,geometry,places&v=weekly`;
        $("body").append(
            window.e("script", {
                src,
            })
        );
    },
    zones_color: [],
    zones_on_map: [],
    startZonesMap() {
        // Initialize and add the map
        // The location of defaultLocation
        const defaultLocation = {
            lat: 43.6156598,
            lng: -116.4865859,
        };
        window.defaultLocation = defaultLocation;
        // The map, centered at defaultLocation
        const map = new google.maps.Map(document.getElementById("zones-map"), {
            zoom: 10,
            center: defaultLocation,
        });
        let dm = new google.maps.drawing.DrawingManager({
            drawingMode: false,
            drawingControl: false,
            polygonOptions: {
                editable: true,
                clickable: true,
            },
        });
        dm.setMap(map);
        window.dm = dm;

        //after click [add area] and finish draw  store the shape
        Zone.onZoneCreated();

        //go to server and load zones from db
        //render zones in map
        Zone.loadZonesOnMap(map);

        window.map = map;
        window.google = google;
    },
    onZoneCreated() {
        google.maps.event.addListener(dm, "overlaycomplete", function (event) {
            dm.setDrawingMode(null);
            let { type, overlay } = event;
            const fillColor = Zone.uniqueColor();
            const strokeColor = fillColor;
            const id = Math.floor(Math.random() * 99999);
            overlay.setOptions({
                id,
                fillColor,
                strokeColor,
            });
            let points = Zone.getPolygonPoints(overlay);
            let size = Zone.calcar(overlay);
            $.post(helper.getSiteUrl(`/zones`), {
                color: fillColor,
                points,
                size,
            })
                .done((zone) => {
                    console.log(zone);
                    overlay.zone_id = zone.id;
                    Zone.updateZoneOnChangeOnMap(overlay);
                    Zone.appZoneToList(zone, "prepend");
                    Zone.zones_on_map.push(overlay);
                })
                .fail((res) => overlay.setMap(null));
        });
    },
    loadZonesOnMap(map, appendToList = true) {
        $.get("/zones").done((zones) => {
            zones.forEach((zone) => {
                let color = zone.color;
                if (appendToList) {
                    Zone.zones_color.push(color);
                }
                let points = Zone.parseZonePoints(zone.points);

                let overlay = new google.maps.Polygon({
                    paths: points,
                    fillColor: color,
                    strokeColor: color,
                    editable: appendToList,
                    clickable: appendToList,
                });
                overlay.setMap(map);
                overlay.zone_id = zone.id;
                if (appendToList) {
                    Zone.updateZoneOnChangeOnMap(overlay);
                    Zone.appZoneToList(zone);
                    Zone.zones_on_map.push(overlay);
                }
            });
        });
    },
    updateZoneOnChangeOnMap(overlay) {

        google.maps.event.addListener(overlay.getPath(), "insert_at", () => {
            console.log("INSERT AT ");
            Zone.updateZoneOnMap(overlay, overlay.zone_id);
        });
        google.maps.event.addListener(overlay, "click", (e) => {
            if (e.vertex != null) {
                console.log("Vertex: " + e.vertex);
                overlay.getPath().removeAt(e.vertex);
                Zone.updateZoneOnMap(overlay, overlay.zone_id);
            }
        });
        google.maps.event.addListener(overlay.getPath(), "set_at", () => {
            console.log("SET AT ");
            Zone.updateZoneOnMap(overlay, overlay.zone_id);
        });
    },
    deleteZone(zone_id) {
        console.log("DELETE");
        let yes = window.confirm("Do you want delete the zone?");
        if (yes) {
            $.get(helper.getSiteUrl(`/delete/zone/${zone_id}`)).done(
                (success) => {
                    $(`#zone-item-${zone_id}`).remove();
                    $("#zones-list").html(``);
                    //reset colors
                    Zone.zones_color = [];
                    Zone.zones_on_map = [];
                    Zone.startZonesMap();
                }
            );
        }
    },

    zoneFeeChanged(event, zone_id) {
        let zone_fee = event.target.value;
        $.post(helper.getSiteUrl(`/update/zone/fee/${zone_id}`), {
            zone_fee,
        });
    },
    onUpdateSettings() {
        const form = $(`#update-zone-settings-from`);
        if (form.length) {
            form.on("submit", (e) => {
                e.preventDefault();
                let f = e.target;

                let name = f.zone_name.value;
                let installer_points = f.installer_points.value;

                let id = f.id.value;
                let data = {
                    id,
                    name,
                    installer_points,
                    su: f.su.checked ? 1 : 0,
                    m: f.m.checked ? 1 : 0,
                    tu: f.tu.checked ? 1 : 0,
                    w: f.w.checked ? 1 : 0,
                    th: f.th.checked ? 1 : 0,
                    f: f.f.checked ? 1 : 0,
                    sa: f.sa.checked ? 1 : 0,
                };
                $.post(form.attr("action"), data).done((success) => {
                    $("#zone-settings-modal").modal("hide");
                    if (name.length) {
                        $(`#zone-item-${id}`).find(`[zone-name]`).html(name);
                    }
                });
            });
        }
    },
    openSetting(zone_id) {
        $.get(helper.getSiteUrl(`/zones/${zone_id}`))
            .done((zone) => {
                const modal = $("#zone-settings-modal");
                if (modal.length) {
                    let colorOpctiy = Zone.getColorOpctiy(zone.color);
                    modal.find(`#zone-color-settings`).css("border", "3px solid " + zone.color).css("color", '#000000').css('background-color', colorOpctiy);
                    modal.find(`[name="id"]`).val(zone.id);
                    modal.find(`[name="zone_name"]`).val(zone.name);
                    modal.find(`[name="installer_points"]`).val(zone.installer_points);
                    modal.find(`[name="su"]`).prop("checked", zone.su ? 1 : 0);
                    modal.find(`[name="m"]`).prop("checked", zone.m ? 1 : 0);
                    modal.find(`[name="tu"]`).prop("checked", zone.tu ? 1 : 0);
                    modal.find(`[name="w"]`).prop("checked", zone.w ? 1 : 0);
                    modal.find(`[name="th"]`).prop("checked", zone.th ? 1 : 0);
                    modal.find(`[name="f"]`).prop("checked", zone.f ? 1 : 0);
                    modal.find(`[name="sa"]`).prop("checked", zone.sa ? 1 : 0);

                    modal.modal();
                }
            })
            .fail((res) => console.log(res));
    },
    hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    },
    getColorOpctiy(color) {
        let { r, g, b } = Zone.hexToRgb(color);
        const opctiy = 0.3;
        let colorOpctiy = `rgb(${r},${g},${b},${opctiy})`;
        return colorOpctiy;
    }
    ,
    appZoneToList(zone, where = "append") {
        // console.log(zone);
        const list = $("#zones-list");
        let color = zone.color;
        let colorOpctiy = Zone.getColorOpctiy(color);

        list[where](`
        <div class="zone-list-itme bg-white w-100 height-rem-5 p-1 " id="zone-item-${zone.id}">
        <div class="d-flex align-items-center mb-1" style="gap:2px">
            <div
                onclick="window.deleteZone(${zone.id})"
                class="width-px-25 height-px-25 bg-danger text-white d-flex justify-content-center align-items-center c-p">
                <div class="fas fa-times"></div>
            </div>
            <div class="px-1 w-100 height-px-25 text-center text-black font-px-16 d-flex justify-content-center align-items-center" style="border: 3px solid ${color};background-color:${colorOpctiy}">
                <strong zone-name>${zone.name || "Name of Zone"}</strong>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-1" style="gap:2px">
            <div
                onclick="window.openSetting(${zone.id})"
                class="width-px-25 height-px-25 text-white d-flex justify-content-center align-items-center c-p">
                <div class="fas fa-cog text-secondary font-px-22"></div>
            </div>
            <div class="px-1  height-px-25  d-flex  c-p">
                <label for="" class="text-dark m-0 px-1 font-px-16">Zone Fee: </label>
                <div class="input-group width-px-100">
                    <div class="input-group-prepend">
                        <span class="input-group-text p-0 px-1">$</span>
                    </div>
                    <input type="number" value="${zone.zone_fee || ""}" step="any" class="form-control text-right form-control-sm font-px-13"  onchange="window.zoneFeeChanged(event,${zone.id})">
                </div>
            </div>
        </div>

    </div>
        `);
    },
    updateZoneOnMap(overlay, zone_id) {
        //
        console.log("UPDATE ZONE:" + zone_id);
        let points = Zone.getPolygonPoints(overlay);
        let size = Zone.calcar(overlay);
        $.post(helper.getSiteUrl(`/update/zone/${zone_id}`), {
            _mthod: "patch",
            points,
            size,
        });

    },
    calcar(overlay) {
        let area = google.maps.geometry.spherical.computeArea(
            overlay.getPath()
        );
        return area;
    },
    getPolygonPoints(polygon) {
        let vertices = polygon.getPath(); // MVCArray
        let pointsArray = []; //list of polyline points
        for (let i = 0; i < vertices.getLength(); i++) {
            let xy = vertices.getAt(i); //LatLang for a polyline
            let item = {
                lat: xy.lat(),
                lng: xy.lng(),
            };
            pointsArray.push(item);
        }

        let points = pointsArray;
        return points;
    },
    parseZonePoints(points) {
        points = JSON.parse(points);
        return points.map((p) => {
            return {
                lat: parseFloat(p.lat),
                lng: parseFloat(p.lng),
            };
        });
    },

    randomColor() {
        var letters = "0123456789ABCDEF";
        var color = "#";
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    },
    uniqueColor() {
        let color = Zone.randomColor();
        while (Zone.zones_color.includes(color)) {
            console.log("old color:" + color);
            color = Zone.randomColor();
            console.log("new color:" + color);
        }
        //finly get unquie color
        Zone.zones_color.push(color);
        return color;
    },
};

export default Zone;
