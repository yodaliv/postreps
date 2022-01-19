<!-- Modal -->
<form id="createPanelSettingsForm" class="panelSettings-form panelSettings-create-form" method="post"
    action="{{ route('panelSettings.store') }}" files=true enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="createPanelSettingsFormModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content auth-card">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="exampleModalLabel">Create Panel Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="row ">

                            <div class="col-12 col-md-12 col-lg-12 mb-2">

                                <div class="row">
                                    <div class="col-12 co-md-8 col-lg-8 mx-auto">

                                        <div class="form-group row ">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-6 col-lg-6 pr-1"
                                                for="price"><b>DEFAULT PRICE:</b></label>
                                            <div class="pl-0  col-12 col-md-4 col-lg-4">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" step="any"
                                                        value="{{ $panelSetting->price ?? 0 }}" required step="any"
                                                        id="price" name="price" class="form-control">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-6 col-lg-6 pr-1"
                                                for="free_storage"><b>DEFAULT FREE STORAGE:</b></label>
                                            <div class="pl-0  col-12 col-md-4 col-lg-4">
                                                <div class="input-group">
                                                    <input type="number" step="any" step="any" id="free_storage"
                                                        name="free_storage" value="{{ $panelSetting->free_storage ?? 0 }}"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-6 col-lg-6 pr-1"
                                                for="cost_per_unit"><b>DEFAULT COST PER UNIT:</b></label>
                                            <div class="pl-0  col-12 col-md-4 col-lg-4">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" step="any" step="any" id="cost_per_unit"
                                                        name="cost_per_unit"
                                                        value="{{ $panelSetting->cost_per_unit ?? 0 }}"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-form-label text-right text-dark m-0 col-12 col-md-6 col-lg-6 pr-1"
                                                for="frequency"><b>DEFAULT FREQUENCY:</b></label>
                                            <div class="pl-0  col-12 col-md-4 col-lg-4">
                                                <div class="input-group">
                                                    <input type="number" step="any" id="frequency" name="frequency"
                                                        class="form-control " value="{{ $panelSetting->frequency ?? 0 }}"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-around align-items-center">
                                        <button class="btn btn-orange col-md-3 col-lg-3">
                                            SAVE
                                        </button>
                                        <button type="button" data-dismiss="modal"
                                            class="btn btn-dark col-md-3 col-lg-3">
                                            CANCEL
                                        </button>
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
