import helper from "./helper";
import Post from "./post";

const Accessory = {
    init() {
        console.log("accessory js");
        window.editAccessory = this.editAccessory;
        window.customAccessAccessorySettingClicked =
            this.customAccessAccessorySettingClicked;
        window.accessory_offices_change = this.accessory_offices_change;
        window.eaccessory_offices_change = this.eaccessory_offices_change;
        this.formSettings();
        this.datatable();
        this.accessoryType();
        this.destroyAllPosts();
        this.accessoriesSearchInput();
        this.showAccessoriesEntries();
        this.inventoryList();
        this.editInventoryList();
        this.AcessoryInventoryDone();
        this.searchOntable();
    },
    searchOntable() {
        let search1 = $("#search-createAccessoryAgentOfficeTable");
        if (search1.length) {
            search1.on("keyup", (e) => {
                let table1Rows = $("#createAccessoryAgentOfficeTable tbody tr");
                let val = $.trim(e.target.value)
                    .replace(/ +/g, "")
                    .toLowerCase();
                table1Rows.filter(function () {
                    let text = $(this).find(`[name="accessory_inventory_item_id[]"]`).val().replace(/\s+/g, " ").toLowerCase();
                    let select1 = $(this).find(`[name="accessory_invetory_offices[]"]`)
                    let select2 = $(this).find(`[name="accessory_inventory_agents[]"]`)
                    let text1 = select1.find(`option[value="${select1.val()}"]`).text().replace(/\s+/g, ' ').toLowerCase();
                    let text2 = select2.find(`option[value="${select2.val()}"]`).text().replace(/\s+/g, ' ').toLowerCase();

                    let res = false;
                    if ((text.indexOf(val) == -1) & (text1.indexOf(val) == -1) & (text2.indexOf(val) == -1)) {
                        $(this).hide()
                    } else {
                        $(this).show()

                    }
                    return res
                })


            });
        }

        let search2 = $("#search-editAccessoryAgentOfficeTable");
        if (search2.length) {
            search2.on("keyup", (e) => {
                let table2Rows = $("#editAccessoryAgentOfficeTable tbody tr");
                let val = $.trim(e.target.value).replace(/ +/g, " ").toLowerCase();
                table2Rows
                    .filter(function () {
                        let text = $(this).find(`[name="eaccessory_inventory_item_id[]"]`).val().replace(/\s+/g, " ").toLowerCase();
                        let select1 = $(this).find(`[name="eaccessory_invetory_offices[]"]`)
                        let select2 = $(this).find(`[name="eaccessory_inventory_agents[]"]`)
                        let text1 = select1.find(`option[value="${select1.val()}"]`).text().replace(/\s+/g, ' ').toLowerCase();
                        let text2 = select2.find(`option[value="${select2.val()}"]`).text().replace(/\s+/g, ' ').toLowerCase();

                        let res = false;
                        if ((text.indexOf(val) == -1) & (text1.indexOf(val) == -1) & (text2.indexOf(val) == -1)) {
                            $(this).hide()
                        } else {
                            $(this).show()

                        }
                        return res
                    })
            });
        }
    },
    AcessoryInventoryDone() {
        $("[accessory-inventories-done]").on("click", () => {
            let table = $("#createAccessoryAgentOfficeTable");
            let errors = [];
            table.find("tbody tr").each((index, row) => {
                row = $(row);

                let item_id = row.find(
                    '[name="accessory_inventory_item_id[]"]'
                );

                let office_id = row.find(
                    '[name="accessory_invetory_offices[]"]'
                );
                if (`${item_id.val()}`.trim()) {
                    if (`${office_id.val()}`.trim() == "") {
                        errors.push(row);
                    }
                    if (`${item_id.val()}`.trim() == "") {
                        item_id.addClass("is-invalid");
                    } else {
                        item_id.removeClass("is-invalid");

                        item_id.addClass("is-valid");
                    }
                    if (`${office_id.val()}`.trim() == "") {
                        office_id.addClass("is-invalid");
                    } else {
                        office_id.removeClass("is-invalid");

                        office_id.addClass("is-valid");
                    }
                }
            });

            if (!errors.length) {
                $("#createAccessoryAgentOffice").modal("hide");
            } else {
                helper.alertError("Please select office");
                window.select_office_dropdown_error = errors[0].find(
                    '[name="accessory_invetory_offices[]"]'
                );
            }
        });
        $("[accessory-inventories-done-edit]").on("click", () => {
            let table = $("#editAccessoryAgentOfficeTable");
            let errors = [];
            table.find("tbody tr").each((index, row) => {
                row = $(row);

                let item_id = row.find(
                    '[name="eaccessory_inventory_item_id[]"]'
                );

                let office_id = row.find(
                    '[name="eaccessory_invetory_offices[]"]'
                );

                if (`${item_id.val()}`.trim()) {
                    if (`${office_id.val()}`.trim() == "") {
                        errors.push(row);
                    }
                    if (`${item_id.val()}`.trim() == "") {
                        item_id.addClass("is-invalid");
                    } else {
                        item_id.removeClass("is-invalid");

                        item_id.addClass("is-valid");
                    }
                    if (`${office_id.val()}`.trim() == "") {
                        office_id.addClass("is-invalid");
                    } else {
                        office_id.removeClass("is-invalid");

                        office_id.addClass("is-valid");
                    }
                }
            });

            if (!errors.length) {
                $("#editAccessoryAgentOffice").modal("hide");
            } else {
                helper.alertError("Please select office");
                window.select_office_dropdown_error = errors[0].find(
                    '[name="eaccessory_invetory_offices[]"]'
                );
            }
        });
        $("#errorModal").on("hidden.bs.modal", () => {
            window.select_office_dropdown_error.focus();
        });
    },
    accessory_offices_change(event) {
        let selectInput = event.target;
        let id = event.target.value;
        let agents = $.get(`office/${id}/agents/json`).done((agents) => {
            let agentsOptions = "<option value=''>-</option>";
            agents.forEach((agent) => {
                agentsOptions += `<option value="${agent.id}">${agent.user.name}</option>`;
            });
            $(selectInput.parentNode.parentNode)
                .find(`[name="accessory_inventory_agents[]"]`)
                .html(agentsOptions);
        });
    },
    eaccessory_offices_change(event) {
        let selectInput = event.target;
        let id = event.target.value;
        let agent_id = $(selectInput).attr("accessory_agent_id");

        let agents = $.get(`office/${id}/agents/json`).done((agents) => {
            let agentsOptions = "<option value=''>-</option>";
            agents.forEach((agent) => {
                agentsOptions += `<option value="${agent.id}" ${agent.id == agent_id ? "selected" : ""
                    }>${agent.user.name}</option>`;
            });
            $(selectInput.parentNode.parentNode)
                .find(`[name="eaccessory_inventory_agents[]"]`)
                .html(agentsOptions);
        });
    },
    renderInventoryList(accessory) {
        let table = $("#editAccessoryAgentOfficeTable");
        let offices = JSON.parse(table.attr("offices"));
        let qty = $("#editAccessoryForm").find(`[name="quantity"]`).val();
        let inventories = accessory.inventories;
        Accessory.edit_renderInventoryList(qty, table, offices);
        if (table.length) {
            table
                .get(0)
                .querySelectorAll("tbody tr")
                .forEach((row, index) => {
                    console.log(row);
                    row.querySelector(
                        `[name="eaccessory_inventory_item_id[]"]`
                    ).value = inventories[index]?.item_id ?? "";
                    let officeSelect = row.querySelector(
                        `[name="eaccessory_invetory_offices[]"]`
                    );
                    officeSelect.setAttribute(
                        "accessory_agent_id",
                        inventories[index]?.agent_id
                    );
                    if (officeSelect) {
                        let office = officeSelect.querySelector(
                            `option[value="${inventories[index]?.office_id}"]`
                        );
                        if (office) {
                            office.selected = true;
                            officeSelect.dispatchEvent(new Event("change"));
                        }
                    }
                    let agentSelect = row.querySelector(
                        `[name="eaccessory_inventory_agents[]"]`
                    );
                    if (agentSelect) {
                        agentSelect.setAttribute(
                            "accessory_agent_id",
                            inventories[index]?.agent_id
                        );
                    }
                });
        }
        console.log(inventories);
    },
    edit_renderInventoryList(qty, table, offices) {
        // table.find(`tbody`).html(``);
        if (table.find(`tbody`).find(`tr`).length > qty) {
            qty = Math.abs(table.find(`tbody`).find(`tr`).length - qty);
            for (let index = 0; index < qty; index++) {
                table.find(`tbody`).find(`tr`).last().remove();
            }
            return;
        } else {
            qty = Math.abs(table.find(`tbody`).find(`tr`).length - qty);
        }
        if (table.length) {
            let officesOptions = "";
            if (!Array.isArray(offices)) offices = Object.values(offices)
            offices.forEach((office) => {
                officesOptions += `<option value="${office.id}">${office.user.name}</option>`;
            });

            for (let index = 0; index < qty; index++) {
                table.find(`tbody`).append(`

            <tr>
                <td>
                    <input type="text" class="form-control form-control-sm text-center" name="eaccessory_inventory_item_id[]" value="" />
                </td>
                <td>
                    <select name="eaccessory_invetory_offices[]" onchange="window.eaccessory_offices_change(event)" class="form-control form-control-sm text-center">
                    <option value=''>-</option>
                    ${officesOptions}
                    </select>
                </td>
                <td>
                    <select name="eaccessory_inventory_agents[]" class="form-control form-control-sm text-center">
                        <option value=''>-</option>
                    </select>
                </td>
            </tr>

            `);
            }
        }
    },
    inventoryList() {
        let table = $("#createAccessoryAgentOfficeTable");
        //console.log(table.attr("offices"))
        let offices = JSON.parse(table.attr("offices"));
        let qty = $("#createAccessoryForm").find(`[name="quantity"]`);
        let qtyV = qty.val() || 0;
        qty.on("keyup", (event) => {
            let _qty = event.target.value || 0;
            if (_qty !== 0) {
                if (qtyV > _qty) {
                    helper.confirm(
                        `By reducing quantity you will lose data. Proceed?`,
                        () => {
                            Accessory.appendRows(
                                table,
                                offices,
                                event.target.value
                            );
                        },
                        () => {
                            qty.val(qtyV);
                            return;
                        }
                    );
                } else {
                    qtyV = _qty;
                    Accessory.appendRows(table, offices, event.target.value);
                }
            }
        });
    },
    appendRows(table, offices, qty) {
        if (table.find(`tbody`).find(`tr`).length >= qty) {
            qty = Math.abs(table.find(`tbody`).find(`tr`).length - qty);
            for (let index = 0; index < qty; index++) {
                table.find(`tbody`).find(`tr`).last().remove();
            }
            return;
        } else {
            qty = Math.abs(table.find(`tbody`).find(`tr`).length - qty);
        }
        if (table.length) {
            let officesOptions = "";
            offices.forEach((office) => {
                officesOptions += `<option value="${office.id}">${office.user.name}</option>`;
            });

            for (let index = 0; index < qty; index++) {
                table.find(`tbody`).append(`

            <tr>
                <td>
                    <input type="text" class="form-control form-control-sm text-center" name="accessory_inventory_item_id[]" value="" />
                </td>
                <td>
                    <select name="accessory_invetory_offices[]" onchange="window.accessory_offices_change(event)" class="form-control form-control-sm text-center">
                    <option value=''>-</option>
                    ${officesOptions}
                    </select>
                </td>
                <td>
                    <select name="accessory_inventory_agents[]" class="form-control form-control-sm text-center">
                        <option value=''>-</option>
                    </select>
                </td>
            </tr>

            `);
            }
        }
    },
    editInventoryList() {
        let table = $("#editAccessoryAgentOfficeTable");
        let offices = JSON.parse(table.attr("offices"));
        let qty = $("#editAccessoryForm").find(`[name="quantity"]`);
        let qtyV = qty.val() || 0;
        $("#editAccessoryFormModal").on(
            "show.bs.modal",
            () => (qtyV = qty.val())
        );
        qty.on("change", (event) => {
            let _qty = event.target.value || 0;
            if (_qty !== 0) {
                if (qtyV > _qty) {
                    helper.confirm(
                        `By reducing quantity you will lose data. Proceed?`,
                        () => {
                            Accessory.edit_renderInventoryList(
                                event.target.value,
                                table,
                                offices
                            );
                        },
                        () => {
                            qty.val(qtyV);
                            return;
                        }
                    );
                } else {
                    qtyV = _qty;
                    Accessory.edit_renderInventoryList(
                        event.target.value,
                        table,
                        offices
                    );
                }
            }
        });
    },
    customAccessAccessorySettingClicked(id, accessory_name) {
        let price_input = $(`[accessory-price-input-${id}]`);
        localStorage.setItem("post_edit_price", price_input.val());
        localStorage.setItem("accessory_access", id);
        $(`[post-id]`).attr("post-id", id).trigger("click");
        $(`[post-name]`).html(accessory_name);
    },
    inputTableChange() {
        window.accessoriesDataTable.find("[updateme]").on("change", (event) => {
            let input = $(event.target);
            let column = input.attr("update");
            let accessory = input.attr("accessory_id");
            let value = input.val();
            $.get(`/update/accessory/${accessory}/${column}/${value}`)
                .done((res) => {
                    console.log(res);
                })
                .fail((res) => {
                    console.log(res);
                });
        });
    },
    formSettings() {
        helper.loadImageOn(
            "#accessoryImageInput",
            "#accessoryImageContainer img"
        );
        helper.loadImageOn(
            "#accessoryImageInputEdit",
            "#accessoryImageContainerEdit img"
        );

        let status = $(`select[name='status']`);
        Post.setSelectDefaultStyle(
            `#createAccessoryFormModal select[name='status']`,
            1,
            "select-active"
        );
        status.on("change", () => {
            Post.setSelectDefaultStyle(
                `#createAccessoryFormModal select[name='status']`,
                1,
                "select-active"
            );
        });
        let defaults = $(`#createAccessoryFormModal select[name='default']`);
        Post.setSelectDefaultStyle(
            `#createAccessoryFormModal select[name='default']`,
            0,
            "bg-dark text-white"
        );
        defaults.on("change", () => {
            Post.setSelectDefaultStyle(
                `#createAccessoryFormModal select[name='default']`,
                0,
                "bg-dark text-white"
            );
        });
    },
    accessoryType() {
        let select = $("#AccessoryType");
        if (select.length) {
            select.on("change", () => {
                let inactive = select.val();
                let dt = window.accessoriesDataTable;
                dt.api().column(0).search(inactive).draw();
            });
        }
    },
    showAccessoriesEntries() {
        let select = $("#showAccessoriesEntries");
        if (select.length) {
            select.on("change", (event) => {
                let selected = parseInt(event.target.value);
                window.accessoriesDataTable.api().context[0]._iDisplayLength =
                    selected;
                window.accessoriesDataTable.api().draw();
            });
        } else {
            console.error(`#showOfficesEntries does not exist`);
        }
    },
    accessoriesSearchInput() {
        let input = $("#accessoriesSearchInput");
        if (input.length) {
            input.on("keyup", (event) => {
                let input = event.target;
                window.accessoriesDataTable.fnFilter(input.value);
            });
        } else {
            console.error(`#postSearchInput no exists`);
        }
    },
    destroyAllPosts() {
        let button = $("#destroyAllAccessories");
        if (button.length) {
            button.on("click", () => {
                helper.confirm(
                    "",
                    //on click Yes
                    () => {
                        //delete end point
                        let deletePath = helper.getSiteUrl(
                            "/accessories/delete/all"
                        );
                        let href = $.post(deletePath)
                            .done((res) => {
                                if (res.type === "success") {
                                    //rerender dataTable
                                    window.accessoriesDataTable.api().draw();
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
    editAccessory(id) {
        $.get(helper.getSiteUrl(`/get/accessory/${id}`))
            .done(async (accessory) => {

                let modal = $("#editAccessoryFormModal");
                if (modal.length) {
                    modal.find(`[accessory-id]`).attr("accessory-id", accessory.id);
                    $(`[accessory-name]`).attr("accessory-name", accessory.accessory_name);
                    $(`[accessory-name-inventory]`).html(accessory.accessory_name);
                    localStorage.setItem("accessory_edit_price", accessory.price);
                    $("#editAccessoryForm").attr("action", helper.getSiteUrl(`/accessories/${id}`));
                    $(`[accessory-id]`).on("click", (event) => {
                        let e = $(event.target);
                        this.customAccessAccessorySettingClicked(
                            e.attr("accessory-id"),
                            e.attr("accessory-name")
                        );
                    });
                    modal.find(`[name='accessory_name']`).val(accessory.accessory_name);
                    modal.find(`[name='quantity']`).val(accessory.quantity);
                    Accessory.renderInventoryList(accessory);
                    modal.find(`[name='price']`).val(accessory.price);
                    modal.find(`[name='loss_damage']`).val(accessory.loss_damage);
                    modal.find(`[name='point_value']`).val(accessory.point_value);
                    modal.find(`[name='listing_order']`).val(accessory.listing_order);
                    modal.find(`[name="default"]`).find(`option[value="${accessory.default}"]`).prop("selected", true);
                    modal.find(`[name="status"]`).find(`option[value="${accessory.status}"]`).prop("selected", true);
                    modal.find(`#accessoryImageContainerEdit img`).prop("src", helper.getSiteUrl(`/private/image/accessory/${accessory.image}`));
                    modal.find(`[name="prompt"]`).prop("checked", accessory.prompt);
                    modal.find(`[name="pull_list"]`).prop("checked", accessory.pull_list);
                    modal.find(`[name="inventory"]`).prop("checked", accessory.inventory);
                    modal.find(`[name="message"]`).prop("checked", accessory.message);
                    modal.find(`[name="document_upload_prompt_heading"]`).val(accessory.document_upload_prompt_heading);
                    modal.find(`[name="document_upload_prompt_message"]`).val(accessory.document_upload_prompt_message);
                    modal.find(`[name="popup_message_heading"]`).val(accessory.popup_message_heading);
                    modal.find(`[name="popup_message_message"]`).val(accessory.popup_message_message);
                    Post.setSelectDefaultStyle(`#editAccessoryFormModal select[name='status']`, 1, "select-active");
                    Post.setSelectDefaultStyle(`#editAccessoryFormModal select[name='default']`, 0, "bg-dark text-white");
                    modal.find(`select[name='status']`).on("change", () => { Post.setSelectDefaultStyle(`#editAccessoryFormModal select[name='status']`, 1, "select-active"); });
                    modal.find(`select[name='default']`).on("change", () => {
                        Post.setSelectDefaultStyle(`#editAccessoryFormModal select[name='default']`, 0, "bg-dark text-white");
                    });
                    modal.modal();

                }
            })
            .fail((res) => {
                console.log(res);
            });
    },
    datatable() {
        let table = $("#AccessoriesTable");

        if (table.length) {
            window.accessoriesDataTable = table.dataTable({
                pageLength: 10,
                dom: "rtip",
                ajax: helper.getSiteUrl("/datatable/accessories"),
                serverSide: true,
                searchCols: [{ search: 1 }],
                order: [[5, "desc"]],
                columns: [
                    {
                        name: "status",
                        data: "status",
                        defaultContent: 404,
                        visible: 0,
                        title: "SATUTS",
                    },
                    {
                        data: "accessory_name",
                        defaultContent: 404,
                        title: "ACCESSORIES DETAILS",
                        className: "text-center text-uppercase",
                        render(d, t, r) {
                            // console.log("Accessory id: " + r.id);
                            return `
                                <p>
                                    <b>${r.accessory_name}</b>
                                </p>
                                <p class='text-center'>
                                <b>${r.item_id_code}</b>
                                </p>
                            `;
                        },
                    },
                    {
                        defaultContent: 404,
                        title: "IMAGE",
                        searchable: 0,
                        orderable: 0,
                        className: "text-center",
                        render(d, t, r) {
                            return `
                                <img class="d-block mx-auto" style="max-width: 5rem; max-height: 102px;" src="${helper.getSiteUrl(
                                `/private/image/accessory/${r.image}`
                            )}">
                            `;
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
                        name: "price",
                        data: "price",
                        visible: 0,
                    },
                    {
                        defaultContent: 404,
                        title: "PRICE",
                        orderable: 0,
                        searchable: 0,
                        className: "text-center  ",
                        render(d, t, r) {
                            return `
                            <span>Install</span><br>
                            <div class="input-group mb-1 width-px-80 mx-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1" id="basic-addon1">$</span>
                                </div>
                                <input
                                        type="number" step="any"
                                        class="form-control form-control-sm h-100 text-right"
                                        value="${r.price}"
                                        updateme
                                        accessory_id="${r.id}"
                                        update="price"
                                        accessory-price-input-${r.id}
                                        aria-describedby="basic-addon1">
                            </div>
                            <span>Loss/Damage</span><br>
                            <div class="input-group mb-1 width-px-80 mx-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1" id="basic-addon1">$</span>
                                </div>
                                <input
                                        type="number" step="any"
                                        class="form-control form-control-sm h-100 text-right"
                                        value="${r.loss_damage}"
                                        updateme
                                        accessory_id="${r.id}"
                                        update="loss_damage"
                                        aria-describedby="basic-addon1">
                            </div>
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
                            <a class='link' onclick="window.editAccessory(${r.id})" >Edit</a><br>
                            <a class='link' onclick="window.customAccessAccessorySettingClicked(${r.id},'${r.accessory_name}')" >Custom Settings</a><br>

                            `;
                        },
                    },
                ],
            });
        }
        table.on("draw.dt", function () {
            Accessory.inputTableChange();
        });
    },
};

export default Accessory;
