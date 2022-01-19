<div class="text-orange-dark d-flex align-items-center gap-px-4">
    <span class="cnumber">2</span>
    <h5 class="pt-2">PROPERTY INFORMATION</h5>
</div>
<div class="px-4">
    <div class="row">
        <div class="col-12 mt-4">
            <label for="repairOrderAddress" class="text-primary"><span class="blue-label">Property Address</span></label>
            <div class="d-flex">
                <input type="text" class="form-control w-100" id="repairOrderAddress" disabled
                    placeholder="Write address and press [ENTER]" id="address" {{-- value="fuller park , boise" --}}>
            </div>
        </div>
        <div class="col-12 my-3">
            <div class="width-100 height-rem-22 border border-dark shadow" id="repairOrderMap"></div>
        </div>

    </div>
</div>
<div class="text-orange-dark d-flex align-items-center mt-4 gap-px-4">
    <span class="cnumber">3</span>
    <h5 class="pt-2">PROPERTY TYPE</h5>
</div>
<div class="d-flex justify-content-start align-items-center pl-5">
    <label for="repairOrderPropertyType" class="text-dark m-0"><b>What type of Property is
            this?</b></label>
    <select  id="repairOrderPropertyType"
        class="form-control form-control-sm width-rem-15 ml-2" disabled>
        <option value="" selected>Select property type</option>
        <option value="1">Existing Home/Condo</option>
        <option value="2">New Construction</option>
        <option value="3">Vacant Land</option>
        <option value="4">Commercial/Industrial</option>
    </select>
</div>
