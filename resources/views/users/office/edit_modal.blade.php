<!-- Modal -->
<form id="editOfficeForm" method="POST" files=true enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <div class="modal fade" id="editOfficeFormModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content auth-card">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Office</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <hr>
                    <div class="row mt-1">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="address"><b>Name</b></label>
                                    <input type="text" id="name" tabindex="1"
                                        class="form-control  @error('name') is-invalid @enderror" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="primary_contact"><b>Primary Contact Name</b></label>
                                    <input type="text" id="primary_contact"  tabindex="6"
                                        class="form-control  @error('primary_contact') is-invalid @enderror"
                                        name="primary_contact">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="address"><b>Street Address</b></label>
                                    <input type="text" id="street_address"   tabindex="2"
                                        class="form-control  @error('address') is-invalid @enderror" placeholder=""
                                        name="address" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="phone"><b>Phone Number</b></label>
                                    <input type="text" id="phone"   tabindex="7"
                                        class="form-control  @error('phone') is-invalid @enderror phones" name="phone"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="city"><b>City</b></label>
                                    <input type="text" id="city"   tabindex="3"
                                        class="form-control  @error('city') is-invalid @enderror" placeholder=""
                                        name="city" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="email"><b>Email</b></label>
                                    <input type="email" id="email"   tabindex="8"
                                        class="form-control @error('email') is-invalid @enderror " placeholder=""
                                        name="email"  required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="state"><b>State</b></label>
                                    <select id="state"  required  tabindex="4"
                                        class="form-control  @error('state') is-invalid @enderror" placeholder=""
                                        name="state">
                                        <option value=""></option>
                                        @if (count($states))
                                            @foreach ($states as $code => $state)
                                                <option value="{{ $code }}" @if ($code === 'ID') selected @endif>
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
                                    <input type="text" id="zipcode"   tabindex="5"
                                        class="form-control @error('zipcode') is-invalid @enderror zipcode"
                                        name="zipcode" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <div>
                                    <label for="email"><b>Website</b></label>
                                    <input type="text"  id="website"  tabindex="9"
                                        class="form-control @error('website') is-invalid @enderror " name="website" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="inactive"><b>Account Status</b></label>
                                    <select id="inactive"  tabindex="10"   class="form-control  @error('inactive') is-invalid @enderror"
                                        name="inactive">
                                        <option value="0"><b class="text-muted">Active</b>
                                        </option>
                                        <option value="1"><b class="text-muted">Inactive</b></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="private"><b>Private/Public</b></label>
                                    <select id="private"  tabindex="11"   class="form-control  @error('private') is-invalid @enderror"
                                        name="private">
                                        <option value="0"><b class="text-muted">Public</b>
                                        </option>
                                        <option value="1"><b class="text-muted">Private</b>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="email"><b>Name Abbreviation</b></label>
                                    <input type="text"  tabindex="12"   id="name_abbreviation"
                                        class="form-control @error('name_abbreviation') is-invalid @enderror "
                                        name="name_abbreviation" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="region_id"><b>Region</b></label>
                                    <select id="region_id"   tabindex="13"
                                        class="form-control  @error('region_id') is-invalid @enderror" name="region_id">
                                        @foreach ($regions as $r)
                                            <option value="{{ $r->id }}"><b
                                                    class="text-muted">{{ $r->name }}</b>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <div>
                                    <label for="edit_logo_image" class="btn btn-primary text-white btn-sm mt-3"><b>Upload
                                            Logo</b></label>
                                    <input type="file" name="edit_logo_image" style="display: none" id="edit_logo_image"
                                        accept="image/*" />
                                    <div class="edit_logo_preview" ><img /></div>
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

