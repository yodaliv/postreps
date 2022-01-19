<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Post Reps') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <noscript>
        <h3> You must have JavaScript enabled in order to use this website. Please
            enable JavaScript and then reload this page in order to continue.
        </h3>
        <meta HTTP-EQUIV="refresh" content=0; url="https://www.enable-javascript.com/">
    </noscript>

</head>
<body class="blue-background">
    <div class="loading-overlay d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p><strong>Proccessing...</strong></p>
    </div>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark justify-content-between">
            <div class="container">
                <ul class="navbar-nav ml-auto desktop-view tablet-view">
                    <li class="nav-item">
                        <a class="btn btn-secondary font-weight-bold" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" style="width: 120px;">
                            LOGOUT
                        </a><br>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <a class="btn btn-orange font-weight-bold mt-2" href="{{ url('/dashboard') }}"
                            style="width: 120px;">DASHBOARD</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-0 mr-0 mobile-view">
                    <a class="navbar-brand p-0" href="{{ url('/') }}">
                        <img src="{{ asset('/storage/images/logo.png') }}"
                            alt="{{ config('app.name', 'Post Reps') }}" style="width: 80px;">
                    </a>
                </ul>

                <ul class="navbar-nav ml-0 mobile-view">
                    <li class="nav-item">
                        <a class="btn btn-secondary font-weight-bold" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" style="width: 120px;">
                            LOGOUT
                        </a>
                        <a class="btn btn-orange btn-sm font-weight-bold ml-2"
                            href="{{ url('/dashboard') }}">DASHBOARD</a>
                    </li>
                </ul>

                <button class="navbar-toggler ml-1" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end ml-5 pl-5" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav pl-2 desktop-view tablet-view">
                        <li>
                            <h5 class="text-white">
                                Welcome {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                            </h5>
                        </li>
                    </ul>

                    <div class="navbar-nav pl-2 mobile-view text-white w-100"
                        style="background-color: #134185; font-size: 13px; z-index: 2147483647; position:fixed">
                        <ul class="navbar-nav">
                            <li class="pb-2 text-center pl-5">
                                <span id="mobileAccountResourcesMenu">
                                    <h5 class="pl-4">X</h5>
                                </span>
                            </li>
                            <li class="pb-2">
                                ORDER STATUS
                            </li>
                            <li class="pb-2">
                                FINANCIAL RECORDS
                            </li>
                            <li class="pb-2">
                                <a href="{{ route('services.index') }}" class="text-white" title="services">
                                    SERVICE SETTINGS
                                </a>

                            </li>
                            <li class="pb-2">
                                <a href="{{ route('users.index') }}" class="text-white" title="users">
                                    USERS
                                </a>
                            </li>
                            <li class="pb-2">
                                CONTACT US
                            </li>
                            <li class="pb-2">
                                ACCOUNT SETTINGS
                            </li>
                            <li class="pb-2">
                                <a href="{{ route('inventories.index') }}" class="text-white"
                                    title="inventories">
                                    INVENTORY
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto mr-0 desktop-view tablet-view">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <img src="{{ asset('/storage/images/logo.png') }}"
                                alt="{{ config('app.name', 'Post Reps') }}" style="width: 150px;">
                        </a>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- <div
            class="container mobile-view py-1 px-1"
            style="background-color: #FF9047; color: rgba(17, 15, 15, 0.911)"
        >
            <div class="order-bar-mobile d-flex justify-content-between">
                <a class="btn btn-success btn-sm px-2" href="">INSTALLATION</a>
                <a class="btn btn-primary btn-sm px-2" href="">REPAIR</a>
                <a class="btn btn-danger btn-sm px-2" href="">REMOVAL</a>
                <a class="btn btn-info btn-sm px-2" href="">DELIVERY/PICKUP</a>
            </div>
        </div> -->

        <main class="py-4 px-0">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('/js/manifest.js') }}" defer></script>
    <script src="{{ mix('/js/vendor.js') }}" defer></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <script src="{{ mix('/js/dashboard.js') }}" defer></script>

    @yield('page_scripts')

    @include('layouts.includes.error_modal')

    @include('layouts.includes.message_model')

    @include('inventory.accessory.document_modal')

    @include('layouts.includes.confirm_modal')

</body>

</html>
