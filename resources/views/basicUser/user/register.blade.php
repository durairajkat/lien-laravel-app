@extends('basicUser.layout.main')

@section('title', 'Register')

@section('content')
    <div class="grey-section">
        <h3>Construction Lien Manager™ Trial Membership Registration</h3>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login">
                    <form class="form-signin" action="{{ route('post.register') }}" method="post">
                        {{-- <div class="form-group">
                            <input type="text" class="form-control" name="companyName" value="{{ old('companyName') }}"
                                placeholder="Company name" required autofocus autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="firstName" value="{{ old('firstName') }}"
                                placeholder="First name" required autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="lastName" value="{{ old('lastName') }}"
                                placeholder="Last name" required autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <textarea name="address" class="form-control" placeholder="Address" required
                                autocomplete="off">{{ old('address') }}</textarea>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="city" value="{{ old('city') }}"
                                placeholder="City" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <select name="state" class="form-control" required>
                                <option value="">Select a State</option>
                                @foreach ($states as $key => $state)
                                    <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="zip" value="{{ old('zip') }}" placeholder="Zip"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                placeholder="Contact phone" required autocomplete="off">
                        </div> --}}
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" value="{{ $email }}"
                                placeholder="Email address" required autocomplete="off">
                        </div>
                        {{-- <div class="form-group">
                            <input type="text" class="form-control" name="userName" value="{{ old('userName') }}"
                                placeholder="User name" required autocomplete="off">
                        </div> --}}
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password"
                                value="{{ $pass }}" required autocomplete="off">
                        </div>
                        {{-- <div class="form-group">
                            <input type="password" class="form-control" name="cPassword" placeholder="Confirm password"
                                required autocomplete="off">
                        </div> --}}
                        {{ csrf_field() }}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Session::has('error'))
                            <p class="alert alert-danger">{{ Session::get('error') }}</p>
                        @endif
                        <button class="btn btn-lg btn-primary btn-block" type="submit">
                            Sign up
                        </button>
                    </form>
                </div>
                <p>Already have an account ? <a href="{{ route('member.login') }}"
                        style="color:#3366ff;font-weight:bold;">Login</a>
                </p>
            </div>
        </div>
    </div>
@endsection
