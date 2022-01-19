<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="background-color: #134185cc;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Post Reps') }}</title>
    <!-- <title>Real Estate Sign Post Installation Service - Boise, Eagle, Nampa, Meridian, Emmett, Caldwell, Post Reps.</title> -->

    <!-- <meta name="Description" content="Real Estate Sign Post Installation, Removal, and Storage service. Installing across the Treasure Valley - Boise, Nampa, Caldwell, Eagle, Emmett, Garden Valley, Meridian. Post Reps and Postreps.com">
        <meta name="keywords" content="Sign Post Installation, Real Estate, For Sale Sign, Sign rental, signpost, home sale sign, Treasure Valley, Boise, Nampa, Caldwell, Emmett, Mountain Home, Kuna, Meridian. Post Reps and Postreps.com"> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/swiper.css') }}" rel="stylesheet">

    <noscript>
        <h3> You must have JavaScript enabled in order to use this website. Please
            enable JavaScript and then reload this page in order to continue.
        </h3>
        <meta HTTP-EQUIV="refresh" content=0; url="https://www.enable-javascript.com/">
    </noscript>

</head>

<body style="margin: auto; max-width: 1450px;">

    <div class="container-fluid" id="navbar">
        <div class="row" style="margin-right: 0;">
            <div class="col-md-2 col-3 pl-4 pt-4 pb-3 public-logo-div text-center hide-on-mobile">
                <a class="navbar-brand p-0 text-center" href="{{ url('/') }}">
                    <img src="{{ asset('/storage/images/logo.png') }}" alt="{{ config('app.name', 'Post Reps') }}">
                </a>
            </div>
            <div class="hide-on-desktop col-md-2 col-3 pl-3 pt-2 pb-2 public-logo-div text-center">
                <a class="navbar-brand p-0  text-center" href="{{ url('/') }}">
                    <img src="{{ asset('/storage/images/logo.png') }}" alt="{{ config('app.name', 'Post Reps') }}"
                        style="width: 80px;">
                </a>
            </div>
            <div class="col-md-10 col-9">
                <div class="row contact-icons hide-on-mobile">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-5 text-right">
                                <span class="icon-wrapper">
                                    <i class="far fa-envelope"></i>
                                </span>
                            </div>
                            <div class="col-md-7 text-left pl-0">
                                <span class="small-text">Email Address</span><br>
                                <span class="font-weight-bold txt-13">info@postreps.com</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="row">
                            <div class="col-md-5 text-right">
                                <span class="icon-wrapper">
                                    <i class="fas fa-phone-alt"></i>
                                </span>
                            </div>
                            <div class="col-md-7 text-left pl-0">
                                <span class="small-text">Phone</span><br>
                                <span class="font-weight-bold txt-13"> (208) 546-5546</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center pr-0">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="icon-wrapper" style="padding: 5px 12px 3px 11px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                            </div>
                            <div class="col-md-9 text-left pl-0">
                                <span class="small-text">Mailing Address</span><br>
                                <span class="font-weight-bold txt-13">PO BOX 1594 Nampa, ID. 83653</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-center pl-0">
                        <div class="row pl-0">
                            <div class="col-md-4 text-right pl-0">
                                <span class="icon-wrapper">
                                    <i class="far fa-clock"></i>
                                </span>
                            </div>
                            <div class="col-md-8 text-left pl-0">
                                <span class="small-text">Business Days</span><br>
                                <span class="font-weight-bold txt-13">Mon - Fri</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row public-menu-bar hide-on-mobile" style="margin-right: -16px;">
                    <div class="col-md-7 left-menu d-flex justify-content-between pl-5 pr-5">
                        <a class="font-weight-bold" href="{{ url('/') }}">HOME</a>
                        <a class="font-weight-bold" href="{{ url('/our-services') }}">SERVICES</a>
                        <a class="font-weight-bold" href="{{ url('/locations-served') }}">LOCATIONS</a>
                        <a class="font-weight-bold" href="{{ url('/contact') }}">CONTACT US</a>
                    </div>

                    <div class="col-md-5 right-menu d-flex justify-content-around pl-5 pr-5">
                        @guest
                            <a class="btn btn-orange btn-sm font-weight-bold ml-2" href="{{ url('/login') }}"
                                style="width: 160px;">LOGIN
                            </a>
                            <a class="btn btn-secondary btn-sm font-weight-bold ml-2" href="{{ url('/register') }}"
                                style="width: 160px;">CREATE ACCOUNT
                            </a>
                        @endguest
                        @auth
                            <a class="btn btn-orange btn-sm font-weight-bold ml-2" href="{{ url('/dashboard') }}"
                                style="width: 160px;">DASHBOARD
                            </a>
                            <form action="{{ route('logout') }}" method="post" id="logoutForm">
                                @csrf
                                <a onclick="document.getElementById('logoutForm').submit()" class="btn btn-secondary btn-sm font-weight-bold ml-2" style="width: 160px;">
                                    LOGOUT
                                </a>
                            </form>
                        @endauth

                    </div>
                </div>

                <div class="row public-menu-bar hide-on-desktop pb-2">
                    <div class="col-md-7 left-menu d-flex justify-content-between">
                        <a class="font-weight-bold" href="{{ url('/') }}">HOME</a>
                        <a class="font-weight-bold" href="{{ url('/our-services') }}">SERVICES</a>
                        <a class="font-weight-bold" href="{{ url('/locations-served') }}">LOCATIONS</a>
                        <a class="font-weight-bold" href="{{ url('/contact') }}">CONTACT US</a>
                    </div>

                    <div class="col-md-5 right-menu d-flex justify-content-around pt-0 pl-2 pr-2">
                        @guest
                            <a class="btn btn-orange btn-sm font-weight-bold ml-2" href="{{ url('/login') }}"
                                style="width: 160px;">LOGIN
                            </a>
                            <a class="btn btn-secondary btn-sm font-weight-bold ml-2" href="{{ url('/register') }}"
                                style="width: 160px;">CREATE ACCOUNT
                            </a>
                        @endguest
                        @auth
                            <form action="{{ route('logout') }}" method="post" id="logoutForm">
                                @csrf
                                <a onclick="document.getElementById('logoutForm').submit()" class="btn btn-secondary btn-sm font-weight-bold ml-2" style="width: 160px;">
                                    LOGOUT
                                </a>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @yield('content')
    </div>

    <div class="container-fluid mt-5 pt-4">
        <footer class="py-5 px-5">
            <div class="row text-white">
                <div class="col-md-4 text-center">
                    &copy {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.
                </div>
                <div class="col-md-4 text-center">
                    <a class="text-white" href="https://www.facebook.com/PostReps/" target="_blank">
                        <i class="fab fa-facebook-square" style="font-size: 22px"></i>
                    </a>
                </div>
                <div class="col-md-4 text-center">
                    <a href="{{ url('/terms') }}" class="text-white">Terms & Conditions</a>
                    <a href="{{ url('/privacy') }}" class="ml-4 text-white">Privacy Policy</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- login modal -->
    <!-- <div class="modal fade mt-5" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content mt-5">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="loginModalLabel">Login</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right text-white">EMAIL ADDRESS</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right  text-white">PASSWORD</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label text-white" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-orange font-weight-bold text-white" id="loginBtn">
                                        SIGN IN
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link text-white" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> -->

    <!-- forgot password modal -->
    <!-- <div class="modal fade mt-5" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content mt-5">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="loginModalLabel">Forgot Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div> -->

    <!-- Scripts -->
    <script src="{{ mix('/js/manifest.js') }}" defer></script>
    <script src="{{ mix('/js/vendor.js') }}" defer></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <script src="{{ mix('/js/public.js') }}" defer></script>
    <script src="{{ asset('js/swiper.js') }}"></script>

    @yield('page_scripts')

    @include('layouts.includes.error_modal')
    @include('layouts.includes.confirm_modal')

</body>

</html>
