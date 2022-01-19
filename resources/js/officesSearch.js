import helper from "./helper";
export default {
    list(props) {
        let table = $(props.table);
        if (!table.length) return;
        let input = $(props.search_element);
        if (input.length) {
            input.on("keyup", (event) => {
                let dt = window[props.tableName];
                if (dt) {
                    dt.fnFilter(event.target.value);
                }
            });
        }
        window[props.tableName] = table.dataTable({
            dom: "",
            pageLength: -1,
            ajax: helper.getSiteUrl("/datatable/offices"),
            serverSide: true,
            columns: [
                {
                    data: "inactive",
                    name: "offices.inactive",
                    defaultContent: "404",
                    title: "inactive",
                    visible: 0,
                },
                {
                    data: "name",
                    name: "users.name",
                    defaultContent: "404",
                    title: "Name",
                    visible: 0,
                },
                {
                    data: "address",
                    defaultContent: "404",
                    title: "address",
                    name: "users.address",
                    visible: 0,
                },
                {
                    data: "city",
                    defaultContent: "404",
                    title: "city",
                    name: "users.city",
                    visible: 0,
                },
                {
                    data: "state",
                    defaultContent: "404",
                    title: "state",
                    name: "users.state",
                    visible: 0,
                },
                {
                    data: "zipcode",
                    defaultContent: "404",
                    title: "zipcode",
                    name: "users.zipcode",
                    visible: 0,
                },
                {
                    data: "phone",
                    defaultContent: "404",
                    title: "phone",
                    name: "users.phone",
                    visible: 0,
                },
                {
                    data: "email",
                    defaultContent: "404",
                    title: "email",
                    name: "users.email",
                    visible: 0,
                },
                {
                    defaultContent: "404",
                    orderable: 0,
                    searchable: 0,
                    width: "45%",
                    className: "width-percent-45",
                    render(d, t, r, settings) {
                        let content = "";
                        if (r.name) {
                            content += e("span", {
                                htmlContent: r.name + "<br>",
                                office_id: r.id,
                            });
                        }
                        return content;
                    },
                },
                {
                    defaultContent: "404",
                    orderable: 0,
                    searchable: 0,
                    width: "65%",
                    className: "width-percent-65",
                    render(d, t, r) {
                        let content = "";
                        content += e("span", {
                            htmlContent: `${r.city ?? ""},${r.state ?? ""},${
                                r.zipcode ?? ""
                            }<br>`,
                        });
                        return content;
                    },
                },
            ],
        });
        table.on("draw.dt", function () {
            let trs = table.find("tbody tr");
            let current_office = trs.find(
                `[office_id='${localStorage.current_office}']`
            );
            if (current_office.length) {
                current_office.parent().parent().addClass("selectedOffice");
            }
            Object.values(trs).forEach((tr) => {
                if (tr instanceof HTMLElement) {
                    $(tr).on("click", () => {
                        trs.removeClass("selectedOffice");
                        let _tr = $(tr);
                        _tr.addClass("selectedOffice");
                        let office_id = _tr
                            .find("[office_id]")
                            .attr("office_id");
                        localStorage.new_office_id_for_agent = office_id;
                    });
                }
            });
        });

        $("#searchOfficeSubmit").on("click", () => {
            let agent_id = localStorage.selected_agent_for_change_office ?? 0;
            let office_id = localStorage.new_office_id_for_agent ?? 0;
            $("#changeOfficeForm").attr(
                "action",
                `set/new/office/${office_id}/for/agent/${agent_id}`
            );
        });

        return table;
    },
};
