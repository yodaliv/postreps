<div class="modal fade" id="createMessage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content auth-card">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Message</h5>
                <button type="button" class="close" data-dismiss-modal="#createMessage" aria-label="Close">
                    <span aria-hidden="true" data-dismiss-modal="#createMessage">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-1">
                        <label for="popup_message_heading" class="text-dark"><b>Heading</b></label>
                        <input type="text" class="form-control" id="popup_message_heading"
                            name="popup_message_heading">
                    </div>
                    <div class="col-12 mb-1">
                        <label for="popup_message_message" class="text-dark"><b>Message</b></label>
                        <textarea name="popup_message_message" id="popup_message_message" class="form-control" cols="30" rows="10"></textarea>
                    </div>
                    <div class="col-12 mt-3 d-flex justify-content-around align-items-center">

                        <button type="button" data-dismiss-modal="#createMessage" class="btn btn-dark col-md-3 col-lg-3">
                            DONE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
