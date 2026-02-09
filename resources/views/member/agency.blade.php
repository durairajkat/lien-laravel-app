<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Agent list')
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Members
                <small>Agency</small>
            </h1>
            <ol class="breadcrumb">
                <button class="btn btn-md btn-success" type="button" data-toggle="modal" data-target="#addAgency">Add
                    new agency
                </button>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of all agency</h3>

                            <div class="box-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control pull-right"
                                        placeholder="Search">

                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>User name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                @if (count($users) > 0)
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->user_name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <button class="btn btn-info btn-xs" type="button" title="Edit user"><i
                                                        class="fa fa-pencil"></i></button>
                                                <button class="btn btn-warning btn-xs" type="button"
                                                    title="Change password"><i class="fa fa-unlock"></i></button>
                                                <button class="btn btn-danger btn-xs" type="button" title="Delete user">
                                                    <i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No agency available.For add agency click <a
                                                href="javascript:void(0)" data-toggle="modal"
                                                data-target="#addAgency">here</a></td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{ $users->links() }}
                            </ul>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
@endsection

@section('modal')
    <!-- Modal -->
    <div id="addAgency" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add agency</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="name" placeholder="Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="user_name" class="col-sm-2 control-label">User name</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="user_name" placeholder="User name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control error" id="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Password</label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control error" id="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cPassword" class="col-sm-2 control-label">Confirm password</label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control error" id="cPassword"
                                        placeholder="Confirm password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group error-tag-field" style="display: none;">
                            <label for="error" class="col-sm-2 control-label">Error</label>

                            <div class="col-sm-10">
                                <span id="error-field" style="color: red;"></span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="button" class="btn btn-info pull-right" id="addAgencyButton"><i
                                    class="fa fa-spinner fa-spin loader" style="display: none;"></i>
                                Sign in
                            </button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#addAgencyButton').on('click', function() {
                $('.help-block').replaceWith('');
                var name = $('#name').val();
                if (name == '') {
                    $('#name').parent().parent('div').addClass('has-error');
                    $('#name').parent('div').append('<span class="help-block">Please enter name</span>');
                    return false;
                }
                var user_name = $('#user_name').val();
                if (user_name == '') {
                    $('#user_name').parent().parent('div').addClass('has-error');
                    $('#user_name').parent('div').append(
                        '<span class="help-block">Please enter user name</span>');
                    return false;
                }
                var email = $('#email').val();
                if (email == '') {
                    $('#email').parent().parent('div').addClass('has-error');
                    $('#email').parent('div').append('<span class="help-block">Please enter email</span>');
                    return false;
                }
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if (!emailReg.test(email)) {
                    $('#email').parent().parent('div').addClass('has-error');
                    $('#email').parent('div').append(
                    '<span class="help-block">Please a valid email</span>');
                    return false;
                }
                var password = $('#password').val();
                if (password == '') {
                    $('#password').parent().parent('div').addClass('has-error');
                    $('#password').parent('div').append(
                        '<span class="help-block">Please enter Password</span>');
                    return false;
                }
                var cPassword = $('#cPassword').val();
                if (cPassword == '') {
                    $('#cPassword').parent().parent('div').addClass('has-error');
                    $('#cPassword').parent('div').append(
                        '<span class="help-block">Please enter confirm Password</span>');
                    return false;
                }
                if (password != cPassword) {
                    $('#cPassword').parent().parent('div').addClass('has-error');
                    $('#cPassword').parent('div').append(
                        '<span class="help-block">Password and confirm Password does not match</span>');
                    return false;
                }
                if (password < 6) {
                    $('#Password').parent().parent('div').addClass('has-error');
                    $('#Password').parent('div').append(
                        '<span class="help-block">please choose a more secure password. it should be longer than 6 characters</span>'
                        );
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.add.agency') }}",
                    data: {
                        name: name,
                        user_name: user_name,
                        email: email,
                        password: password,
                        cPassword: cPassword,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('.loader').show();
                    },
                    complete: function() {
                        $('.loader').hide();
                    },
                    success: function(data) {
                        if (data.status == true) {
                            window.location.reload();
                        } else {
                            $('#error-field').text(data.message);
                            $('.error-tag-field').show();
                        }

                    }
                });
            });
            $('.error').on('keyup', function() {
                $(this).parent().parent('div').removeClass('has-error');
                $(this).parent('div').children('.help-block').remove();
                $('.error-tag-field').hide();
            });
        });
    </script>
@endsection
