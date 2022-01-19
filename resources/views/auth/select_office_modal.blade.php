<!-- Modal -->

<div class="modal fade" id="setOfficeFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content auth-card">
            <div class="modal-header">
                <h5>SELECT OFFICE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-2  search-col">
                        <div class="d-flex align-items-center ">
                            <input validation-except type="text" class="form-control   border border-dark" id="setOfficeSearch"
                                placeholder="SEARCH">
                        </div>
                    </div>
                    <div class="col-12">

                        <div class="changeOfficeTableContainer border border-dark">
                            <table id="setOfficeTable" class="w-100"></table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center align-items-center">
                <button type="submit" class="btn btn-primary col-12 col-md-4 col-lg-4 mx-2 ">Submit</button>
                <a id="officeNotFound" href="/" class="d-none"><b><u>OFFICE NOT FOUND</u></b></a>
            </div>
        </div>
    </div>
</div>
