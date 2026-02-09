@extends('basicUser.layout.main')

@section('title', 'reset password')

@section('content')
    <div class="grey-section">
        <h3>Construction Lien Manager™ Reset Password</h3>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login">
                    <form class="form-signin" action="{{ route('post.password.reset') }}" method="post">
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password" required
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="cPassword" placeholder="Confirm password"
                                required autocomplete="off">
                        </div>
                        <input type="hidden" name="password_token" value="{{ $token }}">
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
                            Reset password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
