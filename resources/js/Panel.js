import helper from "./helper";
import post from "./post";

const Panel = {
    init() {
        console.log("panel.js");
        this.formSettings();
        window.editPanel = this.editPanel;
        this.datatable();
        this.panelType();
        this.destroyAllPanels();
        this.showPanelsEntries();
        this.panelsSearchInput();
        window.deleteAgentSelect = this.deleteAgentSelect;
        this.createAgent();
        this.editAgent();

        $("#editPanelFormModal").on("hide.bs.modal	", () => {
            let o = $(`#createPanelFormModal select[name="agent_id[]"]`);
            let o2 = $(`#createPanelFormModal select[name="office_id"]`);
            if (!o2.val()) {
                o.find("option").not(":first").remove();
            }
        });
        $(() => {
            if (window.innerWidth <= 425) {
                let btn = $(`#panelSettingButton`);
                if (btn.length) {
                    btn.html(`<i class="fas fa-sliders-h"></i>`);
                    btn.css("width", 'auto')
                    btn.css("top", '-40px')
                }
            }
        })
        window.onresize = function () {
            let btn = $(`#panelSettingButton`);
            let old = btn.html();
            if (window.innerWidth <= 425) {
                if (btn.length) {
                    btn.html(`<i class="fas fa-sliders-h"></i>`);
                    btn.css("width", 'auto')
                    btn.css("top", '-40px')
                }
            } else {
                btn.html('Panel Global Settings');
                btn.css("top", '5px')
                btn.css("width", '174px')

            }
        };
    },
    deleteAgentSelect(id) {
        document.getElementById(id).remove();
    },
    panelType() {
        let select = $("#PanelType");
        if (select.length) {
            select.on("change", () => {
                let inactive = select.val();
                let dt = window.panelsDatatable;
                dt.api().column(0).search(inactive).draw();
            });
        }
    },
    createAgent() {
        $(".create-add-agent").on("click", () => {
            let id = helper.create_id();
            let agent_dropdown = $(`.create-add-agent-form-group`)
                .last()
                .clone()
                .appendTo(".agents-dropdowns-list")
                .attr("id", id)
                .find("label").html(`
                    <i class="fas fa-trash text-danger c-p"  onclick="window.deleteAgentSelect('${id}')"></i>
                `);
        });
    },
    editAgent() {
        $(".edit-add-agent").on("click", () => {
            let id = helper.create_id();
            let agent_dropdown = $(`.edit-add-agent-form-group`)
                .last()
                .clone()
                .appendTo(".agents-dropdowns-list-edit")
                .attr("id", id)
                .find("label").html(`
                    <i class="fas fa-trash text-danger c-p" onclick="window.deleteAgentSelect('${id}')"></i>
                `);
        });
    },
    panelsSearchInput() {
        let input = $("#panelsSearchInput");
        if (input.length) {
            input.on("keyup", (event) => {
                let input = event.target;
                window.panelsDatatable.fnFilter(input.value);
            });
        } else {
            console.error(`#panelsSearchInput no exists`);
        }
    },
    showPanelsEntries() {
        let select = $("#showPanelsEntries");
        if (select.length) {
            select.on("change", (event) => {
                let selected = parseInt(event.target.value);
                window.panelsDatatable.api().context[0]._iDisplayLength =
                    selected;
                window.panelsDatatable.api().draw();
            });
        } else {
            console.error(`#showPanelsEntries does not exist`);
        }
    },
    destroyAllPanels() {
        let button = $("#destroyAllPanels");
        if (button.length) {
            button.on("click", () => {
                helper.confirm(
                    "",
                    //on click Yes
                    () => {
                        //delete end point
                        let deletePath =
                            helper.getSiteUrl("/panels/delete/all");
                        let href = $.post(deletePath)
                            .done((res) => {
                                if (res.type === "success") {
                                    //rerender dataTable
                                    window.panelsDatatable.api().draw();
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
    editPanel(id) {
        const form = $("#editPanelForm");
        const modal = $("#editPanelFormModal");
        if (modal.length) {
            $.get(helper.getSiteUrl(`/get/panel/${id}`)).done((panel) => {
                helper.loadImageOn(
                    "#panelEditImageInput",
                    "#panelEditImageContainer img"
                );
                form.attr("action", helper.getSiteUrl(`/panels/${panel.id}`));
                form.find(`[name='panel_name']`).val(panel.panel_name);
                form.find(`[name='quantity']`).val(panel.quantity);
                form.find(`[name='price']`).val(panel.price);
                form.find(`[name='free_storage']`).val(panel.free_storage);
                form.find(`[name='cost_per_unit']`).val(panel.cost_per_unit);
                form.find(`[name='frequency']`).val(panel.frequency);
                form.find(`[name='listing_order']`).val(panel.listing_order);
                form.find(`[name='width']`).val(
                    panel.width == 0 ? "" : panel.width
                );
                form.find(`[name='height']`).val(
                    panel.height == 0 ? "" : panel.height
                );
                form.find(`[name='office_id']`)
                    .find(`option[value="${panel.office_id}"]`)
                    .prop("selected", true);
                form.find(`[name='office_id']`)
                    .get(0)
                    .dispatchEvent(new Event("change"));
                //creat DOM
                //reset
                $(`.edit-add-agent-form-group`).not(":first").remove();
                $.get(helper.getSiteUrl(`/panel/${panel.id}/all/agents`)).done(
                    (agents) => {
                        if (!agents.length) return;
                        for (
                            let index = 0;
                            index < Math.abs(agents.length - 1);
                            index++
                        ) {
                            let id = helper.create_id();
                            $(`.edit-add-agent-form-group`)
                                .last()
                                .clone()
                                .appendTo(".agents-dropdowns-list-edit")
                                .attr("id", id)
                                .find("label").html(`<i class="fas fa-trash text-danger c-p" onclick="window.deleteAgentSelect('${id}')"></i>`);
                        }

                        // add agents to select
                        $.get(helper.getSiteUrl(`/office/${panel.office_id}/agents/order/by/name/json`)).done((office) => {
                            let options = `<option value='0' class='text-muted'>None selected</option>`;
                            office.forEach((agent) => {
                                options += `<option value='${agent.id}'>${agent.user.lastNameFirstName}</option>`;
                            });
                            form.find(`[name="agent_id[]"]`).html(options);
                            form[0]
                                .querySelectorAll(`[name="agent_id[]"]`)
                                .forEach((e, i) => {
                                    let agent = agents[i];
                                    e = $(e);
                                    e.find(`option[value="${agent.agent_id}"]`).prop("selected", true);
                                });
                        });
                    }
                );
                form.find(`[name="status"]`)
                    .find(`option[value="${panel.status}"]`)
                    .prop("selected", true);

                modal
                    .find(`#panelEditImageContainer img`)
                    .prop("src", helper.getSiteUrl(`/private/image/panel/${panel.image_path}`));

                modal.modal();
            });
        }
    },
    formSettings() {
        helper.loadImageOn("#panelImageInput", "#panelImageContainer img");
        helper.loadImageOn("#panelEditImageInput", "#panelEditImageContainer img");
        let status = $(`.panel-form select[name='status']`);
        post.setSelectDefaultStyle(`.panel-form select[name='status']`, 1, "select-active");
        status.on("change", () => {
            console.log("status");
            post.setSelectDefaultStyle(`.panel-form select[name='status']`, 1, "select-active");
        });

        //load agenst
        $(`.panel-form [name="office_id"]`).on("change", (event) => {
            let office = event.target.value;
            if (office == 0) {
                $(`.panel-form [name='agent_id[]']`).html(`<option value='0' class='text-muted'>None selected</option>`);
                return;
            }
            $.get(helper.getSiteUrl(`/office/${office}/agents/order/by/name/json`))
                .done((office) => {
                    let options = `<option value='0' class='text-muted'>None selected</option>`;
                    office.forEach((agent) => {
                        options += `<option value='${agent.id}'>${agent.user.lastNameFirstName}</option>`;
                    });
                    $(`.panel-form [name="agent_id[]"]`).html(options);
                })
                .fail((res) => {
                    console.log(res);
                });
        });
    },
    datatable() {
        let table = $("#PanelsTable");
        if (table.length) {
            window.panelsDatatable = table.dataTable({
                pageLength: 10,
                dom: "rtip",
                serverSide: true,
                ajax: helper.getSiteUrl(`/datatable/panels`),
                searchCols: [{ search: 1 }],
                order: [[10, "desc"]],
                columns: [
                    {
                        name: "status",
                        data: "status",
                        visible: 0,
                    },
                    {
                        name: "panels.panel_name",
                        data: "panel_name",
                        visible: 0,
                    },
                    {
                        name: "panels.item_id_code",
                        data: "item_id_code",
                        visible: 0,
                    },
                    {
                        orderable: 0,
                        searchable: 0,
                        title: "PANEL DETAILS",
                        className: "text-center",
                        render(d, t, r) {
                            let type = " ";
                            let size = `${r.width}×${r.height}`;

                            if (
                                r.width > 0 &&
                                r.height > 0 &&
                                r.width <= 24 &&
                                r.height <= 24
                            ) {
                                type = "STANDARD";
                            }
                            if (
                                r.width > 0 &&
                                r.height > 0 &&
                                ((r.width > 24 && r.width <= 36) ||
                                    (r.height > 24 && r.height <= 36))
                            ) {
                                type = "LARGE";
                            }

                            if (
                                r.width > 0 &&
                                r.height > 0 &&
                                (r.width > 36 || r.height > 36)
                            ) {
                                type = "COMMERCIAL";
                            }

                            if (r.width == 0 || r.height == 0) {
                                size = "";
                            }

                            return `
                                <span><b>${r.panel_name}</b></span><br>
                                <span><b>${size}</b></span><br>
                                <span><b>${type}</b></span><br>
                                <span><b>${r.item_id_code}</b></span><br>
                            `;
                        },
                    },
                    {
                        orderable: 0,
                        searchable: 0,
                        title: "IMAGE",
                        render(d, t, r) {
                            return `
                                <img class="d-block mx-auto width-rem-5" style="max-height: 102px; max-width: 5rem;" src="${helper.getSiteUrl(
                                `/private/image/post/${r.image_path}`
                            )}">`;
                        },
                    },
                    {
                        name: "office_user.name",
                        data: "office_name",
                        visible: 0,
                    },
                    {
                        name: "agent_user.name",
                        data: "agent_name",
                        visible: 0,
                    },
                    {
                        defaultContent: 404,
                        title: "OFFICE/AGENT",
                        orderable: 0,
                        searchable: 0,
                        className: "text-center",
                        render(d, t, r) {
                            // let agents =  await $.get(helper.getSiteUrl(`/panel/${r.id}/agents`));
                            // let agents_oneline = await agents.join(' - ');
                            return `<div panel-id="${r.id}">
                            ${r.office_name || ""} - ${r.agent_name || ""}
                            </div>`;
                        },
                    },
                    {
                        name: "quantity",
                        data: "quantity",
                        visible: 0,
                    },
                    {
                        defaultContent: 404,
                        title: "QUANTITY",
                        orderable: 0,
                        searchable: 0,
                        className: "text-center width-px-135",
                        render(d, t, r) {
                            return `
                            <span>In Storage: ${r.quantity || "0"}</span><br>
                            <span>In Field: ${r.quantity || "0"}</span><br>
                            <a href="#" class="link font-weight-bold">History</a><br>
                            `;
                        },
                    },
                    {
                        name: "created_at",
                        data: "created_at",
                        visible: 0,
                    },
                    {
                        defaultContent: 404,
                        title: "LAST USED",
                        orderable: 0,
                        searchable: 0,
                        className: "text-center width-px-120",
                        render(d, t, r) {
                            let date = r.created_at ?? "";
                            date = date.split("T")[0];
                            return `
                            <span>Last Used: <br>${helper.formatDate(
                                date
                            )}</span><br>
                            <span>Created: <br>${helper.formatDate(
                                date
                            )}</span><br>
                            `;
                        },
                    },
                    {
                        defaultContent: 404,
                        title: "ACTIONS",
                        orderable: 0,
                        searchable: 0,
                        className: "text-center width-px-150",
                        render(d, t, r) {
                            return `
                                <a class='link' onclick="window.editPanel(${r.id})">Edit</a><br>
                                `;
                        },
                    },
                ],
            });

            table.on("draw.dt", (event) => {
                let eles = document.querySelectorAll(`[panel-id]`);

                eles.forEach((e) => {
                    e = $(e);
                    let panel = e.attr("panel-id");
                    $.get(helper.getSiteUrl(`/panel/${panel}/agents`)).then(
                        (agents) => {
                            e.append(agents.join(" - "));
                        }
                    );
                });
            });
        }
    },
};

export default Panel;
