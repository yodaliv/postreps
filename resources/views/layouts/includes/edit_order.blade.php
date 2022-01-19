<div class="overlay"></div>
<!-- Modal -->
<div class="modal fade" id="edit_install_post_modal" data-keyboard="true" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content auth-card">
            <div class="modal-header">
                <h5 class="modal-title">Install Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('install_post') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="row">
                        {{-- office and agent --}}
                        <div class="col-12">
                            <div class="text-orange-dark d-flex align-items-center gap-px-4">
                                <span class="cnumber">1</span>
                                <h5 class="pt-2">OFFICE AND AGENT</h5>
                            </div>
                            <div class="px-4">
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-6 mt-2">
                                        <div class="d-flex justify-content-start align-items-center px-2">
                                            <label for="einstall_post_office" class="text-dark m-0"><b>OFFICE:
                                                </b></label>
                                            <select class="form-control   ml-2" name="einstall_post_office"
                                                id="einstall_post_office">
                                                <option value="" selected>Select office</option>
                                                @foreach ($offices as $o)
                                                    <option value="{{ $o->id }}">{{ $o->user->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6 mt-2">
                                        <div class="d-flex justify-content-start align-items-center px-2">
                                            <label for="einstall_post_agent" class="text-dark m-0"><b>AGENT:
                                                </b></label>
                                            <select class="form-control   ml-2" name="einstall_post_agent"
                                                id="einstall_post_agent">
                                                <option value="">Select Agent</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- property information --}}
                        <div class="col-12  mt-4">
                            <div class="text-orange-dark d-flex align-items-center gap-px-4">
                                <span class="cnumber">2</span>
                                <h5 class="pt-2">PROPERTY INFORMATION</h5>
                            </div>
                            <div class="px-4">
                                <div class="row">
                                    <div class="col-12 mt-4">
                                        <label for="address" class="text-primary"><span
                                                class="blue-label">Property Address</span></label>
                                        <input type="text" class="form-control" name="einstall_post_address"
                                            placeholder="Write address and press [ENTER]" id="eaddress"
                                            {{-- value="fuller park , boise" --}}>
                                    </div>
                                    <div class="col-12 my-3">
                                        <div class="width-100 height-rem-22 border border-dark shadow"
                                            id="edit-install-post-search-map"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="text-primary"><span class="blue-label">Location
                                                Adjustment</span></label>
                                        <div class="d-flex justify-content-start align-items-center px-2">
                                            <input type="checkbox" class="form-control w-h-px-25 mr-2"
                                                name="elocation_adjustment" id="elocation_adjustment">
                                            <label for="elocation_adjustment" class="text-dark m-0"><b>Manually move
                                                    pin
                                                    placement on
                                                    map</b></label>
                                        </div>
                                    </div>
                                    <div class="col-12 pt-4">
                                        <label class="text-primary"><span class="blue-label">Property
                                                Type</span></label>
                                        <div class="d-flex justify-content-start align-items-center px-2">
                                            <label for="einstall_post_property_type" class="text-dark m-0"><b>What type
                                                    of Property is
                                                    this?</b></label>
                                            <select name="einstall_post_property_type" id="einstall_post_property_type"
                                                class="form-control form-control-sm width-rem-15 ml-2">
                                                <option value="" selected>Select property type</option>
                                                <option value="1">Existing Home/Condo</option>
                                                <option value="2">New Construction</option>
                                                <option value="3">Vacant Land</option>
                                                <option value="4">Commercial/Industrial</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- desired date --}}
                        <div class="col-12 col-md-6 col-lg-6 mt-4">
                            <div class="text-orange-dark d-flex align-items-center gap-px-4">
                                <span class="cnumber">3</span>
                                <h5 class="pt-2">DESIRED DATE</h5>
                            </div>
                            <div class="px-4">
                                <div class="d-flex justify-content-start w-100">
                                    <div>
                                        <div class="row width-rem-15">
                                            <div class="col-12 mt-2">
                                                <div class="d-flex justify-content-start align-items-center px-2">
                                                    <input type="radio" value="asap" class="form-control w-h-px-25 mr-2"
                                                        name="einstall_post_desired_date" id="edesired_date">
                                                    <label for="edesired_date" class="text-dark m-0"><b>As Soon As
                                                            Possible</b></label>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="d-flex justify-content-start align-items-center px-2">
                                                    <input type="radio" value="custom_date"
                                                        class="form-control w-h-px-25 mr-2"
                                                        name="einstall_post_desired_date" id="ecustom_date">


                                                    <label for="ecustom_date" class="text-dark m-0"><b>Specify a
                                                            Desired
                                                            Date</b></label>
                                                </div>
                                                <input type="text" readonly="" name="einstall_post_custom_desired_date"
                                                    class="form-control form-control-sm mt-4 width-px-120 d-none"
                                                    id="datepicker">
                                                <p></p>

                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div id="eselectdate_custom_date">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- signpost and  accessorries --}}
                        <div class="col-12   mt-4">
                            <div class="text-orange-dark d-flex align-items-center gap-px-4">
                                <span class="cnumber">4</span>
                                <h5 class="pt-2">SIGNPOST AND ACCESSORIES</h5>
                            </div>
                            <div class="px-4">
                                <div class="row">

                                    <div class="col-12 col-md-4 col-lg-4 mt-2">
                                        <label for="einstall_post_select_post" class="text-primary text-center d-block text-center"><span class="blue-label">Post (select one)</span></label>
                                        {{-- container --}}
                                        <div class="list-container elist-container-posts"></div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4 mt-2">
                                        <label for="install_post_select_sign"
                                            class="text-primary d-block text-center"><span class="blue-label">Sign (select one)</span></label>
                                        <div class="list-container-2">
                                            <div class="form-check ">
                                                <input type="radio" name="einstall_post_panel_type" value="-1"
                                                    class="form-check-input" id="epanel_option__1">
                                                <label class="form-check-label text-dark" for="epanel_option__1">Agent will Hang Own Sign</label>
                                            </div>
                                            <div class="form-check ">
                                                <input type="radio" name="einstall_post_panel_type" value="-2"
                                                    class="form-check-input" id="epanel_option__2">
                                                <label class="form-check-label text-dark" for="epanel_option__2">Sign Left at Property</label>
                                            </div>
                                        </div>
                                        {{-- container --}}
                                        <div class="list-container sign-list elist-container-signs position-relative"></div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4 mt-2">
                                        <label for="einstall_post_select_accessories"
                                            class="text-primary d-block text-center"><span
                                                class="blue-label">Accessories (optional)</span></label>
                                                {{-- container --}}
                                        <div class="list-container elist-container-accessories"></div>
                                    </div>
                                    <div class="col-12 einstall-post-preview-images d-flex justify-content-center align-items-center flex-wrap py-2"
                                        style="gap:10px">
                                        <img class="max-width-125px max-height-113px eorder-preview-imgs"
                                            id="post_image_preview" src="{{ url('/private/image/post/0') }}">
                                        <img class="max-width-125px max-height-113px eorder-preview-imgs"
                                            id="sign_image_preview" src="{{ url('/private/image/panel/0') }}">
                                        <img class="max-width-125px max-height-113px eaccessory_image_preview eorder-preview-imgs"
                                            src="{{ url('/private/image/accessory/0') }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- Comment --}}
                        <div class="col-12   mt-4">
                            @include('layouts.includes.comment')
                        </div>
                        {{-- Attachments --}}
                        <div class="col-12   mt-5">
                            @include('layouts.includes.attachments')
                        </div>
                        {{-- Footer --}}
                        <div class="col-12   mt-4">
                            @include('layouts.includes.install_post_footer')
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>