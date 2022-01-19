<!-- Modal -->
<form id="noteOfficeForm" method="POST" files=true enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="noteOfficeFormModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content auth-card">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="exampleModalLabel">Notes for <span note-office-name></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <hr>
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="form-group">
                                <div>
                                    <label for="note_office_textarea"><b>Note</b></label>
                                    <textarea name="note" class="form-control w-100"  id="note_office_textarea" cols="30" rows="10" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="submitEditOfficeFormButton">Save</button>
                </div>
            </div>
        </div>
    </div>

</form>

