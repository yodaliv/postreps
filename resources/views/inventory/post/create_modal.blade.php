<!-- Modal -->
<form id="createPostForm" class="post-form" method="POST" action="{{ route('posts.store') }}" files=true enctype="multipart/form-data">
    @csrf

    <div class="modal fade" id="createPostFormModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content auth-card">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="exampleModalLabel">Create Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="row h-100">
                            <div class="col-12 col-md-4 col-lg-4 mb-2" id="postImageContainer">
                                <div id="imgContainer" class="text-center">
                                    <img src="{{ url('/private/image/post/0') }}" class="img-fluid" style="max-width: 230px; max-height: 307px;">
                                </div>
                                <div
                                    class="d-flex flex-column justify-content-center align-items-center gap-px-3 w-100">
                                    <label for="postImageInput"
                                        class="text-center btn btn-primary w-100 font-weight-bold mx-auto mt-3">UPLOAD
                                        PHOTO</label>
                                    <input type="file" name="image" id="postImageInput" class="d-none">
                                    <button class="btn btn-orange  w-100 font-weight-bold mx-auto mt-3">SAVE</button>
                                    <button class="btn btn-secondary w-100 font-weight-bold mx-auto mt-3"
                                        data-dismiss="modal">CANCEL</button>
                                </div>
                            </div>
                            <div class="col-12 col-md-8 col-lg-8 mb-2">
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="post_name">
                                        <b>POST NAME:</b>
                                    </label>
                                    <input type="text" id="post_name" name="post_name"
                                        class="form-control col-12 col-md-8 col-lg-8" required>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="quantity"><b>QUANTITY:</b></label>
                                    <input type="number" id="quantity" name="quantity"
                                        class="form-control col-12 col-md-3 col-lg-3">
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="price"><b>PRICE:</b></label>
                                    <div class="input-group pl-0 col-12 col-md-4 col-lg-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="any" id="price" name="price" class="form-control "
                                            required required-error-msg="Price is required">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="renewal_fee"><b>RENEWAL:</b></label>
                                    <div class="input-group pl-0 col-12 col-md-4 col-lg-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="any" id="renewal_fee" name="renewal_fee"
                                            class="form-control" required required-error-msg="Renewal is required">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="loss_damage"><b>LOSS/DAMAGE:</b></label>
                                    <div class="input-group pl-0 col-12 col-md-4 col-lg-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="any" id="loss_damage" name="loss_damage"
                                            class="form-control" required
                                            required-error-msg="Loss / Damage is required">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="time_days"><b>TIME/DAYS:</b></label>
                                    <input type="number" step="any" id="time_days" name="time_days"
                                        class="form-control col-12 col-md-3 col-lg-3" required
                                        required-error-msg="Time / Days is required">
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="point_value"><b>POINT VALUE:</b></label>
                                    <input type="number" step="any" id="point_value" name="point_value"
                                        class="form-control col-12 col-md-3 col-lg-3">
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="listing_order"><b>ORDER:</b></label>
                                    <input type="number" step="any" id="listing_order" name="listing_order"
                                        class="form-control col-12 col-md-3 col-lg-3">
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="office_id"><b>OFFICE:</b></label>
                                    <select type="text" id="office_id" name="office_id"
                                        class="form-control col-12 col-md-8 col-lg-8">
                                        <option value="" class="text-muted">None selected</option>
                                        @foreach ($offices as $o)
                                            <option value="{{ $o->id }}">{{ $o->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-dark m-0 col-12 col-md-3 col-lg-3"
                                        for="agent_id"><b>AGENT:</b></label>
                                    <select type="text" id="agent_id" name="agent_id"
                                        class="form-control col-12 col-md-8 col-lg-8">
                                        <option value="" class="text-muted">None selected</option>
                                    </select>
                                </div>
                                <div class="w-100 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center justify-content-between my-1">
                                        <label class="text-dark m-0" for="default"><b>DEFAULT:</b></label>
                                        <select type="text" id="default" name="default" class="form-control  m-1"
                                            required required-error-msg="Default is required">
                                            <option value="1" class="text-muted">ON</option>
                                            <option value="0" class="text-muted">OFF</option>
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="text-dark m-0" for="status"><b>STATUS:</b></label>
                                        <select type="text" id="status" name="status" class="form-control  m-1" required
                                            required-error-msg="Status is required">
                                            <option value="1" class="text-muted">ACTIVE</option>
                                            <option value="0" class="text-muted">INACTIVE</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-12 font-weight-bold  mt-3">
                                    <a class="link" href="#">EDIT AGENTS/OFFICE ACCESS</a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</form>
