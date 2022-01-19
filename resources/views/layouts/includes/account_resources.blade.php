<div class="bar-title pt-2">ACCOUNT RESOURCES</div>

<div class="row resources-bar-menu-item mt-3 order-status">
    <div class="col-md-2 img-div">
        <img src="{{ asset('/storage/images/Status_Icon.png') }}">
    </div>
    <div class="col-md-10 pr-3 pt-3">
        <span class="item-title text-div">ORDERS</span>
    </div>
</div>

<div class="row resources-bar-menu-item mt-3 financial-records">
    <div class="col-md-2 img-div">
        <img src="{{ asset('/storage/images/Financial_Icon.png') }}">
    </div>
    <div class="col-md-10 pr-3 pt-3">
        <span class="item-title text-div">ACCOUNTING</span>
    </div>
</div>

<a href="{{ route('services.index') }}" title="services">
    <div class="row resources-bar-menu-item mt-3 service-settings">
        <div class="col-md-2 img-div">
            <img src="{{ asset('/storage/images/Area_Icon.png') }}">
        </div>
        <div class="col-md-10 pr-3 pt-3">
            <span class="item-title text-div">SERVICES</span>
        </div>
    </div>
</a>

<a href="{{ route('users.index') }}" title="users">
    <div class="row resources-bar-menu-item mt-3 users">
        <div class="col-md-2 img-div">
            <img src="{{ asset('/storage/images/Users_Icon.png') }}">
        </div>
        <div class="col-md-10 pr-3 pt-3">
            <span class="item-title text-div">USERS</span>
        </div>
    </div>
</a>

<a href="{{ route('inventories.index') }}" title="inventories">
    <div class="row resources-bar-menu-item mt-3 inventory">
        <div class="col-md-2 img-div">
            <img src="{{ asset('/storage/images/Inventory_Icon.png') }}">
        </div>
        <div class="col-md-10 pr-3 pt-3">
            <span class="item-title text-div">INVENTORY</span>
        </div>
    </div>
</a>

<div class="row resources-bar-menu-item mt-3 contact-us">
    <div class="col-md-2 img-div">
        <img src="{{ asset('/storage/images/Question_Icon.png') }}">
    </div>
    <div class="col-md-10 pr-3 pt-3">
        <span class="item-title text-div">CONTACT US</span>
    </div>
</div>

<div class="row resources-bar-menu-item mt-3 account-settings">
    <div class="col-md-2 img-div">
        <img src="{{ asset('/storage/images/settings_Icon.png') }}">
    </div>
    <div class="col-md-10 pr-3 pt-3">
        <span class="item-title text-div">SETTINGS</span>
    </div>
</div>

<a href="{{ url('regions') }}" title="Regions">
    <div class="row resources-bar-menu-item mt-3 inventory">
        <div class="col-md-2 img-div">
            <img src="{{ asset('/storage/images/Region.png') }}">
        </div>
        <div class="col-md-10 pr-3 pt-3">
            <span class="item-title text-div">REGIONS</span>
        </div>
    </div>
</a>
