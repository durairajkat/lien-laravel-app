@extends('user.main')

@section('title', 'Admin Login')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <h1 class="text-center login-title">Sign in to continue to NLB</h1>
                <div class="account-wall">
                    <img class="profile-img"
                        src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                        alt="">
                    <form class="form-signin" method="post" action="{{ route('post.login') }}">
                        <input type="text" value="{{ old('email') }}" name="email" class="form-control"
                            placeholder="Email/Username" required autofocus autocomplete="off">
                        <input type="password" name="password" class="form-control" placeholder="Password" required
                            autocomplete="off">
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
                        <label class="checkboxs">
                            <input type="checkbox" name="remember" value="remember-me">
                            Remember me
                        </label>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
