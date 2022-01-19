<div class="modal fade" id="editPrompt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content auth-card">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Prompt</h5>
                <button type="button" class="close" data-dismiss-modal="#editPrompt" aria-label="Close">
                    <span aria-hidden="true" data-dismiss-modal="#editPrompt">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-1">
                        <label for="document_upload_prompt_heading" class="text-dark"><b>Heading</b></label>
                        <input type="text" class="form-control" id="document_upload_prompt_heading"
                            name="document_upload_prompt_heading">
                    </div>
                    <div class="col-12 mb-1">
                        <label for="document_upload_prompt_message" class="text-dark"><b>Message</b></label>
                        <textarea name="document_upload_prompt_message" id="document_upload_prompt_message"
                            class="form-control" cols="30" rows="10"></textarea>
                    </div>
                    <div class="col-12 mt-3 d-flex justify-content-around align-items-center">

                        <button type="button" data-dismiss-modal="#editPrompt" class="btn btn-dark col-md-3 col-lg-3">
                            DONE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
