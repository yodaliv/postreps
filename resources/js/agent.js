import helper from "./helper";
import OfficesSearch from "./officesSearch";
let Agent = {
    init() {
        window.editAgentModal = this.editAgentModal;
        window.updatePaymentMethodAgent = this.updatePaymentMethodAgent;
        window.noteAgentModal = this.noteAgentModal;
        window.changeOffice = this.changeOffice;
        this.agentSearchInput();
        this.showAgentsEntries();
        this.destroyAllAgents();
        this.agentType();
        window.OfficesSearch = OfficesSearch.list({
            table: "#changeOfficeTable",
            search_element: "#changeOfficeTableSearch",
            tableName: "changeOfficeTable",
        });

        this.importAgents();
        $("#importAgentFile").on('change', () => $('.loading-overlay').toggleClass('d-none'))
    },
    changeOffice(id, current_office) {
        //
        localStorage.selected_agent_for_change_office = id;
        localStorage.current_office = current_office;

        let modal = $("#changeOfficeFormModal");
        if (modal.length) {
            window.OfficesSearch.api().draw();
            modal.modal();
            modal.on("hidden.bs.modal", () => {
                localStorage.removeItem("selected_agent_for_change_office");
            });
        }
    },
    agentType() {
        let select = $("#agentType");
        if (select.length) {
            select.on("change", () => {
                let inactive = select.val();
                let dt = window.agentDataTable;
                dt.api().column(0).search(inactive).draw();
            });
        }
    },
    noteAgentModal(id) {
        let modal = $("#noteAgentFormModal");
        let form = $("#noteAgentForm");
        if (modal.length) {
            $.get(helper.getSiteUrl(`/get/agent/${id}`))
                .done((agent) => {
                    form.attr(`action`, helper.getSiteUrl(`/agent/${agent.id}/create/note`));
                    modal.find(`[name='note']`).text(agent.note);
                    $("[note-agent-name]").text(agent.name)
                    modal.modal();
                })
                .fail((res) => {
                    console.log(res);
                });
        }
    },
    updatePaymentMethodAgent(agent, payment_method) {
        $.post(helper.getSiteUrl(`/agent/${agent}/update/payment/method`), {
            payment_method,
        })
            .done((res) => { })
            .fail((res) => {
                console.log(res);
            });
    },
    destroyAllAgents() {
        let button = $("#destroyAllAgents");
        if (button.length) {
            button.on("click", () => {
                helper.confirm(
                    "",
                    //on click Yes
                    () => {
                        //delete end point
                        let deletePath =
                            helper.getSiteUrl("/agents/delete/all");
                        let href = $.post(deletePath)
                            .done((res) => {
                                if (res.type === "success") {
                                    //rerender dataTable
                                    window.agentDataTable.api().draw();
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
    showAgentsEntries() {
        let select = $("#showAgentsEntries");
        if (select.length) {
            select.on("change", (event) => {
                let selected = parseInt(event.target.value);
                window.agentDataTable.api().context[0]._iDisplayLength =
                    selected;
                window.agentDataTable.api().draw();
            });
        } else {
            console.error(`#showAgentsEntries not exists`);
        }
    },
    agentSearchInput() {
        let input = $("#agentSearchInput");
        if (input.length) {
            input.on("keyup", (event) => {
                let input = event.target;
                window.agentDataTable.fnFilter(input.value);
            });
        } else {
            console.error(`#officeSearchInput no exists`);
        }
    },
    agentsDatatabe() {
        let table = $("#agentsTable");
        let e = window.e;
        if (table.length) {
            window.agentDataTable = table.dataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                },
                pageLength: 10,
                dom: "rtip",
                ajax: helper.getSiteUrl("/datatable/agents"),
                serverSide: true,
                searchCols: [{ search: 0 }],

                columns: [
                    {
                        data: "inactive",
                        defaultContent: "404",
                        title: "inactive",
                        name: "agents.inactive",
                        visible: 0,
                    },
                    {
                        data: "name",
                        defaultContent: "404",
                        title: "Name",
                        name: "users.name",
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
                        data: "office_name",
                        defaultContent: "404",
                        title: "office_name",
                        name: "office_users.name",
                        visible: 0,
                    },
                    {
                        data: "id",
                        defaultContent: "AGENT DETAILS",
                        title: "AGENT DETAILS",
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
                                htmlContent: `${r.city ?? ""},${r.state ?? ""
                                    },${r.zipcode ?? ""}<br>`,
                            });
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
                        defaultContent: "404",
                        orderable: 0,
                        searchable: 0,
                        title: "AGENT OFFICE",
                        render(d, t, r) {
                            let content = "<div class='text-center'>";
                            content += e("b", {
                                htmlContent:
                                    " <b>" +
                                    (r.office_name ?? "No Office Assigned") +
                                    "</b><br>",
                            });
                            content += e("b", {
                                htmlContent:
                                    "" + (r.office_phone ?? "") + " <br>",
                            });
                            content += e("button", {
                                htmlContent: "Change Office",
                                class: "btn btn-sm btn-primary mt-2 mb-3",
                                onclick: `window.changeOffice(${r.id},${r.agent_office})`,
                            });
                            content += e("select", {
                                htmlContent: `
                                <option ${r.payment_method == "1" ? "selected" : "" } value="1">Pay at time of Order</option>
                                <option ${r.payment_method == "2" ? "selected" : "" } value="2">Invoiced</option>
                                <option ${r.payment_method == "3" ? "selected" : "" } value="3">Office Pay</option>
                            `,
                                class: "form-control text-center payment-method-select mx-auto",
                                onchange: `window.updatePaymentMethodAgent(${r.id},this.value)`,
                            });
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
                                    `/agent/${r.id}/reset/password`
                                ),
                                class: " font-weight-bold",
                            });
                            content += e("a", {
                                htmlContent: "View Orders <br>",
                                href: "",
                                class: " font-weight-bold",
                            });
                            content += e("a", {
                                htmlContent: "Account Notes <br>",
                                class: " font-weight-bold",
                                onclick: `window.noteAgentModal(${r.id})`,
                            });
                            content += e("a", {
                                htmlContent: "Edit Account <br>",
                                // href: helper.getSiteUrl(`/offices/${r.id}/edit`),
                                onclick: `window.editAgentModal(${r.id})`,
                                class: " font-weight-bold",
                            });
                            content += e("a", {
                                htmlContent: "Inventory Settings <br>",
                                href: "",
                                class: " font-weight-bold",
                            });
                            content += e("a", {
                                htmlContent: "Financial History <br>",
                                href: "",
                                class: " font-weight-bold",
                            });
                            content += "</div>";
                            return content;
                        },
                    },
                ],
            });
        }
    },
    editAgentModal(id) {
        $.get(helper.getSiteUrl(`/get/agent/${id}`))
            .then((agent) => {
                let editAgentFormModal = $("#editAgentFormModal");
                let editAgentForm = $("#editAgentForm");
                if (editAgentFormModal.length) {
                    editAgentForm.attr(
                        "action",
                        helper.getSiteUrl("/agents/" + agent.id)
                    );
                    editAgentFormModal
                        .find(`[name='first_name']`)
                        .val(agent.first_name);
                    editAgentFormModal
                        .find(`[name='last_name']`)
                        .val(agent.last_name);
                    editAgentFormModal.find(`[name='phone']`).val(agent.phone);
                    editAgentFormModal
                        .find(`[name='address']`)
                        .val(agent.address);
                    editAgentFormModal.find(`[name='city']`).val(agent.city);
                    editAgentFormModal
                        .find(`[name='re_license']`)
                        .val(agent.re_license);
                    editAgentFormModal.find(`[name='email']`).val(agent.email);
                    editAgentFormModal
                        .find(`[name='zipcode']`)
                        .val(agent.zipcode);
                    editAgentFormModal
                        .find(`[name='state']`)
                        .find(`[value="${agent.state}"]`)
                        .prop("selected", true);
                    editAgentFormModal
                        .find(`[name='agent_office']`)
                        .find(`[value="${agent.agent_office}"]`)
                        .prop("selected", true);
                    editAgentFormModal
                        .find(`[name='inactive']`)
                        .find(`[value="${agent.inactive}"]`)
                        .prop("selected", true);
                    editAgentFormModal.modal();
                } else {
                    console.error("editAgentFormModal not exists");
                }
            })
            .fail((res) => console.error(`failed to get agent for edit from`));
    },

    importAgents() {
        $('#importAgentFile').on('change', () => {
            $('#importAgentsForm').trigger('submit');
        });

        $('#importAgentsForm').on('submit', () => {
            $('label[for="importAgentFile"]').text('PROCESSING...').css('cursor', 'none');
        });
    }
};

export default Agent;
