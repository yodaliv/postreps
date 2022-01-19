<!-- Modal -->
<form id="changeOfficeForm" method="POST" files=true enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="changeOfficeFormModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content auth-card">
                <div class="modal-header">
                    <h5 >SELECT OFFICE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-2 search-col">
                            <div class="d-flex align-items-center ">
                                 <input type="text" class="form-control  border border-dark" placeholder="SEARCH"  id="changeOfficeTableSearch">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="changeOfficeTableContainer border border-dark">
                                <table id="changeOfficeTable" class="w-100"></table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success mx-auto p-2 col-4" id="searchOfficeSubmit">Save</button>
                </div>
            </div>
        </div>
    </div>

</form>
