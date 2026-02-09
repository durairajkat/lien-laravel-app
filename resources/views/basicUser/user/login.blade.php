@extends('basicUser.layout.login_main')
@section('header')
@include('partials.header')
@endsection
@section('title', 'Member Login')
@section('content')
    <div class="container">
        <div class="col-md-6 col-sm-6">
            <div class="login-sec">
                <div class="logoSec">
                    <img src="{{ asset('images/LienManagerLogo.png') }}" />
                </div>
                <form method="post" action="{{ route('member.login.action') }}">
                    {{ csrf_field() }}

                    <div class="input-container form-group">
                        <i class="fa fa-user icon"></i>
                        <input class="input-field form-control" type="email" placeholder="Email Address" name="email"
                            value="{{ old('email') }}" required autofocus autocomplete="off">
                    </div>

                    <div class="input-container form-group">
                        <i class="fa fa-key icon"></i>
                        <input class="input-field form-control" type="password" placeholder="Password" name="password"
                            value="{{ old('email') }}" required autofocus autocomplete="off">
                    </div>


                    <div class="form-group-flex">
                    <div class="form-group">
                        <input type="checkbox" id="remember-me" name="remember" value="true">
                        <span class="remember-me" for="remember-me">Keep me logged in.</span>
                    </div>
                    <div class="form-group forgotPassword">
                        <a href="{{ route('get.forgetPassword') }}">Forgot Password</a>
                    </div>
                    </div>

                    <div class="form-group">
                        <input id="sign_in" type="submit" value="Login" class="btn sub-btn">
                        <a href="{{ route('member.basicSignup') }}" class="btn sub-btn">Sign Up</a>
                    </div>



                    <div class="form-group passwordReset">
                        <a href="{{ route('get.forgetPassword') }}">Request a Password Reset</a>
                    </div>

                    @if (Session::has('error'))
                        <p class="alert alert-danger">{{ Session::get('error') }}</p>
                    @endif
                </form>
            </div>
        </div>
        <br>
        <br>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.nonfocus').hide();
            $('.comapny').focus(function() {
                $('.nonfocus').slideToggle(function() {
                    return flase;
                });
            })
            $('.name').focus(function() {
                $('.nonfocus').slideToggle(function() {
                    return flase;
                });
            })
        });
    </script>
@endsection
@section('footer')
@include('partials.footer')
@endsection