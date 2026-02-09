<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Sub User')
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Members
                <small>Sub User</small>
            </h1>
            <div class="col-md-4 input-group input-group pull-right" style="display: flex">
                <input id="tier_search" value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}"
                       class="form-control pull-right"
                       placeholder="Enter Email/User Name/Name" type="text">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default search"><i  class="fa fa-search"></i></button>
                </div>
                <button   style="margin-left: 50px" class="btn btn-md btn-success subUser" data-type="Add" type="button" data-toggle="modal">Add new
                    Sub User
                </button>
            </div>

        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of all Sub users</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>Company name</th>
                                    <th>Company phone number</th>
                                    <th>Name</th>
                                    <th>User name</th>
                                    <th>User phone number</th>
                                    <th>Email</th>
                                    <th>Member</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                @if (count($users) > 0)
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ !is_null($user->mapcompanyContacts) && !is_null($user->mapcompanyContacts->company) ? $user->mapcompanyContacts->company->company : 'N/A' }}
                                            </td>
                                            <td>{{ !is_null($user->mapcompanyContacts) && !is_null($user->mapcompanyContacts->phone) ? $user->mapcompanyContacts->phone : 'N/A' }}
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->user_name }}</td>
                                            <td>{{ !is_null($user->details) && !is_null($user->details->company) ? $user->details->phone : 'N/A' }}
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->parent_id }}</td>
                                            <td>
                                                <input type="checkbox" class="status" data-id="{{ $user->id }}"
                                                    data-status="{{ $user->status }}"
                                                    {{ $user->status == '0' ? 'checked' : '' }} data-toggle="toggle"
                                                    data-on="Active" data-off="Inactive" data-onstyle="success"
                                                    data-offstyle="danger">
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-xs" data-type="Edit" type="button"
                                                    title="Edit user">
                                                    <a href="{{ route('user.sub.edit', ['id' => $user->id]) }}"><i
                                                            class="fa fa-pencil"></i></a>
                                                </button>
                                                <button class="btn btn-warning btn-xs lock" data-id="{{ $user->id }}"
                                                    data-status="{{ $user->status }}" type="button"
                                                    title="Change password"><i
                                                        class="fa fa-{{ $user->status == '0' ? 'unlock' : 'lock' }}"></i></button>
                                                <button class="btn btn-danger btn-xs delete" data-id="{{ $user->id }}"
                                                    type="button" title="Delete user">
                                                    <i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No sub users available.For add business click <a
                                                href="javascript:void(0)" class="subUser" data-toggle="modal">here</a></td>
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
    <div id="addSubModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="modalName"></span> Sub user</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="member" class="col-sm-2 control-label">Member</label>

                                <div class="col-sm-10">
                                    <select class="form-control error" name="member" id="member">
                                        <option value="">Select a member</option>
                                        @foreach ($members as $member)
                                            <option value="{{ $member->id }}">{{ $member->email }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">First Name</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="fname" placeholder="First Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Last Name</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="lname" placeholder="Last Name">
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
                                <label for="company" class="col-sm-2 control-label">Company</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error autocomplete" id="company"
                                        placeholder="Company" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="col-sm-2 control-label">Address</label>

                                <div class="col-sm-10">
                                    <textarea name="address" class="form-control error" placeholder="Enter Address"
                                        id="address"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-2 control-label">City</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="city" placeholder="City">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="state" class="col-sm-2 control-label">State</label>

                                <div class="col-sm-10">
                                    <select class="form-control error" name="state" id="state">
                                        <option value="">Select a state</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-2 control-label">Zip</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="zip" placeholder="Zip">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-2 control-label">Phone</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="phone" placeholder="Phone">
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
                        <input type="hidden" id="company_id" name="company_id">
                        <div class="form-group error-tag-field" style="display: none;">
                            <label for="error" class="col-sm-2 control-label">Error</label>

                            <div class="col-sm-10">
                                <span id="error-field" style="color: red;"></span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <button type="button" class="btn btn-info" id="addSubButton"><i
                                    class="fa fa-spinner fa-spin loader" style="display: none;"></i>
                                Add Sub User
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
            $('.subUser').on('click', function() {
                var type = $(this).data('type');
                $('.modalName').text(type);
                $('#addSubModel').modal('show');
            });
            $('#addSubButton').on('click', function() {
                var type = $(this).data('type');
                $('.help-block').replaceWith('');
                var member = $('#member').val();
                if (member == '') {
                    $('#member').parent().parent('div').addClass('has-error');
                    $('#member').parent('div').append(
                        '<span class="help-block">Please select member</span>');
                    return false;
                } else {
                    $('#state').parent().parent('div').removeClass('has-error');
                }
                var fname = $('#fname').val();
                if (fname == '') {
                    $('#fname').parent().parent('div').addClass('has-error');
                    $('#fname').parent('div').append(
                        '<span class="help-block">Please enter first name</span>');
                    return false;
                }
                var lname = $('#lname').val();
                if (lname == '') {
                    $('#lname').parent().parent('div').addClass('has-error');
                    $('#lname').parent('div').append(
                        '<span class="help-block">Please enter last name</span>');
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
                        '<span class="help-block">Please enter valid email</span>');
                    return false;
                }
                var company = $('#company').val();
                if (company == '') {
                    $('#company').parent().parent('div').addClass('has-error');
                    $('#company').parent('div').append(
                        '<span class="help-block">Please enter company name</span>');
                    return false;
                }
                var address = $('#address').val();
                if (address == '') {
                    $('#address').parent().parent('div').addClass('has-error');
                    $('#address').parent('div').append(
                        '<span class="help-block">Please enter your address</span>');
                    return false;
                }
                var city = $('#city').val();
                if (city == '') {
                    $('#city').parent().parent('div').addClass('has-error');
                    $('#city').parent('div').append('<span class="help-block">Please enter city</span>');
                    return false;
                }
                var state = $('#state').val();
                if (state == '') {
                    $('#state').parent().parent('div').addClass('has-error');
                    $('#state').parent('div').append('<span class="help-block">Please select state</span>');
                    return false;
                } else {
                    $('#state').parent().parent('div').removeClass('has-error');
                }
                var zip = $('#zip').val();
                if (zip == '') {
                    $('#zip').parent().parent('div').addClass('has-error');
                    $('#zip').parent('div').append('<span class="help-block">Please enter zip code</span>');
                    return false;
                }
                var zipReg = /(^\d{5}$)|(^\d{5}-\d{4}$)/;
                if (!zipReg.test(zip)) {
                    $('#zip').parent().parent('div').addClass('has-error');
                    $('#zip').parent('div').append(
                        '<span class="help-block">Please enter valid zip code</span>');
                    return false;
                }
                var phone = $('#phone').val();
                if (phone == '') {
                    $('#phone').parent().parent('div').addClass('has-error');
                    $('#phone').parent('div').append(
                        '<span class="help-block">Please enter phone number</span>');
                    return false;
                }
                var phoneReg = /^\d{10}$/;
                if (!phoneReg.test(phone)) {
                    $('#phone').parent().parent('div').addClass('has-error');
                    $('#phone').parent('div').append(
                        '<span class="help-block">Please enter valid phone number</span>');
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
                var company_id = $('#company_id').val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.add.sub') }}",
                    data: {
                        type: type,
                        member: member,
                        fname: fname,
                        lname: lname,
                        user_name: user_name,
                        email: email,
                        company: company,
                        company_id: company_id,
                        address: address,
                        city: city,
                        state: state,
                        zip: zip,
                        phone: phone,
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
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Sub user created successfully',
                            }).then(function() {
                                window.location.reload();
                            });
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
            $('.status').on('change', function() {
                var id = $(this).data('id');
                var status = $(this).data('status');
                var statusName = (status == "1") ? "Activated" : "Inactivated";
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.status') }}",
                    data: {
                        id: id,
                        status: status,
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
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Member ' + statusName + ' successfully',
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            swal(
                                'Oops...',
                                data.message,
                                'error'
                            )
                        }

                    }
                });
            });
            $('.lock').on('click', function() {
                var id = $(this).data('id');
                var status = $(this).data('status');
                var statusName = (status == "1") ? "Activate" : "Inactivate";
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.status') }}",
                    data: {
                        id: id,
                        status: status,
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
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Member ' + statusName + ' successfully',
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            swal(
                                'Oops...',
                                data.message,
                                'error'
                            )
                        }

                    }
                });
            });
            $('.delete').on('click', function() {
                var id = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('member.delete') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Member deleted successfully',
                                }).then(function() {
                                    window.location.reload();
                                });

                            } else {
                                swal(
                                    'Error',
                                    data.message,
                                    'error'
                                )
                            }
                        }
                    });
                });
            });
        });
        $('.autocomplete').autocomplete({
            source: function(request, response) {
                var key = request.term;
                $.ajax({
                    url: "{{ route('admin.company.autocomplete') }}",
                    dataType: "json",
                    data: {
                        key: key,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log(data);
                        var array = $.map(data.data, function(item) {
                            return {
                                label: item.company,
                                value: item.company,
                                id: item.id,
                                data: item.data
                            }
                        });
                        response(array)
                    }
                });
            },
            minLength: 1,
            max: 10,
            select: function(event, ui) {
                $('#address').val(ui.item.data.address);
                $('#city').val(ui.item.data.city);
                $('#state').val(ui.item.data.state_id);
                $('#zip').val(ui.item.data.zip);
                $('#phone').val(ui.item.data.phone);
                $('#fax').val(ui.item.data.fax);

            }
        });

        $('.search').on('click', function() {
            var string = $('#tier_search').val();
            var location = appendToQueryString('search', string);
            window.location.href = location;
        });
        $('#tier_search').keyup(function(e){
            if(e.keyCode == 13)
            {
                var string = $('#tier_search').val();
                var location = appendToQueryString('search', string);
                window.location.href = location;
            }
        });

        $('#member').change(function() {
            var member_id = $(this).val();
            if (member_id != '') {
                $.ajax({
                    url: "{{ route('admin.subuser.companydetails') }}",
                    dataType: "json",
                    data: {
                        member_id: member_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#company').val(data.data.company);
                            $('#address').val(data.data.address);
                            $('#city').val(data.data.city);
                            $('#state').val(data.data.state_id);
                            $('#zip').val(data.data.zip);
                            $('#phone').val(data.data.phone);
                            $('#fax').val(data.data.fax);
                            $('#company_id').val(data.data.id);
                        } else {
                            $('#company').val('');
                            $('#address').val('');
                            $('#city').val('');
                            $('#state').val('');
                            $('#zip').val('');
                            $('#phone').val('');
                            $('#fax').val('');
                            $('#company_id').val('');
                        }
                    }
                });

            } else {
                $('#company').val('');
                $('#address').val('');
                $('#city').val('');
                $('#state').val('');
                $('#zip').val('');
                $('#phone').val('');
                $('#fax').val('');
                $('#company_id').val('');
            }
        })
    </script>
@endsection
