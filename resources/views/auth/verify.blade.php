@extends('layouts.public')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card public-card">
                <div class="card-header"><h5 class="text-white">{{ __('Verify Your Email Address') }}</h5></div>

                <div class="card-body" style="font-size:15px;">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline" style="font-size:15px; color: rgb(48, 48, 177);">
                            {{ __('click here to request another') }}
                        </button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="footer-separator"></div>
<div class="footer-separator"></div>
<div class="footer-separator"></div>
@endsection
