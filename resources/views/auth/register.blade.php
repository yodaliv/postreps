@extends('layouts.public')

@section('content')
    <div class="p-5">
        @include('layouts.includes.alerts')

        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="card my-2 public-card">
                    <div class="card-header">
                        <h5 class="text-white">Register</h5>
                    </div>
                    <div class="card-body  ">
                        <form id="agentRegistrationForm" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row mt-1 ">
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="first_name">FIRST NAME</label>
                                            <input type="text" id="first_name" error-class='bg-dark'
                                                required-error-msg="First name is required." required tabindex='1'
                                                class="form-control  @error('first_name') is-invalid @enderror"
                                                name="first_name" value="{{ old('first_name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="last_name">LAST NAME</label>
                                            <input type="text" id="last_name" tabindex='2' error-class='bg-dark'
                                                required-error-msg="Last name is required." required
                                                class="form-control  @error('last_name') is-invalid @enderror"
                                                name="last_name" value="{{ old('last_name') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="password">PASSWORD</label>
                                            <input type="password" id="password" tabindex='3' error-class='bg-dark'
                                                required-error-msg="Password is required."
                                                match-error-msg="Password does not match."
                                                class="form-control  @error('password') is-invalid @enderror"
                                                name="password" value="{{ old('password') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="password_confirmation">CONFIRM PASSWORD</label>
                                            <input type="password" id="password_confirmation" tabindex='4'
                                                validation-match='password' required-error-msg="Confirm password is required."
                                                match-error-msg="Confirm password does not match password." error-class='bg-dark'
                                                class="form-control  @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" value="{{ old('password_confirmation') }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="address">STREET ADDRESS</label>
                                            <input type="text" id="street_address" tabindex='5' error-class='bg-dark'
                                                required-error-msg="Street address is required."
                                                class="form-control  @error('address') is-invalid @enderror" placeholder=""
                                                name="address" required value="{{ old('address') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="email">EMAIL</label>
                                            <input type="email" id="email" tabindex='9' error-class='bg-dark'
                                                required-error-msg="Email is required."
                                                class="form-control @error('email') is-invalid @enderror " placeholder=""
                                                name="email" required value="{{ old('email') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="city">CITY</label>
                                            <input type="text" id="city" tabindex='6' error-class='bg-dark'
                                                required-error-msg="City is required."
                                                class="form-control  @error('city') is-invalid @enderror" placeholder=""
                                                name="city" required value="{{ old('city') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6">

                                    <div class="form-group">
                                        <div>
                                            <label for="phone">PHONE NUMBER</label>
                                            <input type="text" id="phone" tabindex='10' error-class='bg-dark'
                                                required-error-msg="Phone is required."
                                                class="form-control  @error('phone') is-invalid @enderror phones"
                                                name="phone" required value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-12 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <div>
                                            <label for="state">STATE</label>
                                            <select id="state" required tabindex='7' error-class='bg-dark'
                                                required-error-msg="State is required."
                                                class="form-control  @error('state') is-invalid @enderror" placeholder=""
                                                name="state">
                                                <option value=""></option>
                                                @if (count($states))
                                                    @foreach ($states as $code => $state)
                                                        <option value="{{ $code }}" @if (old('state') === $code or $code === 'ID') selected @endif>
                                                            {{ $state }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <div>
                                            <label for="zipcode">ZIPCODE</label>
                                            <input type="text" id="zipcode" tabindex='8' error-class='bg-dark'
                                                required-error-msg="Zipcode is required."
                                                class="form-control @error('zipcode') is-invalid @enderror zipcode"
                                                name="zipcode" value="{{ old('zipcode') }}" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div>
                                            <label for="re_license">RE LICENSE #</label>
                                            <input type="text" id="re_license" tabindex='10'
                                                class="form-control @error('re_license') is-invalid @enderror "
                                                name="re_license" value="{{ old('re_license') }}" />
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="agent_office">
                            </div>
                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <button type="button" id="continue"
                                        class="btn btn-orange font-weight-bold text-white">CONTINUE</button>
                                </div>
                            </div>
                            @include('auth.select_office_modal')
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>


@endsection


@section('page_scripts')
    <script src="{{ mix('js/register.js') }}" defer></script>
@endsection
