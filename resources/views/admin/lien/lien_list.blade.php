<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Lien Provider')
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Lien Providers
                <small>List</small>
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="row col-md-12">
                                <div class="col-md-8">
                                    <h3 class="box-title">List of lien providers</h3>
                                    <button class="btn btn-md btn-info addLien" data-type="Add" type="button"
                                        data-toggle="modal">
                                        Add new provider
                                    </button>
                                </div>
                                <div class="col-md-4 input-group input-group pull-right">
                                    <input id="tier_search" value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}"
                                        class="form-control pull-right"
                                        placeholder="Enter Email/Company/First Name/Last Name" type="text">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default search"><i
                                                class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>Company</th>
                                    <th>Company Phone Number</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>State</th>
                                    <th>Action</th>
                                </tr>
                                @if (count($lienProviders))
                                    @foreach ($lienProviders as $key => $provider)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $provider->company }}</td>
                                            <td>{{ !is_null($provider->companyPhone) && !empty($provider->companyPhone) ? $provider->companyPhone : 'N/A' }}
                                            </td>
                                            <td>{{ $provider->firstName }}</td>
                                            <td>{{ $provider->lastName }}</td>
                                            <td>{{ !is_null($provider->phone) && !empty($provider->phone) ? $provider->phone : 'N/A' }}
                                            </td>
                                            <td>{{ $provider->email }}</td>
                                            @if(isset($provider->states) && count($provider->states) > 0)
                                                <td>
                                                    @foreach ($provider->states as $skey => $state)
                                                        @foreach ($state->state as $sskey => $st)
                                                            {{ $st->name }}
                                                        @endforeach
                                                    @endforeach
                                                </td>
                                            @else
                                                <td>{{ $provider->state->name }}</td>
                                            @endif

                                            <td>
                                                <button class="btn btn-info btn-xs subUser" data-type="Edit"
                                                    data-id="{{ $provider->id }}"
                                                    data-company_id="{{ $provider->company_id }}"
                                                    data-user_id="{{ $provider->user_id }}"
                                                    data-company="{{ $provider->company }}"
                                                    data-role_name="{{ $provider->role_name }}"
                                                    data-role_other="{{ $provider->role_other }}"
                                                    data-companyphone="{{ $provider->companyPhone }}"
                                                    data-fname="{{ $provider->firstName }}"
                                                    data-logo="{{ $provider->logo }}"
                                                    data-lname="{{ $provider->lastName }}"
                                                    data-email="{{ $provider->email }}"
                                                    data-phone="{{ $provider->phone }}"
                                                    data-fax="{{ $provider->fax }}"
                                                    @if(isset($provider->states) && count($provider->states) > 0)
                                                        data-state="{{ $provider->states }}"
                                                    @else
                                                        data-state="{{ $provider->stateId }}"
                                                    @endif
                                                    data-address="{{ $provider->address }}"

                                                    data-zip="{{ $provider->zip }}" data-city="{{ $provider->city }}"
                                                    type="button" title="Edit user">
                                                    <a href="#"><i class="fa fa-pencil"></i></a>
                                                </button>
                                                @if ($provider->is_deletable == '1')
                                                    <button class="btn btn-danger btn-xs delete"
                                                        data-id="{{ $provider->id }}" type="button" title="Delete lien">
                                                        <i class="fa fa-trash"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">There is no Lien providers</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{ $lienProviders->links() }}
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
                    <h4 class="modal-title"><span class="modalName"></span> Lien Provider</h4>
                </div>
                <div class="modal-body">
                    <form id="lienProviderForm" class="form-horizontal"  enctype="multipart/form-data">
                        <div class="box-body">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <div class="form-group">
                                <label for="company" class="col-sm-2 control-label">Company</label>

                                <div class="col-sm-10">
                                    <select id="company">
                                        <option value=""></option>
                                        @isset($companies)
                                            @foreach ($companies as $key => $company)
                                                <option value="{{ $key }}">{{ $company }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    {{-- <input type="text" class="form-control error" id="company" placeholder="Company"> --}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="role" class="col-sm-2 control-label">Role</label>

                                <div class="col-sm-10">
                                    <select class="form-control error" name="role_name" id="role_name">
                                        <option value="">Select a role</option>
                                        <option value="notice_service">Notice Service</option>
                                        <option value="law_firm">Law Firm</option>
                                        <option value="mediator_arbitrator">Mediator/Arbitrator</option>
                                        <option value="consultant">Consultant</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div id="box_other" class="form-group" style="display: none">
                                <label for="role" class="col-sm-2 control-label"></label>

                                <div class="col-sm-10">
                                    <input type="text"  name="role_other"  class="form-control error" id="role_other" placeholder="Other">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="col-sm-2 control-label">Phone</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="phone" placeholder="Phone">
                                </div>
                            </div>
                            <input type="hidden" name="lienId" id="lienId">
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
                                <label for="email" class="col-sm-2 control-label">Email</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control error" id="email" placeholder="Email">
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
                                    <select class="form-control error" name="state[]" id="state" multiple>
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
                                <label for="company_phone" class="col-sm-2 control-label">Company Phone</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="company_phone"
                                        placeholder="Company Phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fax" class="col-sm-2 control-label">Fax</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="fax" placeholder="Fax">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fax" class="col-sm-2 control-label">Image</label>

                                <div class="col-sm-10">
                                    <input type="file" class="form-control error" name="logo" id="logo"
                                           placeholder="Image">

                                    <img id="image" style="display: none; width: 100px;margin-top: 10px" />
                                </div>
                            </div>
                            <div class="form-group password_field">
                                <label for="password" class="col-sm-2 control-label">Password</label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control error" id="password" name="password"
                                        placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group conf_password_field">
                                <label for="confirm_password" class="col-sm-2 control-label">Confirm Password</label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control error" id="confirm_password"
                                        name="confirm_password" placeholder="Confirm Password">
                                    <input type="hidden" value="0" name="user_id" id="user_id">
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
                        <div class="box-footer text-center">
                            <button type="button" class="btn btn-info" id="addSubButton"><i
                                    class="fa fa-spinner fa-spin loader" style="display: none;"></i>
                                Add Lien Provider
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
        var chosen = $('#state').chosen({
            width: '100%',
            no_results_text: "Oops, nothing found! <a class='add_state_from_search'>Click here to add state</a>"
        })
        var chosen = $('#company').chosen({
                width: '100%',
                no_results_text: "Oops, nothing found! <a class='add_company_from_search'>Click here to add company</a>"
            })
            .change(
                function() {
                    if ($(this).val() != "new_data") {
                        $.ajax({
                            url: '{{ route('admin.company.autocomplete') }}',
                            dataType: "json",
                            data: {
                                key: $(this).val(),
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success && response.data !== null) {

                                    setData(response);
                                } else {
                                    reset();
                                }
                            }
                        });

                    } else {
                        reset();
                    }
                }
            );

        function reset() {
            console.log('reset');
            $('#company').removeAttr('data-company_name');
            $('#address').val("");
            $('#city').val("");
            $("#state").val([]).trigger("chosen:updated");
            $('#zip').val("");
            $('#company_phone').val("");
            $('#fax').val("");
            $('#role_name').val("");
            $('#role_other').val("");
            $('#image').hide();
        }

        function setData(response) {
            // console.log('!!!!!!!!!!!!!!!!!', response);
            $('#company').attr('data-company_name', response.data.company);
            $('#address').val(response.data.address);
            // $('#logo').val(response.data.logo);
            $('#city').val(response.data.city);
            // $('#state').val(response.data.state_id);
            if ($.isArray(response.data.state_id)) {
                $("#state").val(response.data.state_id).trigger("chosen:updated");
            } else {
                $("#state").val([response.data.state_id]).trigger("chosen:updated");
            }
            $('#zip').val(response.data.zip);
            $('#company_phone').val(response.data.phone);
            $('#fax').val(response.data.fax);
            $('#role_name').val(response.data.role_name);
            console.log("response.data.role_name",response.data.role_name);
            $('#role_other').val(response.data.role_other);
        }

        $(document).delegate('.add_company_from_search', 'click', function() {
            var company = chosen.data('chosen').get_search_text();
            $("#company option[value='new_data']").remove();
            $('#company').append("<option value='new_data'>" + company + "</option>");
            $('#company').val('new_data'); // if you want it to be automatically selected
            $('#company').trigger("chosen:updated");
            $('#company').attr('data-company_name', company);

            $('#address').val("");
            $('#city').val("");
            $("#state").val([]).trigger("chosen:updated");
            $('#zip').val("");
            $('#company_phone').val("");
            $('#fax').val("");
            $('#image').hide();
            $('#role_name').val("");
            $('#role_other').val("");
        });

        $('#role_name').on('change', function() {
            console.log( this.value );
            if(this.value == 'other') {
                $('#box_other').show();
            } else {
                $('#box_other').hide();
            }
        });


        $(document).ready(function() {

            $('.addLien').on('click', function() {
                $("#state").val([]).trigger("chosen:updated");
                $('#lienProviderForm')[0].reset();
                $('.password_field').show();
                $('.conf_password_field').show();
                $('#user_id').val('0');
                $('#company').val('').trigger('chosen:updated');
                $('#company').removeAttr('data-company_name');
                var type = $(this).data('type');
                $('#addSubButton').attr('data-type', type);
                $('#addSubButton').text('Add Lien Provider');
                $('.modalName').text(type);
                $('#addSubModel').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });
            $('.subUser').on('click', function() {
                var type = $(this).data('type');
                // var logo = $(this).data('logo');
                $('#company').removeAttr('data-company_name');
                $('#password').val('');
                $('#confirm_password').val('');
                // $('.password_field').hide();
                $('.conf_password_field').hide();
                $('#addSubButton').attr('data-type', type);
                $('#addSubButton').text('Edit Lien Provider');
                $('#company').val($(this).data('company_id')).trigger('chosen:updated');
                $('#company').attr('data-company_name', $(this).data('company'));
                $('#company_phone').val($(this).data('companyphone'));
                $('#user_id').val($(this).data('user_id'));
                $('#fname').val($(this).data('fname'));
                $('#lname').val($(this).data('lname'));
                console.log('logo', $(this).data('logo'));
                // $('#logo').val($(this).data('logo'));

                if($(this).data('logo') != '') {
                    $('#image').attr('src','../liens/'+$(this).data('logo'));
                    $('#image').show();
                } else {
                    $('#image').hide();
                }
                console.log($(this).data('logo'));
                var role_name = $(this).data('role_name');

                if(role_name != 'notice_service' && role_name != 'law_firm' && role_name != 'mediator_arbitrator' && role_name != 'consultant' && role_name != 'other' ) {
                    $('#role_name').val('other');
                    $('#box_other').show();
                    $('#role_other').val(role_name);
                    setTimeout(() => {
                        $("#role_name").val("other").change();
                    }, 500);
                }

                $('#role_name').val($(this).data('role_name'));
                $('#email').val($(this).data('email'));
                $('#city').val($(this).data('city'));
                $('#address').val($(this).data('address'));
                if ($.isArray($(this).data('state'))) {
                    var arrStates = [];
                    var states = $(this).data('state');
                    for (st in states) {
                        arrStates.push(states[st].state_id);
                    }
                    $("#state").val(arrStates).trigger("chosen:updated");
                } else {
                    $("#state").val([$(this).data('state')]).trigger("chosen:updated");
                }

                $('#zip').val($(this).data('zip'));
                $('#fax').val($(this).data('fax'));
                $('#phone').val($(this).data('phone'));
                $('#lienId').val($(this).data('id'));
                $('.modalName').text(type);
                $('#addSubModel').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });
            $('.error').on('keyup', function() {
                $(this).parent().parent('div').removeClass('has-error');
                $(this).parent('div').children('.help-block').remove();
                $('.error-tag-field').hide();
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
                        url: "{{ route('lien.delete') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Lien provider deleted successfully',
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
            $('#addSubButton').on('click', function() {
                var type = $(this).data('type');
                $('.help-block').replaceWith('');
                var company = $('#company').val();
                if (company == '') {
                    $('#company').parent().parent('div').addClass('has-error');
                    $('#company').parent('div').append(
                        '<span class="help-block">Please enter company name</span>');
                    return false;
                }

                var role_other = $('#role_other').val();
                var role_name = $('#role_name').val();

                var phone = $('#phone').val();
                if (phone == '') {
                    $('#phone').parent().parent('div').addClass('has-error');
                    $('#phone').parent('div').append(
                        '<span class="help-block">Please enter phone number</span>');
                    return false;
                }
                var company_phone = $('#company_phone').val();
                if (company_phone == '') {
                    $('#company_phone').parent().parent('div').addClass('has-error');
                    $('#company_phone').parent('div').append(
                        '<span class="help-block">Please enter company phone number</span>');
                    return false;
                }

                //var companyphoneReg = /^\d{10}$/;
                //if (!companyphoneReg.test(company_phone)) {
                //    $('#company_phone').parent().parent('div').addClass('has-error');
                //    $('#company_phone').parent('div').append('<span class="help-block">Please enter valid phone number</span>');
                //    return false;
                //}

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

                // var phoneReg = /^\d{10}$/;
                // if (!phoneReg.test(phone)) {
                //     $('#phone').parent().parent('div').addClass('has-error');
                //     $('#phone').parent('div').append('<span class="help-block">Please enter valid phone number</span>');
                //     return false;
                // }
                var fax = $('#fax').val();
                if (fax != '') {
                    var phoneReg = /^\d{10}$/;
                    if (!phoneReg.test(fax)) {
                        $('#fax').parent().parent('div').addClass('has-error');
                        $('#fax').parent('div').append(
                            '<span class="help-block">Please enter valid fax</span>');
                        return false;
                    }
                }
                var lienId = $('#lienId').val();
                var user_id = $('#user_id').val();
                if (type == 'Add') {

                    // $('#state').chosen([]);
                    // $("#state").val([]).trigger("chosen:updated");

                    var password = $('#password').val();
                    var confirm_password = $('#confirm_password').val();
                    if (password == '') {
                        $('#password').parent().parent('div').addClass('has-error');
                        $('#password').parent('div').append(
                            '<span class="help-block">Please enter password</span>');
                        return false;
                    }

                    if (confirm_password == '') {
                        $('#confirm_password').parent().parent('div').addClass('has-error');
                        $('#confirm_password').parent('div').append(
                            '<span class="help-block">Please confirm the password</span>');
                        return false;
                    }

                    if (password != '' && confirm_password != '' && password != confirm_password) {
                        $('#confirm_password').parent().parent('div').addClass('has-error');
                        $('#confirm_password').parent('div').append(
                            '<span class="help-block">Password and confirm password doesn\'t match</span>'
                        );
                        return false;
                    }
                }

                var company_name = $('#company').data('company_name');
                var logo = $('#logo')[0].files[0];
                var formData = new FormData($('#lienProviderForm')[0]);
                formData.append("logo", $('#logo')[0].files[0]);
                formData.append("company", $('#company').val());
                formData.append("company_name", company_name);
                formData.append("company_phone", company_phone);
                formData.append("phone", $('#phone').val());
                formData.append("fname", $('#fname').val());
                formData.append("lname", $('#lname').val());
                formData.append("email", $('#email').val());
                formData.append("password", $('#password').val());
                formData.append("type", type);
                formData.append("city", $('#city').val());
                formData.append("zip", $('#zip').val());
                formData.append("fax", $('#fax').val());
                $.ajax({
                    type: "POST",
                    url: "{{ route('add.lien.provider') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    {{--data: {--}}
                    {{--    company: company,--}}
                    {{--    company_name: company_name,--}}
                    {{--    company_phone: company_phone,--}}
                    {{--    role_name: role_name,--}}
                    {{--    role_other: role_other,--}}
                    {{--    type: type,--}}
                    {{--    fname: fname,--}}
                    {{--    lname: lname,--}}
                    {{--    email: email,--}}
                    {{--    address: address,--}}
                    {{--    city: city,--}}
                    {{--    state: state,--}}
                    {{--    zip: zip,--}}
                    {{--    phone: phone,--}}
                    {{--    fax: fax,--}}
                    {{--    // image: image,--}}
                    {{--    lienId: lienId,--}}
                    {{--    password: password,--}}
                    {{--    user_id: user_id,--}}
                    {{--    _token: '{{ csrf_token() }}'--}}
                    {{--},--}}
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
                                title: 'Lien Provider created successfully',
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
        });
    </script>
@endsection
