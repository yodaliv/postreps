@extends('layouts.auth')

@section('content')

    @include('service.zones')
    <div class="menu-bar pb-5 adjust-meun-bar ">
        @include('layouts.includes.account_resources')
    </div>

@endsection

@section('page_scripts')
    <script src="{{ mix('/js/service.js') }}" defer></script>


@endsection
