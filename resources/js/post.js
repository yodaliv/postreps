import helper from "./helper";
import Access from "./access";

const Post = {
    init() {
        this.formSettings();
        this.datatable();
        this.postSearchInput();
        window.editPost = this.editPost;
        window.customSettingClicked = this.customSettingClicked;
        this.destroyAllPosts();
        this.showPostsEntries();
        this.postType();
        this.updateAccess();
        Access.init();
    },
    customSettingClicked(id, post_name) {
        let price_input = $(`[post-price-input-${id}]`);
        localStorage.setItem("post_edit_price", price_input.val());
        $(`[post-id]`).attr("post-id", id).trigger("click");
        $(`[post-name]`).html(post_name);
        // Post.updateAccess();
    },
    updateAccess() {
        // console.log("updateAccess() post.js");
        let access = $(`[post-id]`);
        if (access.length) {
            access.on("click", (event) => {
                event.stopPropagation()

                let link = $(event.target);
                let post = link.attr("post-id");
                let accessory = localStorage.getItem('accessory_access');

                console.log(post);
                localStorage.setItem("edit_post", post);
                if (window.editAccessOfficeDataTable) {
                    let source = helper.getSiteUrl(
                        "/datatable/post/offices?post_id=" + post
                    );
                    if (accessory) {
                        source = helper.getSiteUrl("/datatable/accessory/offices?accessory_id=" + post)

                    }
                    window.editAccessOfficeDataTable.api().ajax.url(source);
                    window.editAccessOfficeDataTable.api().draw();
                }
                if (window.editAccessAgentDataTable) {
                    let source = helper.getSiteUrl("/datatable/post/agents?agent_id=" + post);
                    if (accessory) {
                        source = helper.getSiteUrl("/datatable/accessory/agents?accessory_id=" + post)
                    }
                    window.editAccessAgentDataTable.api().ajax.url(source);
                    window.editAccessAgentDataTable.api().draw();
                }
                let modal = $("#editAccessFormModal");
                if (modal.length) {
                    modal.modal();
                }
            });
        }
    },
    inputTableChange() {
        const table = $("#PostsTable");
        table.find("[updateme]").on("change", (event) => {
            let input = $(event.target);
            let column = input.attr("update");
            let post = input.attr("post_id");
            let value = input.val();
            $.get(`/update/posts/${post}/${column}/${value}`)
                .done((res) => {
                    console.log(res);
                })
                .fail((res) => {
                    console.log(res);
                });
        });
    },
    postType() {
        let select = $("#PostType");
        if (select.length) {
            select.on("change", () => {
                let inactive = select.val();
                let dt = window.postDataTable;
                dt.api().column(0).search(inactive).draw();
            });
        }
    },
    destroyAllPosts() {
        let button = $("#destroyAllPosts");
        if (button.length) {
            button.on("click", () => {
                helper.confirm(
                    "",
                    //on click Yes
                    () => {
                        //delete end point
                        let deletePath = helper.getSiteUrl("/posts/delete/all");
                        let href = $.post(deletePath)
                            .done((res) => {
                                if (res.type === "success") {
                                    //rerender dataTable
                                    window.postDataTable.api().draw();
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
    editPost(id) {
        $.get(helper.getSiteUrl(`/get/post/${id}`))
            .done(async (post) => {
                $("#editPostForm").attr(
                    "action",
                    helper.getSiteUrl(`/posts/${id}`)
                );
                let modal = $("#editPostFormModal");
                if (modal.length) {
                    modal.find(`[post-id]`).attr("post-id", post.id);
                    $(`[post-name]`).html(post.post_name);
                    localStorage.setItem("post_edit_price", post.price);
                    modal.find(`[name='post_name']`).val(post.post_name);
                    modal.find(`[name='quantity']`).val(post.quantity);
                    modal.find(`[name='price']`).val(post.price);
                    modal.find(`[name='renewal_fee']`).val(post.renewal_fee);
                    modal.find(`[name='loss_damage']`).val(post.loss_damage);
                    modal.find(`[name='time_days']`).val(post.time_days);
                    modal.find(`[name='point_value']`).val(post.point_value);
                    modal.find(`[name='listing_order']`).val(post.listing_order);
                    modal
                        .find(`[name='office_id']`)
                        .find(`option[value="${post.office_id}"]`)
                        .prop("selected", true);

                    if (!post.office_id) {
                        modal
                            .find(`[name='office_id']`)
                            .find(`option[value=""]`)
                            .prop("selected", true);
                    }
                    console.log(post);
                    $.get(`/office/${post.office_id}/agents`)
                        .done((office) => {
                            let options = ``;
                            if (office.active_agents) {
                                $(`.post-form [name='agent_id']`).html(
                                    `<option value='0' class='text-muted'>None selected</option>`
                                );       
                                office.active_agents.forEach((agent) => {
                                    options += `<option value='${agent.id}'>${agent.user.name}</option>`;
                                });
                            } else {
                                options = ` <option value="" class="text-muted">None selected</option>`;
                            }
                            modal.find(`[name="agent_id"]`).append(options);
                            modal
                                .find(`[name='agent_id']`)
                                .find(`option[value="${post.agent_id}"]`)
                                .prop("selected", true);
                        })
                        .fail((res) => { });

                    modal
                        .find(`[name="default"]`)
                        .find(`option[value="${post.default}"]`)
                        .prop("selected", true);
                    modal
                        .find(`[name="status"]`)
                        .find(`option[value="${post.status}"]`)
                        .prop("selected", true);

                    modal
                        .find(`#postEditImageContainer img`)
                        .prop(
                            "src",
                            helper.getSiteUrl(
                                `/private/image/post/${post.image_path}`
                            )
                        );
                    Post.setSelectDefaultStyle(
                        `#editPostFormModal select[name='status']`,
                        1,
                        "select-active"
                    );
                    Post.setSelectDefaultStyle(
                        `#editPostFormModal select[name='default']`,
                        0,
                        "bg-dark text-white"
                    );
                    modal.find(`select[name='status']`).on("change", () => {
                        Post.setSelectDefaultStyle(
                            `#editPostFormModal select[name='status']`,
                            1,
                            "select-active"
                        );
                    });
                    modal.find(`select[name='default']`).on("change", () => {
                        Post.setSelectDefaultStyle(
                            `#editPostFormModal select[name='default']`,
                            0,
                            "bg-dark text-white"
                        );
                    });
                    modal.modal();
                }
            })
            .fail((res) => {
                console.log(res);
            });
    },
    showPostsEntries() {
        let select = $("#showPostsEntries");
        if (select.length) {
            select.on("change", (event) => {
                let selected = parseInt(event.target.value);
                window.postDataTable.api().context[0]._iDisplayLength =
                    selected;
                window.postDataTable.api().draw();
            });
        } else {
            console.error(`#showOfficesEntries does not exist`);
        }
    },
    postSearchInput() {
        let input = $("#PostSearchInput");
        if (input.length) {
            input.on("keyup", (event) => {
                let input = event.target;
                window.postDataTable.fnFilter(input.value);
            });
        } else {
            console.error(`#postSearchInput no exists`);
        }
    },
    datatable() {
        let table = $("#PostsTable");

        if (table.length) {
            window.postDataTable = table.dataTable({
                pageLength: 10,
                dom: "rtip",
                ajax: helper.getSiteUrl("/datatable/posts"),
                serverSide: true,
                searchCols: [{ search: 1 }],
                order: [[8, "desc"]],
                columns: [
                    {
                        name: "posts.status",
                        data: "status",
                        defaultContent: 404,
                        visible: 0,
                        title: "SATUTS",
                    },
                    {
                        data: "post_name",
                        defaultContent: 404,
                        title: "POST DETAILS",
                        className: "text-center text-uppercase",
                        render(d, t, r) {
                            return `
                                <p>
                                    <b>${r.post_name}</b>
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
                                <img class="d-block mx-auto width-rem-5" style="max-width: 5rem; max-height: 102px;" src="${helper.getSiteUrl(
                                `/private/image/post/${r.image_path}`
                            )}">
                            `;
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
                            return `${r.office_name || ""} - ${r.agent_name || ""
                                }`;
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
                                        post_id="${r.id}"
                                        update="price"
                                        updateme
                                        post-price-input-${r.id}
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
                                        post_id="${r.id}"
                                        update="loss_damage"
                                        updateme
                                        aria-describedby="basic-addon1">
                            </div>
                            `;
                        },
                    },
                    {
                        name: "time_days",
                        data: "time_days",
                        visible: 0,
                    },
                    {
                        defaultContent: 404,
                        title: "RENEWAL",
                        orderable: 0,
                        searchable: 0,
                        className: "text-center",
                        render(d, t, r) {
                            return `
                            <span>Time/Days</span><br>
                            <div class="input-group mb-1 width-px-80 mx-auto">
                                <input
                                        type="number" step="any"
                                        class="form-control form-control-sm h-100 text-right"
                                        value="${r.time_days}"
                                        post_id="${r.id}"
                                        update="time_days"
                                        updateme
                                        aria-describedby="basic-addon1">
                            </div>
                            <span>Renewal fee</span><br>
                            <div class="input-group mb-1 width-px-80 mx-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1" id="basic-addon1">$</span>
                                </div>
                                <input
                                        type="number" step="any"
                                        class="form-control form-control-sm h-100 text-right"
                                        value="${r.renewal_fee}"
                                        post_id="${r.id}"
                                        update="renewal_fee"
                                        updateme
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
                            <a class='link' onclick="window.editPost(${r.id})">Edit</a><br>
                            <a class='link' onclick="window.customSettingClicked(${r.id},'${r.post_name}')">Custom Settings</a><br>

                            `;
                        },
                    },
                ],
            });
        }
        table.on("draw.dt", function () {
            Post.inputTableChange();
            // $(`[onclick="window.editPost(1)"]`).trigger("click");
        });
    },
    formSettings() {
        helper.loadImageOn("#postImageInput", "#postImageContainer img");
        helper.loadImageOn(
            "#postEditImageInput",
            "#postEditImageContainer img"
        );
        let status = $(`select[name='status']`);
        this.setSelectDefaultStyle(`select[name='status']`, 1, "select-active");
        status.on("change", () => {
            this.setSelectDefaultStyle(
                `select[name='status']`,
                1,
                "select-active"
            );
        });
        let defaults = $(`select[name='default']`);
        this.setSelectDefaultStyle(
            `select[name='default']`,
            0,
            "bg-dark text-white"
        );
        defaults.on("change", () => {
            this.setSelectDefaultStyle(
                `select[name='default']`,
                0,
                "bg-dark text-white"
            );
        });

        //load agenst
        $(`.post-form [name="office_id"]`).on("change", (event) => {
            let office = event.target.value;
            if (office == 0) {
                $(`.post-form [name='agent_id']`).html(
                    `<option value='0' class='text-muted'>None selected</option>`
                );
                return;
            }
            $.get(`/office/${office}/agents/order/by/name/json`)
                .done((office) => {
                    let options = ``;
                    $(`.post-form [name='agent_id']`).html(
                        `<option value='0' class='text-muted'>None selected</option>`
                    );                    
                    office.forEach((agent) => {
                        options += `<option value='${agent.id}'>${agent.user.lastNameFirstName}</option>`;
                    });
                    $(`.post-form [name="agent_id"]`).append(options);
                })
                .fail((res) => {
                    console.log(res);
                });
        });
    },
    setSelectDefaultStyle(target, value, clas) {
        let e = $(target);
        if (e.val() == value) {
            e.addClass(clas);
        } else {
            e.removeClass(clas);
        }
    },
};

export default Post;
