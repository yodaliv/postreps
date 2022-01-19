@extends('layouts.auth')

@section('content')
    <div class="container p-0">
        @include('layouts.includes.alerts')
    </div>
    <div class="container bg-white p-0 offices-page">
        <div class="row justify-content-center">
            <div class="col-md-12 position-relative">
                <ul class="nav nav-pills mb-3 ml-0" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-offices-tab" data-toggle="pill" href="#pills-offices"
                            role="tab">Offices</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " id="pills-agents-tab" data-toggle="pill" href="#pills-agents"
                            role="tab">Agents</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " id="pills-installers-tab" data-toggle="pill" href="#pills-installers"
                            role="tab">Installers</a>
                    </li>
                </ul>
                <div class="tab-content p-2" id="pills-tabContent">
                    @include('users.taps.offices')
                    @include('users.taps.agents')
                    @include('users.taps.installers')
                </div>
            </div>
        </div>
    </div>
    <div class="menu-bar pb-5 adjust-meun-bar ">
        @include('layouts.includes.account_resources')
    </div>
    {{-- models --}}
    @include('users.agent.create_modal')
    @include('users.agent.edit_modal')
    @include('users.agent.note_model')
    @include('users.agent.change_office_modal')
    @include('users.office.create_modal')
    @include('users.office.edit_modal')
    @include('users.office.note_modal')

@endsection

@section('page_scripts')
    <script>
        window.isHaveErrorCreateOfficeFormModel = {{ old('createOfficeForm') ? 1 : 0 }}
    </script>
    <script src="{{ mix('/js/user.js') }}" defer></script>
@endsection
