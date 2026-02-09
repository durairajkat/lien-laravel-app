<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Dynamic Title -->
    <title>
        NLB || @yield('title')
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet"
        href="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Date Picker -->
    <link rel="stylesheet"
        href="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet"
        href="{{ env('ASSET_URL') }}/admin_assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/newLogin.css">
    <link rel="shortcut icon" href="{{ env('ASSET_URL') }}/images/favicon.ico" />
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/sweetalert.min.css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/chosen_v1.8.3/chosen.min.css">
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link href="{{ env('ASSET_URL') }}/css/HoldOn.min.css" rel="stylesheet">
    <link href="{{ env('ASSET_URL') }}/admin_assets/jquery-ui-1.11.4/jquery-ui.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- Dynamic Css -->
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/style.css">
    @yield('style')
</head>
<!--Body -->

<body>
    <header>
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="logo">
                                    <a href="{{ env('ASSET_URL') }}">
                                        <img src="{{ env('ASSET_URL') }}/images/nlb.png" alt="img" title="NLB">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 header-top-right">
                                <span class="free-consultation">
                                    {{-- <a href="{{ route('member.get.consultation') }}">FREE CONSULTATION</a> --}}
                                </span>
                                <span class="phone">
                                    800.432.7799
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom">
            <div class="container">
                <div class="row">
                    @if (Auth::check() && Auth::user()->checkLienProvider())
                        <div class="col-md-9 col-sm-9">
                            <nav class="navbar navbar-default">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>
                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                    <ul class="nav navbar-nav">
                                        {{-- <li class="{{  (\Request::is('member'))? 'active': '' }}"><a href="{{ route('member.dashboard') }}">Home</a></li> --}}
                                        <li class="{{ \Request::is('lien') ? 'active' : '' }}"><a href="{{ route('lien.dashboard') }}">Dashboard</a></li>
                                        <li class="{{ \Request::is('lien/update/profile') ? 'active' : '' }}"><a href="{{ route('lien.update.profile') }}">Profile</a></li>
{{--                                        <li class="{{ \Request::is('lien/job-info') ? 'active' : '' }}"><a href="{{ route('lien.view.jobinfos') }}">Projects</a></li>--}}
                                        {{-- <li class="{{  (\Request::is('member/new-claim'))? 'active': '' }}"><a href="{{ route('member.create.project') }}">Start New Project</a></li>
                                        <li class="{{  (\Request::is('member/customer/contacts'))? 'active': '' }}"><a href="{{route('member.contacts.contacts')}}">My Contacts</a></li>
                                        <li class="{{  (\Request::is('member/customer/users'))? 'active': '' }}"><a href="{{ route('member.contacts.users') }}">Users</a></li>
                                        <li class="{{  (\Request::is('member/contact-us'))? 'active': '' }}"><a href="{{ route('member.contact.us') }}">Contact Us</a></li>
                                        <li><a href="#">Notifications <i class="fa fa-exclamation-circle" aria-hidden="true"></i></a>
                                        </li> --}}
                                    </ul>
                                </div>
                                <!-- /.navbar-collapse -->
                            </nav>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            Hello,
                            <a class="pro-log dropdown" href="{{ route('member.update.profile') }}">
                                @php($user = \App\User::find(Auth::user()->id))
                                @if ($user->details != '' && $user->details->image != '')
                                    <img src="{{ env('ASSET_URL') }}/image_logo/{{ $user->details->image }}" width="20px" height="20px" class="img-circle special-img">
                                @else
                                    <img src="{{ env('ASSET_URL') }}/images/avatar5.png" width="20px" height="20px" class="img-circle special-img">
                                @endif
                            </a>
                            {{ ucwords(Auth::user()->name) }}
                            <span class="dropdown-toggle" role="alert" id="dropdownMenuButton" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-cog" aria-hidden="true"></i>
                            </span>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('lien.update.profile') }}">Hello
                                    {{ ucwords(Auth::user()->name) }}</a>
                                <a class="dropdown-item" href="{{ route('lien.update.profile') }}">My Account</a>
                                <a class="dropdown-item" href="{{ route('lien.logout') }}">Log out</a>

                            </div>
                        </div>
                    @else
                        <div class="col-md-12 col-sm-12">
                            <p>Construction Lien Manager™ Log-in to Access Your Projects & Mechanic Lien Deadlines</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>
    <section class="bodypart">
        @if (session()->has('try-error'))
            <div class="alert alert-danger">
                {!! session('try-error') !!}
            </div>
        @endif
        <!--   @if (Session::has('success'))
        <p class="alert alert-success">{{ Session::get('success') }}</p>
    @endif -->
        @if (session()->has('date-error'))
            <div class="alert alert-danger">
                {!! session('date-error') !!}
            </div>
        @endif
        <div class="col-centered">
            @if (Session::has('success'))
                <p class="alert alert-success">{{ Session::get('success') }}</p>
            @endif
        </div>
        @yield('content')
    </section>
    <!-- Modal Section -->
    @yield('modal')
    <!-- jQuery 3 -->
    {{-- <script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script> --}}
    <script src="{{ env('ASSET_URL') }}/js/admin/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/jquery-ui-1.11.4/jquery-ui.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/sweetalert.min.js"></script>
    <!-- datepicker -->
    <script
        src="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
    </script>
    <script src="{{ env('ASSET_URL') }}/js/create_project.js"></script>
    <script src="{{ env('ASSET_URL') }}/chosen_v1.8.3/chosen.jquery.min.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/chosen-patch.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/HoldOn.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.date').datepicker({
                autoclose: true,
                format: 'mm/dd/yyyy'
            });

            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <!--Dynamic Javascript-->
    @yield('script')
    <!-- For Append Query String -->
</body>

</html>
