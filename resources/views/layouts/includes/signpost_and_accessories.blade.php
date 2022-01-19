<div class="text-orange-dark d-flex align-items-center gap-px-4">
    <span class="cnumber">5</span>
    <h5 class="pt-2">SIGNPOST AND ACCESSORIES</h5>
</div>
<div class="px-4">
    <div class="row">

        <div class="col-12 col-md-4 col-lg-4 mt-2">
            <label for="install_post_select_post" class="text-primary text-center d-block text-center"><span
                    class="blue-label">Post
                    (select one)</span></label>
            <div class="list-container list-container-posts">

            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4 mt-2">
            <label for="install_post_select_sign" class="text-primary d-block text-center"><span
                    class="blue-label">Sign (select one)</span></label>
            <div class="list-container-2">
                <div class="form-check ">
                    <input type="radio" name="install_post_panel_type" value="-1" class="form-check-input"
                        id="panel_option__1">
                    <label class="form-check-label text-dark" for="panel_option__1">Agent will Hang Own Sign</label>
                </div>
                <div class="form-check ">
                    <input type="radio" name="install_post_panel_type" value="-2" class="form-check-input"
                        id="panel_option__2">
                    <label class="form-check-label text-dark" for="panel_option__2">Sign Left at Property</label>
                </div>
            </div>
            <div class="list-container sign-list list-container-signs position-relative">

            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4 mt-2">
            <label for="install_post_select_accessories" class="text-primary d-block text-center"><span
                    class="blue-label">Accessories (optional)</span></label>
            <div class="list-container list-container-accessories">

            </div>
        </div>
        <div class="col-12 install-post-preview-images d-flex justify-content-center align-items-center flex-wrap py-2"
            style="gap:10px">
            <img class="max-width-125px max-height-113px order-preview-imgs d-none" id="post_image_preview"
                src="{{ url('/private/image/post/0') }}">
            <img class="max-width-125px max-height-113px order-preview-imgs d-none" id="sign_image_preview"
                src="{{ url('/private/image/panel/0') }}">
            <img class="max-width-125px max-height-113px accessory_image_preview order-preview-imgs d-none "
                src="{{ url('/private/image/accessory/0') }}">
        </div>
    </div>
</div>
