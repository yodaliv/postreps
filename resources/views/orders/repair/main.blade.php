@extends('layouts.auth')

@section('content')
    <div class="container p-0">
        @include('layouts.includes.alerts')
    </div>
    <div class="container-fluid pl-4 mt-1 pr-4 desktop-view hide-on-tablet">
        <div class="row ">
            <div class="col-md-3 menu-bar-single pb-3">
                @include('orders.repair.order_bar_repair')
            </div>
            <div class="col-md-7">
                <div class="card auth-card">
                        @include('orders.repair.card_header')
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('orders.repair.order_table')

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 menu-bar pb-5">
                @include('layouts.includes.account_resources')
            </div>
        </div>
    </div>

    <div class="container-fluid pl-4 mt-1 pr-4 tablet-view">
        <div class="row ">
            <div class="col-md-3 menu-bar pb-3">
                @include('orders.repair.order_bar_repair')
            </div>
            <div class="col-md-8">
                <div class="card auth-card">
                    @include('orders.repair.card_header')
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('orders.repair.order_table')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 menu-bar text-center pb-5" style="padding-left: 2vw;">
                @include('layouts.includes.account_resources_tablet')
            </div>
        </div>
    </div>

    <div class="container-fluid pl-4 mt-2 pr-4 mobile-view">
        <div class="row ">
            <div class="col-1"></div>
            <div class="col-10 menu-bar pb-3">
                <div class="card auth-card">
                    @include('orders.repair.card_header')
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('orders.repair.order_table')

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
    @include('orders.repair.order_modal');
@endsection

@section('page_scripts')
    <script src="{{ mix('/js/repair-order.js') }}" defer></script>
@endsection