<div class="text-orange-dark d-flex align-items-center gap-px-4">
    <span class="cnumber">5</span>
    <h5 class="pt-2">SIGNPOST AND ACCESSORIES</h5>
</div>
<div class="px-4">
    <div class="row">

        <div class="col-12 col-md-4 col-lg-4 mt-2">
            <label for="repair_order_select_post" class="text-primary text-center d-block text-center">
                <span class="blue-label">Post Repair</span>
            </label>
            <div class="form-check form-check pl-4 py-2" style="background-color: #ced4da;">
                <input type="radio" id="repairOrderPost"  class="form-check-input" checked>
                <label class="form-check-label text-dark" for="repairOrderPost">POst name here</label>
            </div>
            <div class="list-container list-container-posts height-rem-4" id="repairOptionsContainer" >

            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4 mt-2">
            <label for="repair_order_select_sign" class="text-primary d-block text-center"><span
                    class="blue-label">Swap Sign Panel</span></label>
            <div class="list-container-2">
                <!-- <div class="form-check ">
                    <input type="radio" name="repair_order_panel_type" value="-1" class="form-check-input"
                        id="panel_option__1">
                    <label class="form-check-label text-dark" for="panel_option__1">Agent will Hang Own Sign</label>
                </div> -->
                <!-- <div class="form-check ">
                    <input type="radio" name="repair_order_panel_type" value="-2" class="form-check-input"
                        id="panel_option__2">
                    <label class="form-check-label text-dark" for="panel_option__2">Sign Left at Property</label>
                </div> -->
            </div>
            <div class="list-container sign-list list-container-signs position-relative">

            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4 mt-2">
            <label for="repair_order_select_accessories" class="text-primary d-block text-center"><span
                    class="blue-label">Change Accessories</span></label>
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
