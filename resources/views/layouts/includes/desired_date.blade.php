<div class="text-orange-dark d-flex align-items-center gap-px-4">
    <span class="cnumber">4</span>
    <h5 class="pt-2">DESIRED DATE</h5>
</div>
<div class="px-4">
    <div class="d-flex justify-content-start w-100">
        <div>
            <div class="row width-rem-19">
                <div class="col-12 mt-2">
                    <div class="d-flex justify-content-start align-items-center px-2">
                        <input type="radio" value="asap" class="form-control w-h-px-25 mr-2"
                            name="install_post_desired_date" id="desired_date">
                        <label for="desired_date" class="text-dark m-0"><b>Rush Order Request (<span
                                    rush-order-fee="{{ $service_settings->rush_order }}">${{ $service_settings->rush_order }}</span>)</b></label>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="d-flex justify-content-start align-items-center px-2">
                        <input type="radio" checked value="custom_date" class="form-control w-h-px-25 mr-2"
                            name="install_post_desired_date" id="custom_date">


                        <label for="custom_date" class="text-dark m-0"><b>Specify a Desired Date</b></label>
                    </div>
                    <input type="text" readonly name="install_post_custom_desired_date"
                        class="form-control form-control-sm mt-4 width-px-120 d-none" id="datepicker"></p>

                </div>
            </div>
        </div>
        <div>
            <div id="selectdate_custom_date">

            </div>
        </div>
    </div>
</div>
