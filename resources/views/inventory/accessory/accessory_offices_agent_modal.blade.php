<div class="modal fade" id="createAccessoryAgentOffice" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content auth-card">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">INVENTORY LIST</h5>
                <button type="button" class="close" data-dismiss-modal="#createAccessoryAgentOffice"
                    aria-label="Close">
                    <span aria-hidden="true" data-dismiss-modal="#createAccessoryAgentOffice">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-1">
                        <div class="table-responsive height-rem-25">
                            <input type="text" placeholder="search..." class="form-control no-shadow" name="" id="search-createAccessoryAgentOfficeTable">
                            <table id="createAccessoryAgentOfficeTable" offices='{!! str_replace("'", "", $offices->toJson()) !!}''
                                class="table w-100 text-center">
                                <thead>
                                    <tr>
                                        <th class="width-percent-25">
                                            ITEM ID
                                        </th>
                                        <th class="width-percent-25">
                                            OFFICE
                                        </th>
                                        <th class="width-percent-25">
                                            AGENT
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white   ">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 mt-3 d-flex justify-content-around align-items-center">

                        <button type="button" accessory-inventories-done class="btn btn-dark col-md-3 col-lg-3">
                            DONE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
