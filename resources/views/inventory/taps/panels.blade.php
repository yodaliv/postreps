<div class="tab-pane fade" id="pills-panels" role="tabpanel" aria-labelledby="pills-panels-tab">
    <button class="btn btn-dark panel-global-settings" data-toggle="modal" id="panelSettingButton"  data-target="#createPanelSettingsFormModal">Panel Global Settings</button>

    <select id="PanelType" class="form-control text-center col-2 rounded-0 mb-1 ml-auto be-in-corner">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
    <div class="card auth-card mb-2">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mt-2">Panels</h6>
            <div class="d-flex align-items-center" style="gap: 3px">
                <span>Show</span>
                <select class="form-control" id="showPanelsEntries">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>Entries</span>
            </div>
            <div>
                <a id="destroyAllPanels" class="btn btn-danger">Delete All Panels</a>
            </div>
            <div>
                <input type="text" class="form-control" placeholder="search..." id="panelsSearchInput">
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPanelFormModal">
                    Add New Panels
                </button>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive pt-3">
                <table class="table inventory-table table-bordered table-hover w-100 h-100" id="PanelsTable">
                </table>
            </div>
        </div>
    </div>
</div>
