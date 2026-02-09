<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Dashboard')

<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <a href="{{ route('admin.project.view') }}"><span class="info-box-icon bg-aqua"><i
                                    class="fa fa-flag-o"></i></span></a>

                        <div class="info-box-content">
                            <span class="info-box-text">Projects</span>
                            <span class="info-box-number">{{ $projects }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <a href="{{ route('user.member') }}"><span class="info-box-icon bg-yellow"><i
                                    class="ion ion-ios-people"></i></span></a>

                        <div class="info-box-content">
                            <span class="info-box-text">Members</span>
                            <span class="info-box-number">{{ $members }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <a href="{{ route('user.sub') }}"><span class="info-box-icon bg-yellow"><i
                                    class="ion ion-ios-people-outline"></i></span></a>

                        <div class="info-box-content">
                            <span class="info-box-text">Sub Members</span>
                            <span class="info-box-number">{{ $subMembers }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <a href="{{ route('admin.job.info') }}"><span class="info-box-icon bg-aqua"><i
                                    class="fa fa-info-circle"></i></span></a>

                        <div class="info-box-content">
                            <span class="info-box-text">Job Information Sheets</span>
                            <span class="info-box-number">{{ $jobInfos }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <a href="{{ route('lien.list') }}"><span class="info-box-icon bg-yellow"><i
                                    class="fa fa-link"></i></span></a>

                        <div class="info-box-content">
                            <span class="info-box-text">Lien Providers</span>
                            <span class="info-box-number">{{ $lienProviders }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>
@endsection

@section('script')
    <!-- Morris.js charts -->
    <script src="{{ env('ASSET_URL') }}/admin/bower_components/raphael/raphael.min.js"></script>
    <script src="{{ env('ASSET_URL') }}/admin/bower_components/morris.js/morris.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ env('ASSET_URL') }}/admin/dist/js/pages/dashboard.js"></script>
@endsection
