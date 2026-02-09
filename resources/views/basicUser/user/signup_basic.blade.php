@extends('basicUser.layout.login_main')
@section('title', 'Member Basic Signup')
@section('content')
    <style>
        @media only screen and (max-width: 768px) {

            /* For mobile phones: */
            [class*="col-"] {
                width: 100%;
            }
        }

    </style>
    <div class="container">
        <div class="col-md-6 col-sm-6">
            <div class="login-sec">
                <div class="logoSec">
                    <img src="{{ asset('images/LienManagerLogo.png') }}" />
                </div>
                <form class="form-signin" action="{{ route('post.register') }}" method="post">
                    {{ csrf_field() }}
                    <h2 class="signup-heading">Basic Package - Sign Up</h2>

                    {{--     <div class="input-container form-group">
                            <input type="text" class="input-signup form-control comapny" name="companyName"
                                value="{{ old('companyName') }}" placeholder="Enter Company name" required autofocus
                                autocomplete="off" />
                        </div>

                    <div class="input-container form-group">
                        <input type="text" class="input-signup form-control name" name="firstName"
                            value="{{ old('firstName') }}" placeholder="Enter First name" required autocomplete="off" />
                    </div>
                    <div class="input-container form-group">
                        <input type="text" class="input-signup form-control" name="lastName" value="{{ old('lastName') }}"
                            placeholder="Enter Last name" required autocomplete="off" />
                    </div> --}}
                    <div class="focus">
                    <div class="input-line">
                        <div class="input-container form-group">
                            <input type="text" class="input-signup form-control" name="email" value=""
                                placeholder="Email address" required autocomplete="off">
                        </div>
                      </div>

                    </div>
                    {{-- <div class="input-line">
                        <div class="input-group" style="margin-bottom: 10px; width: 98%;">
                            <span class="input-group-addon border-0">+1</span>
                            <input type="text" class="input-signup form-control border-0" name="phone"
                                placeholder="Enter Contact Number" pattern="[0-9]{1}[0-9]{9}" value="{{ old('phone') }}">

                        </div>
                    </div>

                    <div class="input-line">

                        <div class="input-container form-group">
                            <select name="state" class="input-signup form-control" required>
                                <option value="">Select a State</option>
                                @foreach ($states as $key => $state)
                                    <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="input-container form-group">
                        <input type="password" class="input-signup form-control" name="password"
                            placeholder="Enter Password" value="" required autocomplete="off">
                    </div>
                    {{-- <div class="input-container form-group">
                        <input type="password" class="input-signup form-control" name="confirmPassword"
                            placeholder="Enter Confirm password" required autocomplete="off">
                    </div> --}}
                    <div>Beta Access: You’re joining the Lien Manager Beta.<br>Enjoy early access now and get an exclusive launch discount later.</div>

                    <input type="hidden" name="plan_type" value="basic" required autocomplete="off">
                    <div class="form-group">
                        <input id="" type="submit" value="START MY TRIAL" class="btn sub-btn">
                    </div>
                    @if (Session::has('error'))
                        <p class="alert alert-danger">{{ Session::get('error') }}</p>
                    @endif
                    @if ($errors->any())

                        @foreach ($errors->all() as $error)
                            <p class="alert alert-danger"> {{ $error }}</p>
                        @endforeach

                    @endif
                </form>
            </div>
            <br>
            <br>
        </div>
    </div>
@endsection
