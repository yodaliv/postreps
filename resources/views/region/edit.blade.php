@extends('layouts.auth')

@section('content')
    <div class="container">
        @include('layouts.includes.alerts')

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card auth-card">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mt-2">
                            <a href="{{url('/regions')}}">Regions</a>
                             / Edit
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('regions', $region->id)}}">
                            @csrf
                            @method('PUT')
                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Region Name</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            name="name"
                                            id="name"
                                            required
                                            value="{{$region->name}}"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <select class="form-control" type="text" name="state" id="state" required>
                                            <option value=""></option>
                                            @if(count($states))
                                                @foreach ($states as $code => $state)
                                                    <option
                                                        value="{{$code}}"
                                                        {{$code == $region->state ? 'selected' : ''}}
                                                    >{{$state}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="menu-bar pb-5 adjust-meun-bar ">
        @include('layouts.includes.account_resources')
    </div>
@endsection
