import helper from "./helper";
const Register = {
    init() {
        this.continue();
        this.selectOffice();
    },
    continue() {
        const modal = $("#setOfficeFormModal");

        $("#continue").on("click", async (e) => {
            const form = $("#agentRegistrationForm").get(0);
            let validation = await helper.validateForm(form);
            if (validation.ok) {
                modal.modal();
            }
        });
    },
    selectOffice() {
        let table = $("#setOfficeTable");
        if (!table.length) return;
        let input = $("#setOfficeSearch");
        if (input.length) {
            input.on("keyup", (event) => {
                $("#officeNotFound").removeClass("d-none");
                $("#officeNotFound").on("click", (e) => {
                    e.preventDefault();
                    console.log("sdsd");
                    helper.confirm(
                        "Your account will be created without an office! Proceed?",
                        () => {
                            window.officeNotFound = true;
                            $("#agentRegistrationForm").trigger("submit");
                        },
                        () => {}
                    );
                });
                let dt = window.setOfficeTable;
                if (dt) {
                    dt.fnFilter(event.target.value);
                }
            });
        }
        window.setOfficeTable = table.dataTable({
            dom: "",
            pageLength: -1,
            ajax: helper.getSiteUrl("/datatable/public/offices"),
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
            Object.values(trs).forEach((tr) => {
                if (tr instanceof HTMLElement) {
                    $(tr).on("click", () => {
                        trs.removeClass("selectedOffice");
                        let _tr = $(tr);
                        _tr.addClass("selectedOffice");
                        let office_id = _tr
                            .find("[office_id]")
                            .attr("office_id");

                        $(`[name="agent_office"]`).val(office_id);
                    });
                }
            });
        });
    },
};

$(() => Register.init());
