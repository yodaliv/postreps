<!-- Modal -->
<form id="editAccessForm" files=true enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="editAccessFormModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content auth-card">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="exampleModalLabel">Edit agent/office access to <span post-name></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-0 bg-white position-relative" style="height: 520px; overflow-y: scroll;">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-office-tab" data-toggle="pill" href="#pills-office"
                                    role="tab" aria-controls="pills-office" aria-selected="true">Offices</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link " id="pills-agent-tab" data-toggle="pill" href="#pills-agent"
                                    role="tab" aria-controls="pills-agent" aria-selected="false">Agents</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active px-1" id="pills-office" role="tabpanel"
                                aria-labelledby="pills-office-tab">
                                <div class="be-in-corner">
                                    <label class="switch">
                                        <input type="checkbox" class="accessOfficeGlobalSwitch" value="0">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div>
                                    <input type="text" class="form-control mb-2" placeholder="search..."
                                        id="officeEditSearchInput">
                                </div>
                                <table id="officesTable" class="table w-100 text-center"></table>
                            </div>

                            <div class="tab-pane fade px-1" id="pills-agent" role="tabpanel"
                                aria-labelledby="pills-agent-tab">
                                <!-- <div class="be-in-corner">
                                    <label class="switch">
                                        <input type="checkbox" class="accessOfficeGlobalSwitch" value="0">
                                        <span class="slider"></span>
                                    </label>
                                </div> -->
                                <input type="text" class="form-control mb-2" placeholder="search..."
                                    id="agentEditSearchInput">
                                <table id="agentsTable" class="table w-100 text-center"></table>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

</form>
