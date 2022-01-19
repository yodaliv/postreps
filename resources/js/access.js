import helper from "./helper";

const Access = {
    init() {
        console.log("Acccess js");
        this.editAccessOfficeDataTable();
        window.editAccessOfficeToggleAcces = this.editAccessOfficeToggleAcces;
        window.editAccessAgentToggleAcces = this.editAccessAgentToggleAcces;
        window.toggleLocked = this.toggleLocked;
        this.editAccessOfficeSearch();
        // Agent
        this.editAccessAgentDataTable();
        this.editAccessAgentSearch();
        localStorage.removeItem("accessory_access");

        $("#editAccessFormModal").on("hide.bs.modal", () =>
            localStorage.removeItem("accessory_access")
        );

        //global switch
        $(".accessOfficeGlobalSwitch").on('click', (e) => {
            let { checked } = e.target;
            console.log("CCCC");
            console.log(checked);
            console.log(localStorage.edit_post);
            let isaccessory = Access.isAccessoryAcess();
            console.log(localStorage.edit_post);
            if (!isaccessory) {

                $.get(`/post/${localStorage.edit_post}/access/set/all/office/access/${checked}`).done(res => {
                    console.log(res);
                    window.editAccessOfficeDataTable.api().draw();
                    window.editAccessAgentDataTable.api().draw();
                })
            } else {

                $.get(`/accessory/${localStorage.edit_post}/access/set/all/office/access/${checked}`).done(res => {
                    console.log(res);
                    window.editAccessOfficeDataTable.api().draw();
                    window.editAccessAgentDataTable.api().draw();
                })
            }
        })
    },
    isAccessoryAcess() {
        return localStorage.getItem("accessory_access");
    },
    toggleLocked(event, object, id) {

        let accessory = Access.isAccessoryAcess();
        if (object == "office") {
            let locked = $(event.target);
            let isLocked = locked.prop("checked");
            let office = id;
            let post = localStorage.edit_post;
            let price = $(`#postInstallEditPrice${office}`).val();
            let access = $("#officeAccess" + office).prop("checked");

            // return;
            if (isLocked == true) {
                $.get(
                    `/update/${accessory ? "accessory" : "post"
                    }/office/${post}/${office}/${access}/${price}/${isLocked} `
                ).done((res) => {
                    window.editAccessOfficeDataTable.api().draw();
                });
            } else {
                $.get(
                    `/update/${accessory ? "accessory" : "post"
                    }/office/${post}/${office}/${access}/${price}/${isLocked} `
                ).done((res) => {
                    window.editAccessOfficeDataTable.api().draw();
                });
            }
        }
        if (object == "agent") {
            let locked = $(event.target);
            let isLocked = locked.prop("checked");
            let agent = id;
            let post = localStorage.edit_post;
            let price = $(`#postInstallEditPriceAgent${agent}`).val();
            let access = $("#AgentAccess" + agent).prop("checked");

            console.log("ACCESS: ", access);
            console.log("PRICE: ", price);
            console.log("POST: ", post);
            console.log("AGENT: ", agent);
            console.log("LOCKED: ", isLocked);
            // return;
            if (isLocked == true) {
                $.get(
                    `/update/${accessory ? "accessory" : "post"
                    }/agent/${post}/${agent}/${access}/${price}/${isLocked}`
                ).done((res) => {
                    window.editAccessAgentDataTable.api().draw();
                });
            } else {
                $.get(
                    `/update/${accessory ? "accessory" : "post"
                    }/agent/${post}/${agent}/${access}/${price}/${isLocked}`
                ).done((res) => {
                    window.editAccessAgentDataTable.api().draw();
                });
            }
        }
    },
    inputTableChange() {
        let accessory = Access.isAccessoryAcess();

        window.editAccessOfficeDataTable
            .find(`[name='price']`)
            .on("change", (event) => {
                let input = $(event.target);
                let office = input.attr("office");
                $.get(
                    `/update/${accessory ? "accessory" : "post"}/${localStorage.edit_post
                    }/office/${office}/price/${input.val()}`
                ).then((res) => {
                    console.log(res);
                });
            });
        window.editAccessAgentDataTable
            .find(`[name='price']`)
            .on("change", (event) => {
                event.stopImmediatePropagation();
                let input = $(event.target);
                let agent = input.attr("agent");
                $.get(
                    `/update/${accessory ? "accessory" : "post"}/${localStorage.edit_post
                    }/agent/${agent}/price/${input.val()}`
                ).then((res) => {
                    console.log(res);
                });
            });
    },
    editAccessOfficeToggleAcces(event, office) {
        let access = event.target.checked;
        let post = localStorage.edit_post;
        let accessory = Access.isAccessoryAcess();
        let price = window.editAccessOfficeDataTable
            .find(`[office="${office}"]`)
            .val();
        $.get(
            `/update/${accessory ? "accessories" : "posts"
            }/office/${post}/${office}/${access}/${price}`
        );

        let agents = $(`#agentsTable input[type="checkbox"][agentOfficeId="${office}"]`);
        for (let index = 0; index < agents.length; index++) {
            const element = agents.get(index);
            if (element.checked == event.target.checked) {

            } else {
                element.click()
            }


        }
    },
    editAccessOfficeSearch() {
        let input = $("#officeEditSearchInput");
        if (input.length) {
            input.on("keyup", () => {
                window.editAccessOfficeDataTable.fnFilter(input.val());
            });
        }
    },
    editAccessOfficeDataTable() {
        let table = $("#officesTable");
        if (table.length) {
            window.editAccessOfficeDataTable = table.dataTable({
                pageLength: 5000,
                dom: " ",
                ajax: helper.getSiteUrl("/datatable/post/offices?post_id=0"),
                serverSide: true,
                columns: [
                    {
                        name: "users.name",
                        data: "name",
                        defaultContent: 404,
                        title: "OFFICE",
                        className: "width-percent-40",
                    },
                    {
                        searchable: 0,
                        orderable: 0,
                        defaultContent: 404,
                        title: "ACCESS",
                        render(d, t, r) {
                            return `
                            <!-- Rectangular switch -->
                            <label class="switch">
                              <input type="checkbox" ${r.access != 0 && r.access != null
                                    ? "checked"
                                    : ""
                                }
                              onchange="window.editAccessOfficeToggleAcces(event,${r.id
                                })"
                              id="officeAccess${r.id}"
                              >
                              <span class="slider"></span>
                            </label>
                            `;
                        },
                    },
                    {
                        searchable: 0,
                        orderable: 0,
                        defaultContent: 404,
                        title: "PRICE",
                        className: "width-percent-20",
                        render(d, t, r) {
                            return `
                            <div class="input-group mb-1 width-px-80 mx-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1" id="basic-addon1">$</span>
                                </div>
                                <input
                                        type="number" step="any"
                                        class="form-control form-control-sm h-100"
                                        name='price'
                                        ${r.locked ? " input-locked " : " "}
                                        value="${r.locked
                                    ? r.price || r.post_office_price
                                    : localStorage.post_edit_price
                                }"
                                        office="${r.id}"
                                        id="postInstallEditPrice${r.id}";
                                        aria-describedby="basic-addon1">
                            </div>`;
                        },
                    },
                    {
                        searchable: 0,
                        orderable: 0,
                        defaultContent: 404,
                        title: "LOCKED",
                        className: "width-percent-20",
                        render(d, t, r) {
                            return `
                            <div class="input-group mb-1 width-px-80 mx-auto">
                                <input type="checkbox" ${r.locked ? " checked " : " "
                                }
                                onchange="window.toggleLocked(event,'office',${r.id
                                })"
                                class="form-control no-shadow height-px-28" >
                            </div>`;
                        },
                    },
                ],
            });
            table.on("draw.dt", function () {
                Access.inputTableChange();
            });
        }
    },
    editAccessAgentToggleAcces(event, agent) {
        let accessory = Access.isAccessoryAcess();

        let access = event.target.checked;
        let post = localStorage.edit_post;
        $.get(`/update/${accessory ? "accessories" : "posts"}/agent/${post}/${agent}/${access}`);
    },
    editAccessAgentSearch() {
        let input = $("#agentEditSearchInput");
        if (input.length) {
            input.on("keyup", () => {
                window.editAccessAgentDataTable.fnFilter(input.val());
            });
        }
    },
    editAccessAgentDataTable() {
        let table = $("#agentsTable");
        if (table.length) {
            window.editAccessAgentDataTable = table.dataTable({
                pageLength: 5000,
                dom: " ",
                ajax: helper.getSiteUrl("/datatable/post/agents?agent_id=0"),
                serverSide: true,
                columns: [
                    {
                        name: "users.name",
                        data: "name",
                        defaultContent: 404,
                        title: "AGENT",
                        className: "width-percent-40",
                    },
                    {
                        searchable: 0,
                        orderable: 0,
                        defaultContent: 404,
                        title: "ACCESS",
                        className: "width-percent-20",
                        render(d, t, r) {
                            return `
                            <!-- Rectangular switch -->
                            <label class="switch">
                              <input type="checkbox" ${r.access != 0 && r.access != null ? "checked" : ""} onchange="window.editAccessAgentToggleAcces(event,${r.id})" id="AgentAccess${r.id}" agentOfficeId="${r.agent_office}" >
                              <span class="slider"></span>
                            </label>
                            `;
                        },
                    },
                    {
                        searchable: 0,
                        orderable: 0,
                        defaultContent: 404,
                        title: "PRICE",
                        className: "width-percent-20",
                        render(d, t, r) {
                            return `
                            <div class="input-group mb-1 width-px-80 mx-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1" id="basic-addon1">$</span>
                                </div>
                                <input
                                        type="number" step="any"
                                        class="form-control form-control-sm h-100"
                                        name='price'
                                        id="postInstallEditPriceAgent${r.id}";
                                        ${r.locked ? " input-locked " : " "}
                                        value="${r.locked
                                    ? r.price || r.post_agent_price
                                    : localStorage.post_edit_price
                                }"
                                        agent="${r.id}"
                                        aria-describedby="basic-addon1">
                            </div>`;
                        },
                    },
                    {
                        searchable: 0,
                        orderable: 0,
                        defaultContent: 404,
                        title: "LOCKED",
                        className: "width-percent-20",
                        render(d, t, r) {
                            return `
                            <div class="input-group mb-1 width-px-80 mx-auto">
                                <input
                                    type="checkbox"
                                    ${r.locked ? " checked " : " "}
                                    onchange="window.toggleLocked(event,'agent',${r.id
                                })"

                                class="form-control no-shadow height-px-28" >
                            </div>`;
                        },
                    },
                ],
            });
            table.on("draw.dt", function () {
                Access.inputTableChange();
            });
        }
    },
};

export default Access;
