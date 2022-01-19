<div class="text-orange-dark d-flex align-items-center gap-px-4">
    <span class="cnumber">7</span>
    <h5 class="pt-2">ATTACHMENTS</h5>
</div>
<div style="margin-top: -12px; margin-left: 43px;">
    Accepted file formats: PDF, GIF, PNG, JPG
</div>
<div class="px-4">
    <div class="row">
        <div class="col-12 mt-1">
            <input type="file" multiple name="install_post_files[]" class="d-none" id="install_post_files">
            <div class="alert alert-warning alert-dismissible fade show d-none document-required-warning" role="alert">
                <strong>
                    <label
                        for="install_post_files"
                        class="text-primary c-p m-0 underline"
                        onclick="window.uploadAccessoryFile('plat-map')">
                        UPLOAD FILES
                    </label>
                    <i class="fas fa-exclamation-triangle ml-3"></i> Notice!
                </strong> Plat map upload required.
            </div>
            <div id="warning-alerts"></div>

            <ul id="files_list">
                {{-- <li><span><a href="#"><strong>filename.png</strong></a>[UPLOADED 9/6/2021 1:32PM] <a href="#"><strong>[REMOVE]</strong></a></span></li> --}}
            </ul>
            <strong><label for="install_post_files" id="uploadOtherDoc"
                    class="text-primary c-p  underline">UPLOAD</label> OTHER DOCUMENTS</strong>

        </div>
    </div>
</div>
