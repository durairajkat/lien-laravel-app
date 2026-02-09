<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Dynamic Title -->
    <title>
        NLB || @yield('title')
    </title>
    <!-- JUST FOR DEV, REMOVE CACHE -->
    <!--REMOVE                            -->
    <meta http-equiv=“Pragma” content=”no-cache”>
    <meta http-equiv=“Expires” content=”-1″>
    <meta http-equiv=“CACHE-CONTROL” content=”NO-CACHE”>
    <!--REMOVE                            -->

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet"
        href="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Ionicons -->
    <link rel="stylesheet"
        href="{{ env('ASSET_URL') }}/admin_assets/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/admin_assets/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
  folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/admin_assets/dist/css/skins/_all-skins.min.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/admin_assets/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet"
        href="{{ env('ASSET_URL') }}/admin_assets/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Date Picker -->
    <link rel="stylesheet"
        href="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet"
        href="{{ env('ASSET_URL') }}/admin_assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/sweetalert.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="{{ env('ASSET_URL') }}/css/HoldOn.min.css" rel="stylesheet">
    <link href="{{ env('ASSET_URL') }}/admin_assets/jquery-ui-1.11.4/jquery-ui.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/chosen_v1.8.3/chosen.min.css">
    <style>
        /******  for jquery autocomplete  ******/
        .ui-autocomplete {
            z-index: 2147483647;
        }

    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <link rel="shortcut icon" href="{{ env('ASSET_URL') }}/images/favicon.ico" />
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- Dynamic Css -->
    @yield('style')

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

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <!--Top nav bar -->
        @include('layout.topnav')
        <!--Side nav bar -->
        @include('layout.sidenav')
        <!-- Content Wrapper. Contains page content -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @if (session()->has('try-error'))
                    <div class="alert alert-danger">
                        {!! session('try-error') !!}
                    </div>
                @endif
            </div>
        </div>

        @yield('content')
        <!-- Footer -->
        @include('layout.footer')
        <!-- /.control-sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->
    <!-- Modal Section -->
    @yield('modal')
    <!-- jQuery 3 -->
    {{-- <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery/dist/jquery.min.js"></script> --}}
    {{-- <script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous"></script> --}}
    <script src="{{ env('ASSET_URL') }}/js/admin/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
        /** add active class and stay opened when selected */
        var url = window.location;

        // for sidebar menu entirely but not cover treeview
        $('ul.sidebar-menu a').filter(function() {
            return this.href == url;
        }).parent().addClass('active');

        // for treeview
        $('ul.treeview-menu a').filter(function() {
            return this.href == url;
        }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
    </script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Sparkline -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js">
    </script>
    <!-- jvectormap -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="{{ env('ASSET_URL') }}/admin_assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/moment/min/moment.min.js"></script>
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap-daterangepicker/daterangepicker.js">
    </script>
    <!-- datepicker -->
    <script
        src="{{ env('ASSET_URL') }}/admin_assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
    </script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js">
    </script>
    <!-- Slimscroll -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js">
    </script>
    <!-- FastClick -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/dist/js/adminlte.min.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/sweetalert.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/dist/js/demo.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/HoldOn.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ env('ASSET_URL') }}/chosen_v1.8.3/chosen.jquery.min.js"></script>
    <!--Dynamic Javascript-->
    @yield('script')
    <!-- For Append Query String -->
    <script>
        appendToQueryString = function(param, val) {
            var queryString = window.location.search.replace("?", "");
            var parameterListRaw = queryString == "" ? [] : queryString.split("&");
            var parameterList = {};
            for (var i = 0; i < parameterListRaw.length; i++) {
                var parameter = parameterListRaw[i].split("=");
                parameterList[parameter[0]] = parameter[1];
            }
            parameterList[param] = val;

            var newQueryString = "?";
            for (var item in parameterList) {
                if (parameterList.hasOwnProperty(item)) {
                    newQueryString += item + "=" + parameterList[item] + "&";
                }
            }
            newQueryString = newQueryString.replace(/&$/, "");
            return location.origin + location.pathname + newQueryString;
        }
    </script>
</body>

</html>
