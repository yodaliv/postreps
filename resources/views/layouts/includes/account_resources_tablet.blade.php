<a href="{{ url('/order-status') }}" title="Orders">
    <div class="row resources-bar-menu-item mt-5 order-status">
        <div class="col-md-2 text-center">
            <img src="{{ asset('/storage/images/Status_Icon.png') }}">
        </div>
    </div>
</a>

<div class="row resources-bar-menu-item mt-3 financial-records">
    <div class="col-md-2 text-center">
        <img src="{{ asset('/storage/images/Financial_Icon.png') }}">
    </div>
</div>

<div class="row resources-bar-menu-item mt-3 service-settings">
    <div class="col-md-2 text-center">
        <img src="{{ asset('/storage/images/Area_Icon.png') }}">
    </div>
</div>

<div class="row resources-bar-menu-item mt-3 users">
    <a href="{{ route('users.index') }}" title="users">
        <div class="col-md-2 text-center">
            <img src="{{ asset('/storage/images/Users_Icon.png') }}">
        </div>
    </a>
</div>

<a href="{{ route('inventories.index') }}" title="inventories">
    <div class="row resources-bar-menu-item mt-3 inventory">
        <div class="col-md-2 text-center">
            <img src="{{ asset('/storage/images/Inventory_Icon.png') }}">
        </div>
    </div>
</a>

<div class="row resources-bar-menu-item mt-3 contact-us">
    <div class="col-md-2 text-center">
        <img src="{{ asset('/storage/images/Question_Icon.png') }}">
    </div>
</div>

<div class="row resources-bar-menu-item mt-3 account-settings">
    <div class="col-md-2 text-center">
        <img src="{{ asset('/storage/images/settings_Icon.png') }}">
    </div>
</div>

<a href="{{ url('regions') }}" title="Regions">
    <div class="row resources-bar-menu-item mt-3 inventory">
        <div class="col-md-2 img-div">
            <img src="{{ asset('/storage/images/Region.png') }}">
        </div>
    </div>
</a>
