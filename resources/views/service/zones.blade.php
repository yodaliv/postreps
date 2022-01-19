<div class="row mx-auto w-100">
    <div class="col-12 d-flex justify-content-center align-items-center">
        <div id="zones-list" class="zones-list width-rem-15 height-rem-38 bg-white">
        </div>

        <div id="zones-map" class="zones-map width-rem-60 height-rem-38 bg-dark"></div>

    </div>
    <div class="col-12 d-flex justify-content-center align-items-center mt-2">
        <div class="overflow-hidden d-flex justify-content-center align-items-center width-rem-15 height-rem-4 ">
            <button class="btn btn-orange rounded-0 px-5" id="add-area"><strong>ADD AREA</strong></button>
        </div>
        <div class="zones-map width-rem-60 height-rem-4 d-flex justify-content-center align-items-center ">
            <div class="input-group ">
                <div class="input-group-prepend">
                    <span class="input-group-text"><strong>TEST ADDRESS</strong></span>
                </div>
                <input type="text" {{--value="fuller park , boise"--}} class="form-control" id="place-input">
                <div class="input-group-append">
                    <button class="btn btn-primary input-group-text" id="find-place"><strong>TEST</strong></button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-12 col-lg-12  text-center mt-5">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-4  text-center">
                <label for="" class="text-white m-0 px-1 font-px-22">Select Holidays</label><br>
                <div id="holidays" class="d-flex justify-content-center m"></div>
            </div>
            <div class="col-12 col-md-8 col-lg-8  c-p" id="service-global-settings">
                <div class="row">
                    <div class="col-md-3">
                        <div class="px-4  height-px-25 justify-content-center align-items-center c-p">
                            <label for="" class="text-white m-0 px-1 font-px-22">Install: </label><br>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1">%</span>
                                </div>
                                <input type="number" value="{{ $service_settings->install }}" name="install" step="any"
                                    class="form-control text-right">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="px-4  height-px-25  justify-content-center align-items-center c-p">
                            <label for="" class="text-white m-0 px-1 font-px-22">Repair: </label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1">%</span>
                                </div>
                                <input type="number" value="{{ $service_settings->repair }}" name="repair" step="any"
                                    class="form-control text-right">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="px-4  height-px-25 justify-content-center align-items-center ">
                            <label for="" class="text-white m-0 px-1 font-px-22">Removal: </label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1">%</span>
                                </div>
                                <input type="number" value="{{ $service_settings->removal }}" name="removal" step="any"
                                    class="form-control text-right">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="px-4 height-px-25 justify-content-center align-items-center c-p">
                            <label for="" class="text-white m-0 px-1 font-px-22">Delivery: </label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1">%</span>
                                </div>
                                <input type="number" value="{{ $service_settings->delivery }}" name="delivery" step="any"
                                    class="form-control text-right">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 margin-top-60px">
                        <div class="px-4 height-px-25 justify-content-center align-items-center c-p">
                            <label for="" class="text-white m-0 px-1 font-px-22">Rush Order Fee: </label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-0 px-1">$</span>
                                </div>
                                <input type="number" value="{{ $service_settings->rush_order }}" name="rush_order" step="any"
                                    class="form-control text-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






{{-- ================================ --}}
<!-- Modal -->
<div class="modal fade" id="zone-settings-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content auth-card">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Zone Settings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('zones.update.settings') }}" method="post" id="update-zone-settings-from"
                    style="margin-top: -10px;">
                    <input type="hidden" name="id">
                    <div class="row">
                        <div class="col-12 d-flex  flex-column align-items-center justify-content-center mb-3">
                            <div class="px-1 w-75 height-px-25 text-black text-center">
                                <h6>Zone Name</h6>
                            </div>
                            <input type="text" placeholder="Zone Name" id="zone-color-settings"
                                class="text-center form-control form-control-sm mb-2 w-75 font-px-18 font-weight-bold"
                                name="zone_name">
                        </div>
                        <div class="col-12 text-center">
                            <h6>Days of Operation</h6>
                            <div class="w-100 d-flex justify-content-between days-checkboxs" style="gap: 5px;">
                                <div class="">
                                    <label for="" class="m-0 text-dark">Su</label>
                                    <input type="checkbox" class="form-control form-control-sm m-0" name="su" value="0">
                                </div>
                                <div>
                                    <label for="" class="m-0  text-dark">Mo</label>
                                    <input type="checkbox" class="form-control form-control-sm m-0" name="m" value="0">
                                </div>
                                <div>
                                    <label for="" class="m-0 text-dark">Tu</label>
                                    <input type="checkbox" class="form-control form-control-sm m-0" name="tu" value="0">
                                </div>
                                <div>
                                    <label for="" class="m-0 text-dark ">We</label>
                                    <input type="checkbox" class="form-control form-control-sm m-0" name="w" value="0">
                                </div>
                                <div>
                                    <label for="" class="m-0 text-dark">Th</label>
                                    <input type="checkbox" class="form-control form-control-sm m-0" name="th" value="0">
                                </div>
                                <div>
                                    <label for="" class="m-0  text-dark">Fr</label>
                                    <input type="checkbox" class="form-control form-control-sm m-0" name="f" value="0">
                                </div>
                                <div>
                                    <label for="" class="m-0 text-dark">Sa</label>
                                    <input type="checkbox" class="form-control form-control-sm m-0" name="sa" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex align-items-center justify-content-center mt-4 mb-3" style="gap: 2px">
                            <label for="installer_points" class="m-0 text-dark">
                                <h6>Installer Points:</h6>
                            </label>
                            <input id="installer_points" type="number"
                                class="form-control form-control-sm width-rem-6 text-right" name="installer_points"
                                value="0">
                        </div>
                        <div class="col-12 d-flex justify-content-center mt-3">
                            <button class="btn btn-orange mx-auto"><strong class="text-white">SAVE</strong></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="search-place-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content auth-card">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center" style="margin-top: -30px;">
                        <h4>Test Address:</h4>
                        <h5 address-query>5840 N Collister Dr, Boise, ID 83703</h5>
                        <h5 zone-name>[ZONE NAME]</h5>
                        <h5 zone-fee>[ZONE FEE]</h5>
                        <div class="w-100 height-rem-20 bg-danger border border-primary" id="search-place-map"></div>
                        <button class="btn btn-orange w-50 mt-5" data-dismiss="modal"><strong
                                class="text-white">OK</strong></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
