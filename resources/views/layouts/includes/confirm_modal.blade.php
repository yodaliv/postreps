   <!--Error Modal -->
   <div class="modal fade" id="confirmModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalError" aria-hidden="true">
      <div class="modal-dialog modal-sm">
         <div class="modal-content">
            <div class="modal-header">
              <h6 class="modal-title font-weight-bold" id="myModalError">Are you sure?</h6>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <span id="confirmModalContent">This action is irreversible!</span>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger"  type="button" id="confirmBtnOk">Yes</button>
                <button class="btn btn-secondary"  type="button" id="confirmBtnCancel">Cancel</button>
            </div>
         </div>
      </div>
   </div>
