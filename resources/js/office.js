import helper from "./helper";

let office = {
    init() {
        this.watchLogoImageInput();
        window.editOfficeModal = this.editOfficeModal;
        window.noteOfficeModal = this.noteOfficeModal;
        window.viewAgents = this.viewAgents;
        window.addAgent = this.addAgent;
        window.updatePaymentMethodOffice = this.updatePaymentMethodOffice;
        if (window.isHaveErrorCreateOfficeFormModel) {
            $("#createOfficeFormModal").modal();
        }
        this.officeSearchInput();
        this.showOfficesEntries();
        this.destroyAllOffices();
        this.officeType();
    },
    officeType() {
        let select = $("#officeType");
        if (select.length) {
            select.on("change", () => {
                let inactive = select.val();
                let dt = window.officeDataTable;
                dt.api().column(0).search(inactive).draw();
            });
        }
    },
    noteOfficeModal(id) {
        let modal = $("#noteOfficeFormModal");
        let form = $("#noteOfficeForm");
        if (modal.length) {
            $.get(helper.getSiteUrl(`/get/office/${id}`))
                .done((office) => {
                    form.attr(`action`,helper.getSiteUrl(`/office/${office.id}/create/note`));
                    modal.find(`[name='note']`).text(office.note);
                    $("[note-office-name]").text(office.name)
                    modal.modal();
                })
                .fail((res) => {
                    console.log(res);
                });
        }
    },
    updatePaymentMethodOffice(office, payment_method) {
        $.post(helper.getSiteUrl(`/office/${office}/update/payment/method`), {
            payment_method,
        })
            .done((res) => { })
            .fail((res) => {
                console.log(res);
            });
    },
    addAgent(id) {
        let modal = $("#createAgentFormModal");
        if (modal.length) {
            let option = modal
                .find('[name="agent_office"]')
                .find(`option[value="${id}"]`);
            if (option.length) {
                // select office
                option.prop("selected", true);
                //open modal
                modal.modal();
            }
        } else {
            console.error("#createAgentFormModal does not exist");
        }
    },
    viewAgents(id, inactive) {

        $.get(helper.getSiteUrl(`/get/office/${id}`)).done(res => {
            if ($("#agentSearchInput").length) $("#agentSearchInput").val(res.name);
            // let agentType = $("#agentType");
            // if (agentType.length) {
            //     agentType.val(inactive);
            //     agentType.get(0).dispatchEvent(new Event("change"));
            // }
            if (window.agentDataTable) {
                // Added agent office column index to only filter by agent office
                window.agentDataTable.fnFilter(res.name, 8);
                $("#pills-agents-tab").trigger("click");
            } else {
                console.error("Agent datatable does does not exist");
            }
        })
    },
    destroyAllOffices() {
        let button = $("#destroyAllOffices");
        if (button.length) {
            button.on("click", () => {
                helper.confirm(
                    "",
                    //on click Yes
                    () => {
                        //delete end point
                        let deletePath = helper.getSiteUrl(
                            "/offices/delete/all"
                        );
                        let href = $.post(deletePath)
                            .done((res) => {
                                if (res.type === "success") {
                                    //rerender dataTable
                                    window.officeDataTable.api().draw();
                                }
                            })
                            .fail((res) => {
                                console.error(res);
                            });
                    },
                    //on click cancel
                    () => ""
                );
            });
        }
    },
    officeSearchInput() {
        let input = $("#officeSearchInput");
        if (input.length) {
            input.on("keyup", (event) => {
                let input = event.target;
                window.officeDataTable.fnFilter(input.value);
            });
        } else {
            console.error(`#officeSearchInput does not exist`);
        }
    },
    showOfficesEntries() {
        let select = $("#showOfficesEntries");
        if (select.length) {
            select.on("change", (event) => {
                let selected = parseInt(event.target.value);
                window.officeDataTable.api().context[0]._iDisplayLength =
                    selected;
                window.officeDataTable.api().draw();
            });
        } else {
            console.error(`#showOfficesEntries does not exist`);
        }
    },
    officesDatatabe() {
        let table = $("#officesTable");
        let e = window.e;
        if (table.length) {
            window.officeDataTable = table.dataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                },
                pageLength: 10,
                dom: "rtip",
                ajax: helper.getSiteUrl("/datatable/offices"),
                serverSide: true,
                searchCols: [{ search: 0 }],
                columns: [
                    {
                        data: "inactive",
                        name: "offices.inactive",
                        defaultContent: "404",
                        title: "offices.inactive",
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
                        data: "primary_contact",
                        defaultContent: "404",
                        title: "primary_contact",
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
                        data: "id",
                        defaultContent: "OFFICE DETAILS",
                        title: "OFFICE DETAILS",
                        render(d, t, r) {
                            let content = "";
                            if (r.name) {
                                content += e("b", {
                                    htmlContent: r.name + "<br>",
                                });
                            }
                            if (r.address) {
                                content += e("span", {
                                    htmlContent: r.address + "<br>",
                                });
                            }
                            content += e("span", {
                                htmlContent: `${r.city ?? ""}, ${r.state ?? ""
                                    }, ${r.zipcode ?? ""}<br>`,
                            });
                            if (r.primary_contact) {
                                content += e("span", {
                                    htmlContent: r.primary_contact + "<br>",
                                });
                            }
                            if (r.phone) {
                                content += e("span", {
                                    htmlContent: r.phone + "<br>",
                                });
                            }
                            if (r.email) {
                                content += e("span", {
                                    htmlContent: r.email + "<br>",
                                });
                            }
                            return content;
                        },
                    },
                    {
                        defaultContent: "OFFICE DETAILS",
                        orderable: 0,
                        searchable: 0,
                        title: "OFFICE AGENTS",
                        render(d, t, r) {
                            let content = "<div class='text-center'>";
                            content += e("span", {
                                htmlContent:
                                    " <b>TOTAL AGENTS:</b> " +
                                    r.agents_count +
                                    "<br>",
                            });
                            content += e("b", {
                                htmlContent: `<a onclick="window.viewAgents('${r.id}',${r.inactive})">View Agents</a> <br>`,
                            });
                            content += e("button", {
                                htmlContent: "Add Agent",
                                class: "btn btn-sm btn-primary mt-2 mb-3",
                                onclick: `window.addAgent(${r.id})`,
                            });
                            content +=
                                "<div class='d-flex justify-content-center'>";
                            content += e("select", {
                                htmlContent: `
                                <option ${r.payment_method == "1" ? "selected" : ""
                                    } value="1">Pay at time of Order</option>
                                <option ${r.payment_method == "2" ? "selected" : ""
                                    } value="2">Invoiced</option>
                            `,
                                class: "form-control text-center payment-method-select",
                                onchange: `window.updatePaymentMethodOffice(${r.id},this.value)`,
                            });
                            content += "</div>";
                            content += "</div>";
                            return content;
                        },
                    },
                    {
                        defaultContent: "ACTION",
                        orderable: 0,
                        searchable: 0,
                        title: "ACTION",
                        render(d, t, r) {
                            let content = "<div class='text-center'>";
                            content += e("a", {
                                htmlContent: "Password Reset <br>",
                                href: helper.getSiteUrl(
                                    `/office/${r.id}/reset/password`
                                ),
                                class: "",
                            });
                            content += e("a", {
                                htmlContent: "View Orders <br>",
                                href: "",
                                class: "",
                            });
                            content += e("a", {
                                htmlContent: "Account Notes <br>",
                                class: "",
                                onclick: `window.noteOfficeModal(${r.id})`,
                            });
                            content += e("a", {
                                htmlContent: "Edit Account <br>",
                                // href: helper.getSiteUrl(`/offices/${r.id}/edit`),
                                onclick: `window.editOfficeModal(${r.id})`,
                                class: "",
                            });
                            content += e("a", {
                                htmlContent: "Inventory Settings <br>",
                                href: "",
                                class: "",
                            });
                            content += e("b", {
                                htmlContent:
                                    "<a href=''>Financial History</a> <br>",
                            });
                            content += "</div>";
                            return content;
                        },
                    },
                ],
            });
        }
    },
    editOfficeModal(id) {
        $.get(helper.getSiteUrl(`/get/office/${id}`))
            .then((office) => {
                let editOfficeFormModal = $("#editOfficeFormModal");
                let editOfficeForm = $("#editOfficeForm");
                if (editOfficeFormModal.length) {
                    editOfficeForm.attr(
                        "action",
                        helper.getSiteUrl("/offices/" + office.id)
                    );
                    editOfficeFormModal.find(`[name='name']`).val(office.name);
                    editOfficeFormModal
                        .find(`[name='primary_contact']`)
                        .val(office.primary_contact);
                    editOfficeFormModal
                        .find(`[name='address']`)
                        .val(office.address);
                    editOfficeFormModal
                        .find(`[name='phone']`)
                        .val(office.phone);
                    editOfficeFormModal.find(`[name='city']`).val(office.city);
                    editOfficeFormModal
                        .find(`[name='email']`)
                        .val(office.email);
                    editOfficeFormModal
                        .find(`[name='state']`)
                        .find(`option[value="${office.state}"]`)
                        .prop("selected", true);
                    editOfficeFormModal
                        .find(`[name='zipcode']`)
                        .val(office.zipcode);
                    editOfficeFormModal
                        .find(`[name='website']`)
                        .val(office.website);
                    editOfficeFormModal
                        .find(`[name='inactive']`)
                        .find(`option[value="${office.inactive}"]`)
                        .prop("selected", true);
                    editOfficeFormModal
                        .find(`[name='private']`)
                        .find(`option[value="${office.private}"]`)
                        .prop("selected", true);
                    editOfficeFormModal
                        .find(`[name='name_abbreviation']`)
                        .val(office.name_abbreviation);
                    editOfficeFormModal
                        .find(`[name='region_id']`)
                        .find(`option[value="${office.region_id}"]`)
                        .prop("selected", true);
                    editOfficeFormModal
                        .find(".edit_logo_preview")
                        .find("img")
                        .prop(
                            "src",
                            helper.getSiteUrl(
                                "/private/image/office/" + office.logo_image
                            )
                        );
                    editOfficeFormModal.modal();
                } else {
                    console.error("editOfficeFormModal does not exist");
                }
            })
            .fail((res) => console.error(`failed to get office for edit form`));
    },
    watchLogoImageInput() {
        $("[name='logo_image']").on("change", (e) => {
            let logo_image = e.target.files[0];
            $(".logo_preview img").prop("src", URL.createObjectURL(logo_image));
            $(".logo_preview").show();
        });
        $("[name='edit_logo_image']").on("change", (e) => {
            let logo_image = e.target.files[0];
            $(".edit_logo_preview img").prop(
                "src",
                URL.createObjectURL(logo_image)
            );
        });
    },
};

export default office;
