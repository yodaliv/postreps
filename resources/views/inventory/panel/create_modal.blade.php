<!-- Modal -->
<form id="createPanelForm" class="panel-form panel-create-form" method="post" action="{{ route('panels.store') }}" files=true
    enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="createPanelFormModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content auth-card">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="exampleModalLabel">Create Panel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="row h-100">
                            <div class="col-12 col-md-4 col-lg-4 mb-2" id="panelImageContainer">
                                <div id="imgContainer" class="text-center">
                                    <img src="{{ url('/private/image/panel/0') }}" class="img-fluid"
                                        style="width: 230px; max-height: 307px;">
                                </div>

                                <div
                                    class="d-flex flex-column justify-content-center align-items-center gap-px-3 w-100">
                                    <label for="panelImageInput"
                                        class="text-center btn btn-primary w-100 font-weight-bold mx-auto mt-3">UPLOAD
                                        PHOTO</label>
                                    <input type="file" name="panel_image" id="panelImageInput" class="d-none">
                                    <button class="btn btn-orange  w-100 font-weight-bold mx-auto mt-3">SAVE</button>
                                    <button class="btn btn-secondary w-100 font-weight-bold mx-auto mt-3"
                                        data-dismiss="modal">CANCEL</button>
                                </div>
                            </div>
                            <div class="col-12 col-md-8 col-lg-8 mb-2">
                                <div class="form-group row">
                                    <label class="col-form-label text-right text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="panel_name">
                                        <b>PANEL NAME:</b>
                                    </label>
                                    <input type="text" id="panel_name" name="panel_name"
                                        class="form-control col-12 col-md-8 col-lg-8" required>
                                </div>
                                <div class="row">
                                    <div class="col-12 co-md-6 col-lg-6">
                                        <div class="form-group row ">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-6 col-lg-6"
                                                for="quantity"><b>QUANTITY:</b></label>
                                            <input type="number" step="any" id="quantity" name="quantity"
                                                class="form-control col-12 col-md-5 col-lg-5" required>
                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-6 col-lg-6"
                                                for="price"><b>PRICE:</b></label>
                                            <div class="pl-0  col-12 col-md-6 col-lg-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" value="{{$panelSetting->price??0}}" step="any" step="any" id="price" name="price"
                                                        class="form-control">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-6 col-lg-6 pr-1"
                                                for="free_storage"><b>FREE STORAGE:</b></label>
                                            <div class="pl-0  col-12 col-md-6 col-lg-6">
                                                <div class="input-group">
                                                    <input type="number" step="any" step="any" id="free_storage"
                                                        name="free_storage"  value="{{$panelSetting->free_storage??0}}" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-6 col-lg-6 pr-1"
                                                for="cost_per_unit"><b>COST PER UNIT:</b></label>
                                            <div class="pl-0  col-12 col-md-6 col-lg-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" step="any" step="any" id="cost_per_unit"
                                                        name="cost_per_unit" value="{{$panelSetting->cost_per_unit??0}}" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    {{-- Second columns --}}
                                    <div class="col-12 co-md-6 col-lg-6">
                                        <div class=" text-center border-top border-bottom border-dark mb-3 w-200">
                                            <span>Sign Dimensions</span>
                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-5 col-lg-5"
                                                for="width"><b>WIDTH:</b></label>
                                            <input type="number"  min="0" step="any" id="width" name="width"
                                                class="form-control col-12 col-md-5 col-lg-5 ">
                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-5 col-lg-5"
                                                for="height"><b>HEIGHT:</b></label>
                                            <input type="number"  min="0" step="any" id="height" name="height"
                                                class="form-control col-12 col-md-5 col-lg-5 ">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-right text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="frequency"><b>FREQUENCY:</b></label>
                                    <input type="number" step="any" id="frequency" name="frequency"
                                        class="form-control col-12 col-md-3 col-lg-3" value="{{$panelSetting->frequency??0}}" required>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="listing_order"><b>ORDER:</b></label>
                                    <input type="number" step="any" id="listing_order" name="listing_order"
                                        class="form-control col-12 col-md-3 col-lg-3">
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-right text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="office_id"><b>OFFICE:</b></label>
                                    <select type="text" id="office_id" name="office_id"
                                        class="form-control col-12 col-md-8 col-lg-8" required>
                                        <option value="" class="text-muted">None selected</option>
                                        @foreach ($offices as $o)
                                            <option value="{{ $o->id }}">{{ $o->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                               <div class="agents-dropdowns-list">
                                <div class="form-group row create-add-agent-form-group">
                                    <label class="col-form-label text-right text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="agent_id"><b>AGENT:</b></label>
                                    <select type="text" id="agent_id"  name="agent_id[]"
                                        class="form-control col-12 col-md-8 col-lg-8">
                                        <option value="" class="text-muted">None selected</option>
                                    </select>
                                </div>
                               </div>
                                <div class="row">
                                    <div
                                        class="d-flex align-items-center justify-content-between col-12 col-md-5 col-lg-5 mx-auto">
                                        <a
                                            type="button"

                                            class="link font-weight-bold create-add-agent">ADD AGENT</a>
                                    </div>
                                    <div
                                        class="d-flex align-items-center justify-content-between col-12 col-md-5 col-lg-5 mx-auto">
                                        <label class="text-dark m-0" for="status"><b>STATUS:</b></label>
                                        <select type="text" id="status" name="status"
                                            class="form-control m-1 select-active" required=""
                                            required-error-msg="Status is required">
                                            <option value="1" class="text-muted">ACTIVE</option>
                                            <option value="0" class="text-muted">INACTIVE</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</form>
