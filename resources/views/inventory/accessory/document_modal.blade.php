<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="myModalError"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="messageModelTitle">Alert!</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="messageModelContent"></span>
                <form>
                    <input type="file" class="d-none" name="document_files[]" multiple id="accessory_documents">
                </form>
                <label for="accessory_documents" class="btn btn-primary d-block mt-3"><strong>UPLOAD
                        FILES</strong></label>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
