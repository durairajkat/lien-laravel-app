@extends('basicUser.layout.main')

@section('title', 'Customer Contacts')
@section('style')

    <style>
        .input-error {
            border: 2px solid red;

        }

        textarea {
            max-width: 100%;
            max-height: 100%;
        }

        .blue-btn-ext {
            background: #1084ff;
            color: #fff;
        }

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-sm-10 col-sm-offset-1">
                <div class="table-block">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <h2>User</h2>
                        </div>
                        <div class="col-md-7 col-sm-7 table-middle">
                            <ul class="contactsList">
                                <li><a href="javascript:void(0)" class="addSubUser" data-type="add"
                                        data-toggle="modal"><span><i class="fa fa-plus-circle"
                                                aria-hidden="true"></i></span> Create New Sub User</a></li>
                            </ul>
                        </div>
                        <div class="col-md-2 col-sm-2 align-right">
                            <!-- <span class="filter">
                               <a href="#">
                               <img src="{{ env('ASSET_URL') }}/images/filter.png" alt="img"> Filter
                               </a>
                               </span>
                                <span class="search dropdown-toggle" id="dropdownMenuButton1" role="alert" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <a href="#">
                               <img src="{{ env('ASSET_URL') }}/images/search.png" alt="img">
                               </a>
                               </span> -->
                            <div class="dropdown-menu dropdown-search" aria-labelledby="dropdownMenuButton">
                                <form>
                                    <input type="text" placeholder="Search.." class="form-control">
                                    <button class="btn s-button"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="table-responsive white-table">
                                <table class="table customer">
                                    <thead>
                                        <tr>
                                            <th>Company Name</th>
                                            <th>Title</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Direct Phone</th>
                                            <th>Cell</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($sub_users) > 0)
                                            @foreach ($sub_users as $key => $sub_user)
                                                <tr>
                                                    <td>
                                                        <div class="pad15">
                                                            {{ !is_null($sub_user->mapcompanyContacts) && !is_null($sub_user->mapcompanyContacts->company) ? $sub_user->mapcompanyContacts->company->company : 'N/A' }}
                                                        </div>
                                                    </td>
                                                    {{-- <td><div class="pad15">{{$sub_user->mapcompanyContacts->company->company}}</div></td> --}}
                                                    <td>
                                                        <div class="pad15">N/A</div>
                                                    </td>
                                                    <td>
                                                        <div class="pad15">
                                                            {{ !is_null($sub_user->details) ? $sub_user->details->first_name : 'N/A' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="pad15">
                                                            {{ !is_null($sub_user->details) ? $sub_user->details->last_name : 'N/A' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="pad15">{{ $sub_user->email }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="pad15">
                                                            {{ !is_null($sub_user->details) ? $sub_user->details->phone : 'N/A' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="pad15">
                                                            {{ !is_null($sub_user->details) && !is_null($sub_user->details->office_phone) ? $sub_user->details->office_phone : 'N/A' }}
                                                        </div>
                                                    </td>


                                                    <td class="editDelete">

                                                        <a href="javascript:void(0)" class="addSubUser"
                                                            data-subuser_id="{{ $sub_user->id }}" data-type="edit"
                                                            data-first_name="{{ $sub_user->details->first_name }}"
                                                            data-last_name="{{ $sub_user->details->last_name }}"
                                                            data-company="{{ !is_null($sub_user->mapcompanyContacts) && !is_null($sub_user->mapcompanyContacts->company) ? $sub_user->mapcompanyContacts->company->company : '' }}"
                                                            data-address="{{ !is_null($sub_user->mapcompanyContacts) ? $sub_user->mapcompanyContacts->address : '' }}"
                                                            data-city="{{ !is_null($sub_user->mapcompanyContacts) ? $sub_user->mapcompanyContacts->city : '' }}"
                                                            data-state="{{ !is_null($sub_user->mapcompanyContacts) ? $sub_user->mapcompanyContacts->state_id : '' }}"
                                                            data-phone="{{ !is_null($sub_user->mapcompanyContacts) ? $sub_user->mapcompanyContacts->phone : '' }}"
                                                            data-zip="{{ !is_null($sub_user->mapcompanyContacts) ? $sub_user->mapcompanyContacts->zip : '' }}"
                                                            data-email="{{ $sub_user->email }}"
                                                            data-username="{{ $sub_user->user_name }}"
                                                            data-password="{{ $sub_user->password }}">EDIT
                                                        </a> |
                                                        <a href="javascript:void(0)" class="delete"
                                                            data-id="{{ $sub_user->id }}">DELETE

                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8"> No contact found...</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="8">
                                                {{ $sub_users->links() }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    @if (\Request::route()->getName() == 'member.contacts.users')
                        <div class="row">
                            <div class="col-md-12 col-sm-12 invite">
                                <h2>Invite New User
                                    <img data-toggle="tooltip" title="Hooray!"
                                        src="{{ env('ASSET_URL') }}/images/ask.png" alt="img">
                                </h2>
                                <form action="{{ route('member.invite.post') }}" method="post" class="invite-n-user">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <label>Company Name</label>
                                                <input type="text" name="companyName" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" name="firstName" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" name="lastName" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <label>Email Address</label>
                                                <input type="text" name="address" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <!--   <div class="col-md-3 col-sm-3">
                                              <div class="form-group">
                                                  <label>User Type</label>
                                                  <select class="form-control" name="userType">
                                                      <option>Administator</option>
                                                      <option>Administator</option>
                                                      <option>Administator</option>
                                                      <option>Administator</option>
                                                  </select>
                                              </div>
                                          </div>
                                      </div> -->
                                    <div class="row">
                                        <div class="col-md-7 col-sm-7">
                                            <div class="form-group">
                                                <label>Message</label>
                                                <textarea rows="4" class="form-control message" name="message"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <label>&nbsp;</label><br>
                                                <input class="blue-btn" type="submit" value="SUBMIT">
                                            </div>
                                        </div>
                                        <!--  <button id="btnAdd" type="button" class="btn btn-primary" data-toggle="tooltip"
                                              data-original-title="Add more controls"><i class="glyphicon glyphicon-plus-sign"></i>
                                             </button> -->
                                        <!-- <div class="col-md-1 col-sm-1">
                                                <div class="form-group">
                                                    <br>
                                                    <button id="btnAdd" type="button" class="btn btn-primary" data-toggle="tooltip"
                                                     data-original-title="Add more controls"><i class="glyphicon glyphicon-plus-sign"></i>
                                                    </button>
                                                </div>
                                            </div> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <h2>Manage Projects
                                    <img data-toggle="tooltip" title="Manage Projects"
                                        src="{{ env('ASSET_URL') }}/images/ask.png" alt="img">
                                </h2>
                                <div class="white-box ">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4">
                                            <div class="sky-box">
                                                <h3>File Your Claim <br>
                                                    (Lien | Notice)
                                                </h3>
                                                <img src="{{ env('ASSET_URL') }}/images/claim-icon.png" alt="img">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="sky-box">
                                                <h3>Collect Receivables</h3>
                                                <img src="{{ env('ASSET_URL') }}/images/collect-icon.png" alt="img">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="sky-box">
                                                <h3>Manage Finances
                                                    & Receivables
                                                </h3>
                                                <img src="{{ env('ASSET_URL') }}/images/manage-icon.png" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4">
                                            <div class="sky-box">
                                                <h3>Consultation</h3>
                                                <img src="{{ env('ASSET_URL') }}/images/consultation-icon.png"
                                                    alt="img">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="sky-box">
                                                <h3>Generate Reports</h3>
                                                <img src="{{ env('ASSET_URL') }}/images/report-icon.png" alt="img">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-4">
                                            <div class="sky-box">
                                                <h3>Document Library</h3>
                                                <img src="{{ env('ASSET_URL') }}/images/manage-icon.png" alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('basicUser.modals.sub_user_modal',['user' => $user,'states' => $states])
    {{-- <div id="addUserModel" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Create Sub User </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" id="addUserForm">
                        <!-- <div class="form-group">
                            <label class="col-md-4 control-label">Contact Type : </label>
                            <div class="col-md-8">
                                <select name="ContactType" class="form-control error" id="contactType1">
                                    <option value="">Select a contract type</option>
                                    <option value="General Contractor">General Contractor</option>
                                    <option value="Architect">Architect</option>
                                    <option value="Sub-Contractor">Sub-Contractor</option>
                                    <option value="Bonding Company">Bonding Company</option>
                                    <option value="Owner">Owner</option>
                                    <option value="Lender">Lender</option>
                                    <option value="Title Company">Title Company</option>
                                    <option value="Engineer">Engineer</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Company : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="company" id="company1" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->company) ? $user->details->getCompany->company : ''}}"
                                       placeholder="Enter Company Name" readonly/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">First Name : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="firstName" id="firstName1"
                                       placeholder="Enter First Name"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Last Name : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="lastName" id="lastName1"
                                       placeholder="Enter Last Name"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Address : </label>
                            <div class="col-md-8">
                                <textarea name="address" class="form-control error" placeholder="Enter Address"
                                          id="address1">{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->address)? $user->details->getCompany->address : ''}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">City : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="city" id="city1" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->city)? $user->details->getCompany->city : ''}}"
                                       placeholder="Enter City"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">State : </label>
                            <div class="col-md-8">
                                <select class="form-control error" name="state" id="state1">
                                    <option value="">Select a state</option>
                                    @foreach ($states as $key => $state)
                                        <option value="{{ $key }}" {{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->state_id) && $key == $user->details->getCompany->state_id ?  'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Zip : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="zip" id="zip1" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->zip)? $user->details->getCompany->zip : ''}}"
                                       placeholder="Enter Zip Code"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Phone : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="phone" id="phone1" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->phone)? $user->details->getCompany->phone : ''}}"
                                       placeholder="Enter Phone Number"/>
                            </div>
                        </div>
                       <!--  <div class="form-group">
                            <label class="col-md-4 control-label">Fax : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="fax" id="fax1"
                                       placeholder="Enter Fax Number"/>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Email : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="email" name="email" id="email1"
                                       placeholder="Enter Email"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">User Name : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="test" name="username" id="username1"
                                       placeholder="Enter username"/>
                            </div>
                        </div> --}}{{-- <div class="form-group">
                            <label class="col-md-4 control-label">Password : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="password" name="password" id="password1"
                                       placeholder="Enter Email"/>
                            </div>
                        </div> --}}{{-- <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <div id="error-message">

                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->id) ?  $user->details->getCompany->id : 0 }}" id="companyId">
                        <input type="hidden" value="0" id="subUserId">
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button class="btn blue-btn  formSubmit" type="button"><span
                                            class="title"></span> Contact
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div> --}}
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#addUserModel').on('hidden.bs.modal', function() {
                $('#addUserForm').trigger("reset");
            });
            $('.industry').hide();
            $('.addIndustryButton').hide();
            $('.industryContacts').on('click', function() {
                $('.industry').show();
                $('.customer').hide();
                $('.addCustomerButton').hide();
                $('.addIndustryButton').show();

            });
            $('.customerContacts').on('click', function() {
                $('.industry').hide();
                $('.customer').show();
                $('.addCustomerButton').show();
                $('.addIndustryButton').hide();

            });
            $('.contactsList > li').on('click', function() {
                $('.contactsList > li').removeClass("select");
                $(this).addClass('select');

            });
            $('.addCustomerButton').on('click', function() {
                var type = $(this).data('type');
                if (type == 'add') {
                    $('.formSubmit').attr('data-type', 'add');
                    $('.title').html('Add');
                } else {
                    $('#company').val($(this).data('company'));
                    $('#firstName').val($(this).data('first_name'));
                    $('#lastName').val($(this).data('last_name'));
                    $('#address').val($(this).data('address'));
                    $('#city').val($(this).data('city'));
                    $('#state').val($(this).data('state'));
                    $('#zip').val($(this).data('zip'));
                    $('#phone').val($(this).data('phone'));
                    //  $('#fax').val($(this).data('fax'));
                    $('#email').val($(this).data('email'));
                    $('#id').val($(this).data('id'));
                    $('.formSubmit').attr('data-type', 'edit');
                    $('.title').html('Edit');
                }
                $('#addCustomerModel').modal('show');
            });
            $('.addSubUser').on('click', function() {
                //alert('fdgdf');
                var type = $(this).data('type');
                if (type == 'add') {
                    $('.formSubmit').attr('data-type', 'add');
                    $('.title').html('Add');
                    $('#subUserId').val(0);
                } else {
                    //$('#contactType1').val($(this).data('contact_type'));
                    $('#company1').val($(this).data('company'));
                    $('#firstName1').val($(this).data('first_name'));
                    $('#lastName1').val($(this).data('last_name'));
                    $('#address1').val($(this).data('address'));
                    $('#city1').val($(this).data('city'));
                    $('#state1').val($(this).data('state'));
                    $('#zip1').val($(this).data('zip'));
                    $('#phone1').val($(this).data('phone'));
                    $('#fax1').val($(this).data('fax'));
                    $('#email1').val($(this).data('email'));
                    $('#subUserId').val($(this).data('subuser_id'));
                    $('#email1').val($(this).data('email'));
                    $('#username1').val($(this).data('username'));
                    //$('#password1').val($(this).data('password'));
                    $('.formSubmit').attr('data-type', 'edit');
                    $('.title').html('Edit');
                }
                $('#addUserModel').modal('show');
            });
            $('.formSubmit').on('click', function() {
                var company = $('#company1').val();
                var flag = true;
                var company_id = $('#companyId').val();
                var company = $('#company1').val();
                if (company == '') {
                    $('#company1').addClass('input-error');
                    flag = false;
                }
                var firstName = $('#firstName1').val();
                if (firstName == '') {
                    $('#firstName1').addClass('input-error');
                    flag = false;
                }
                var lastName = $('#lastName1').val();
                if (lastName == '') {
                    $('#lastName1').addClass('input-error');
                    flag = false;
                }
                var address = $('#address1').val();
                if (address == '') {
                    $('#address1').addClass('input-error');
                    flag = false;
                }
                var city = $('#city1').val();
                if (city == '') {
                    $('#city1').addClass('input-error');
                    flag = false;
                }
                var state = $('#state1').val();
                if (state == '') {
                    $('#state1').addClass('input-error');
                    flag = false;
                }
                var zip = $('#zip1').val();
                if (zip == '') {
                    $('#zip1').addClass('input-error');
                    flag = false;
                }
                if (!validateNumber(zip, 'zip1')) {
                    $('#zip1').addClass('input-error');
                    flag = false;
                }
                var phone = $('#phone1').val();
                if (phone == '') {
                    $('#phone1').addClass('input-error');
                    flag = false;
                }
                if (!validateNumber(phone, 'phone1')) {
                    $('#phone1').addClass('input-error');
                    flag = false;
                }
                // var fax = $('#fax1').val();
                var email = $('#email1').val();
                if (email == '') {
                    $('#email1').addClass('input-error');
                    flag = false;
                }
                var username = $('#username1').val();
                if (username == '') {
                    $('#username1').addClass('input-error');
                    flag = false;
                }
                /*var password = $('#password1').val();
                if (password == '') {
                    $('#password1').addClass('input-error');
                    flag = false;
                }*/
                if (!validateEmail(email)) {
                    $('#email1').addClass('input-error');
                    flag = false;
                }
                var type = $(this).data('type');
                var subuser_id = $('#subUserId').val();
                if (flag) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('member.subuser.create') }}",
                        data: {
                            company_id: company_id,
                            subuser_id: subuser_id,
                            type: type,
                            company: company,
                            firstName: firstName,
                            lastName: lastName,
                            address: address,
                            city: city,
                            zip: zip,
                            phone: phone,
                            //   fax: fax,
                            email: email,
                            state: state,
                            username: username,
                            //password: password,
                            user_id: '{{ Auth::user()->id }}',
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                $('#addUserModel').modal('hide');
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'User created successfully',
                                }).then(function() {
                                    window.location.reload();
                                });

                            } else {
                                html = '';
                                $.each(data.message, function(index, value) {
                                    html += '<p class="input-error">' + value + '</p>'
                                });
                                $('#error-message').html(html);
                                //$('#error-message').html('<p class="input-error">' + data.message + '</p>');
                                //  location.reload();
                            }
                        }
                    });
                }
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
                    buttonsStyling: false,
                    allowOutsideClick: false
                }).then(function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('member.subuser.delete') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Contact deleted successfully',
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
            $('.error').on('focus', function() {
                $(this).removeClass('input-error');
                $('#error-message').html('');
            });

            function validateEmail(mail) {
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
                    return true;
                }
                $('#error-message').html("<p class='input-error'>You have entered an invalid email address!</p>");
                return false;
            }

            function validateNumber(number, type) {
                if (/^-?\d*\.?\d*$/.test(number)) {
                    return true;
                }
                $('#error-message').html("<p class='input-error'>You have entered an invalid " + type + " !</p>");
                return false;
            }
        });
    </script>
@endsection
