<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <base href="/lien/application/" />
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

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '317272046700723');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=317272046700723&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->
</head>
<!--Body -->

<body>
    <style type="text/css">
        /*header {
            display: none;
        }*/

        body {
            background: #f3f7fa;
        }

        section.bodypart {
            margin-top: 0px;
        }

        .mainHolder {
            background: #3a86f8;
            float: left;
            width: 100%;
        }

        .leftSec {
            float: left;
            width: 20%;
            height: 100%;
            position: fixed;
            background: #3a86f8;
        }

        .leftInner {
            padding: 40px 20px;
        }

        .leftInner .logo img {
            width: 90%;
        }

        .leftInner ul.navbar-nav {
            width: 100%;
            margin-top: 60px;
        }

        .leftInner ul.navbar-nav li {
            width: 100%;
            float: left;
            color: #fff;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .leftInner ul.navbar-nav li a i.fa-angle-down {
            float: right;
        }

        .leftInner .navbar-nav>li>a {
            border-bottom: 0 solid #fff;
            color: #fff;
            font-size: 15px;
            padding: 10px 8px 10px 0px;
            text-transform: none;
            margin-bottom: 0px;
        }

        .leftInner .navbar-nav>li>a:hover {
            border-bottom: 0 solid #fff;
        }

        .bottomMenu {
            position: relative;
            bottom: 0px;
            margin-top: 60px;
        }

        .forgotPassword {
            text-align: center;
            color: #1084ff
        }

        .passwoReset {
            text-align: center;
        }

        .mainSec {
            float: right;
            width: 100%;
            background: #f3f7fa;
            padding-top: 30px;
        }

        .rightSec .container-wide {
            width: 100%;
        }

        .rightSec .project-table-header-button,
        .rightSec .project-table-header-button:hover {
            background: #fff;
            color: #000;
            box-shadow: 2px 5px 5px #e7f0f9;
            padding: 10px 20px;
            height: auto;
        }

        .rightSec .white-table thead tr th {
            color: #959cae;
        }

        .rightSec .white-table .table>tbody>tr>td {
            border-bottom: 1px solid #fff;
        }

        #tooManyClasses tr th {
            background: #fff;
            border: 1px solid #fff;
        }

        tr:nth-child(even) {
            background: #fff;
            border: 0px;
        }

        tr:nth-child(odd) {
            background: #f8f8f8;
            border: 0px;
        }

        .welcomeTxt {
            font-size: 16px;
            line-height: 20px;
            margin-bottom: 20px;
            margin-top: 60px;
        }

        .logoSec {
            margin-top: 30%;
            text-align: center;
        }

        .copyRights {
            text-align: center;
            color: #b3b3b3;
            font-size: 10px;
        }

        .remember-me {
            font-size: 12px;
        }

        .login-sec a {
            color: #1084ff;
        }

        .col-md-6,
        .col-sm-6 {
            margin-left: 25%;
            padding: 0 7%;
        }

        .login-sec .form-group .form-control {
            background: #fff;
            border-radius: 0px;
            padding-right: 10px;
        }
        .form-group-flex {
            display:flex;
            justify-content: space-between;
        }
        .passwordReset {
            text-align:center;
        }
        .passwordReset>a{
            color:black;
            
        }

        .login-sec .form-group .form-control::-webkit-input-placeholder {
            color: #b3b3b3;
        }

        .login-sec .form-group .form-control::-moz-placeholder {
            color: #b3b3b3;
        }

        .login-sec .form-group .form-control:-ms-input-placeholder {
            color: #b3b3b3;
        }

        .login-sec .form-group .form-control:-moz-placeholder {
            color: #b3b3b3;
        }


        #sign_in {
            width: 100%;
        }

        #request_pass {
            background: #fff;
            border: 0.5px solid #c1c1c1;
            color: #4e4c4c;
            font-size: 11px;
            padding: 10px 0px;
            width: 100%;
        }

        .mob {
            display: none;
        }

        @media only screen and (max-width: 600px) {
            .leftInner {
                padding: 20px 5px;
            }

            .navbar-nav li span {
                display: none;
            }

            .navbar-nav {
                padding-left: 20px;
            }

            .desk {
                display: none;
            }

            .mob {
                display: block !important;
            }
        }

        @media only screen and (min-device-width: 320px) and (max-device-width: 480px) and (orientation: portrait) {

            .col-md-6,
            .col-sm-6 {
                margin: 10%;
                padding: 0 7%;
            }
        }

        @media only screen and (min-width: 992px) {
            .col-md-8 {
                width: 100%;
            }
        }

        @media only screen and (min-width: 768px) {
            .col-sm-offset-2 {
                text-align: center;
                margin-left: 0;
            }
        }

        .input-line {
            display: flex;
        }

        .input-container {
            display: -ms-flexbox;
            /* IE10 */
            display: flex;
            width: 100%;
            /* margin-bottom: 15px; */
            border-radius: 0px;
        }

        .signup-heading {
            color: #00A1E9;
            text-align: center;
            font: inherit !important;
            font-size: 20px !important;
            font-weight: 1000 !important;
        }

        .input-signup {
            margin-right: 7px;
            padding: 7px !important;
        }

        .login-sec .input-container .form-control {
            border: none;
            padding: 5px;
        }

        .icon {
            padding: 10px;
            background: white;
            color: #b4b4b4;
            min-width: 40px;
            text-align: center;
        }

        .input-field {
            width: 100%;
            padding: 10px;
        }

        .input-field:focus {
            border: 2px solid dodgerblue;
        }
        
        html, body {
  height: 100%;
}

body {
  display: flex;
  flex-direction: column;
  min-height: 100vh;  /* ensures full viewport height */
}

section.bodypart {
  flex: 1;            /* this makes the middle content expand */
}

/* optional: prevent footer overlap if it's fixed height */
footer, .lm-footer {
  flex-shrink: 0;
}


    </style>
    @yield('header')
    <section class="bodypart">
        <div class="mainHolder">
            <div class="mainSec">
                @yield('content')
            </div>
        </div>
    </section>
    <!-- Modal Section -->
    @yield('modal')
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
    <script src="{{ env('ASSET_URL') }}/vine/common/vine/vine.js?r=<?php echo time(); ?>"></script>

    <!--Dynamic Javascript-->
    @yield('script')
    <!-- For Append Query String -->
    @yield('footer')
</body>

</html>
