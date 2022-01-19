<div class="tab-pane fade show active" id="pills-offices" role="tabpanel">
    <select id="officeType" class="form-control text-center col-2 rounded-0 mb-1 ml-auto be-in-corner" id="">
        <option value="0">Active</option>
        <option value="1">Inactive</option>
    </select>
    <div class="card auth-card mb-2">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mt-2">Offices</h6>
            <div class="d-flex align-items-center" style="gap: 3px">
                <span>Show</span>
                <select class="form-control" id="showOfficesEntries">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>Entries</span>
            </div>
            <div>
                <!-- <a id="destroyAllOffices" class="btn btn-danger">Delete All Offices</a> -->
            </div>
            <div>
                <input type="text" class="form-control" placeholder="search..." id="officeSearchInput">
            </div>
            <div>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createOfficeFormModal">
                    Add New Office
                </button>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive pt-3">
                <table class="table table-hover  w-100" id="officesTable"> </table>
            </div>
        </div>
    </div>

</div>
