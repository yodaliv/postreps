<div class="tab-pane fade" id="pills-accessories" role="tabpanel" aria-labelledby="pills-accessories-tab">
    {{-- <button class="btn btn-dark panel-global-settings" data-toggle="modal"
        data-target="#createAccessoryFormModal">Panel Global Settings</button> --}}

    <select id="AccessoryType" class="form-control text-center col-2 rounded-0 mb-1 ml-auto be-in-corner">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
    <div class="card auth-card mb-2">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mt-2">Accessories</h6>
            <div class="d-flex align-items-center" style="gap: 3px">
                <span>Show</span>
                <select class="form-control" id="showAccessoriesEntries">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>Entries</span>
            </div>
            <div>
                <a id="destroyAllAccessories" class="btn btn-danger">Delete All Accessories</a>
            </div>
            <div>
                <input type="text" class="form-control" placeholder="search..." id="accessoriesSearchInput">
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createAccessoryFormModal">
                    Add New Accessory
                </button>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive pt-3">
                <table class="table inventory-table table-bordered table-hover w-100 h-100" id="AccessoriesTable">
                </table>
            </div>
        </div>
    </div>
</div>
