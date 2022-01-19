<div class="tab-pane fade  " id="pills-agents" role="tabpanel">
    <div class="be-in-corner import-export-buttons">
        <a href="{{ route('export.agents') }}" class="btn btn-dark ">EXPORT</a>
    <form action="{{ route('import.agents') }}"  method="post" enctype="multipart/form-data" id="importAgentsForm">
        @csrf
        <input type="file"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            name="file" id="importAgentFile" class="d-none">
            <label for="importAgentFile" class="btn btn-dark m-0">IMPORT</label>
    </form>
    </div>
    <select id="agentType" class="form-control text-center col-2 rounded-0 mb-1 ml-auto be-in-corner" id="">
        <option value="0">Active</option>
        <option value="1">Inactive</option>
    </select>
    <div class="card auth-card mb-2">
        <div class="card-header d-flex justify-content-between">
            <h6 class="mt-2">Agents</h6>
            <div class="d-flex align-items-center" style="gap: 3px">
                <span>Show</span>
                <select class="form-control" id="showAgentsEntries">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>Entries</span>
            </div>
            <div>
                <a id="destroyAllAgents" class="btn btn-danger">Delete All Agents</a>
            </div>
            <div>
                <input type="text" class="form-control" placeholder="search..." id="agentSearchInput">
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createAgentFormModal">
                    Add New Agent
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive pt-3">
                <table class="table table-hover  w-100" id="agentsTable"> </table>
            </div>
        </div>
    </div>
</div>
