<!-- Button trigger modal -->
<!-- Modal -->
<form id="createAgentForm" method="POST" action="{{ route('agents.store') }}" files=true
    enctype="multipart/form-data">

    <div class="modal fade" id="createAgentFormModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content auth-card">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Agent</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row mt-1">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="first_name"><b>First Name</b></label>
                                    <input type="text" id="first_name" tabindex="1"
                                        class="form-control  @error('first_name') is-invalid @enderror" name="first_name"
                                        value="{{ old('first_name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="last_name"><b>Last Name</b></label>
                                    <input type="text" id="last_name" tabindex="2"
                                        class="form-control  @error('last_name') is-invalid @enderror"
                                        name="last_name" value="{{ old('last_name') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="address"><b>Street Address</b></label>
                                    <input type="text" id="street_address" tabindex="3"
                                        class="form-control  @error('address') is-invalid @enderror" placeholder=""
                                        name="address" required value="{{ old('address') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="email"><b>Email</b></label>
                                    <input type="email" id="email" tabindex="7"
                                        class="form-control @error('email') is-invalid @enderror " placeholder=""
                                        name="email" required value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="city"><b>City</b></label>
                                    <input type="text" id="city" tabindex="4"
                                        class="form-control  @error('city') is-invalid @enderror" placeholder=""
                                        name="city" required value="{{ old('city') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">

                            <div class="form-group">
                                <div>
                                    <label for="phone"><b>Phone Number</b></label>
                                    <input type="text" id="phone" tabindex="8"
                                        class="form-control  @error('phone') is-invalid @enderror phones" name="phone"
                                        required value="{{ old('phone') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="state"><b>State</b></label>
                                    <select id="state" required tabindex="5"
                                        class="form-control  @error('state') is-invalid @enderror" placeholder=""
                                        name="state">
                                        <option value=""></option>
                                        @if (count($states))
                                            @foreach ($states as $code => $state)
                                                <option value="{{ $code }}" @if (old('state') === $code or $code === 'ID') selected @endif>
                                                    {{ $state }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="email"><b>Zipcode</b></label>
                                    <input type="text" id="zipcode" tabindex="6"
                                        class="form-control @error('zipcode') is-invalid @enderror zipcode"
                                        name="zipcode" value="{{ old('zipcode') }}" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="email"><b>RE license #</b></label>
                                    <input type="text" id="re_license" tabindex="9"
                                        class="form-control @error('re_license') is-invalid @enderror " name="re_license"
                                        value="{{ old('re_license') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="inactive"><b>Account Status</b></label>
                                    <select id="inactive" class="form-control  @error('inactive') is-invalid @enderror"
                                        name="inactive" tabindex="10">
                                        <option value="0" @if (old('inactive') == 0) selected @endif><b class="text-muted">Active</b>
                                        </option>
                                        <option value="1" @if (old('inactive') == 1) selected @endif><b
                                                class="text-muted">Inactive</b></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="agent_office"><b>Office</b></label>
                                    <select id="agent_office" tabindex="11" required
                                        class="form-control  @error('agent_office') is-invalid @enderror" placeholder=""
                                        name="agent_office">
                                        <option value="0">No Office Assigned</option>
                                        @if (count($states))
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}" @if (old('office') === $office->id) selected @endif>
                                                    {{ $office->user->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="createAgentForm" value="true" class="btn btn-success"
                        id="submitCreateAgentFormButton">Save</button>
                </div>
            </div>
        </div>
    </div>

</form>
