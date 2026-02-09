@extends('basicUser.layout.main')

@section('title', 'Update Profile')

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

        .pad15 select {
            border: 1px solid #ccc;
        }

        table.profile-table tr table tr:first-child {
            border-color: #eee;
        }

        .radio_button {
            font-size: 16px;
        }

        .radio_label {
            margin-top: 5px;
        }

    </style>
@endsection
<link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
@section('content')


    <div class="container summary">
        <div class="row">
            <div class="col-md-3">
                <div class="create-project-form-bgcolor">
                    <div class="create-project-form-header">
                        <h2>Edit Your Profile</h2>
                    </div>
                    <div class="form-padding-wrapper-x text-left">
                        <div class="row-row-first">
                            <div class="">
                                <ul class="profile-links">
                                    <li class="grey"><a href="#a">Update Login Information</a></li>
                                    <li><a href="#b">Update Profile Picture</a></li>
                                    <li class="grey"><a href="#c">Update Contact Information</a></li>
                                    <li><a href="#d">Add / Edit Users</a></li>
                                    <li class="grey"><a href="#e">Notification Settings</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <form action="{{ route('member.action.profile') }}" method="post" enctype="multipart/form-data">
                    <div class="create-project-form-bgcolor">
                        <div class="create-project-form-header">
                            <a name="a"></a>
                            <h2>Login Information</h2>
                        </div>
                        <div class="form-padding-wrapper text-left">
                            <div class="row row-first">
                                <div class="col-md-6">
                                    <label>Email Address</label>
                                    <input type="text" name="email" id="userEmail" class="form-control"
                                        value="{{ Auth::user()->email }}">
                                </div>
                                <div class="col-md-6">
                                    <a href="">Reset Password</a>
                                </div>
                            </div>
                            <div class="row row-first">
                                <div class="col-md-6">
                                    <label>First Name</label>
                                    <input type="text" name="firstName"
                                        value="{{ !is_null($user->details) && isset($user->details->first_name) ? $user->details->first_name : '' }}"
                                        id="userFirstName" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>Last Name</label>
                                    <input type="text" name="lastName"
                                        value="{{ !is_null($user->details) && isset($user->details->last_name) ? $user->details->last_name : '' }}"
                                        id="userLastName" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="create-project-form-bgcolor">
                        <div class="create-project-form-header">
                            <a name="b"></a>
                            <h2>Update Profile Picture</h2>
                        </div>
                        <div class="form-padding-wrapper text-left">
                            <div class="row row-first">
                                <div class="col-md-6">
                                    <input type="file" name="image"
                                        value="{{ !is_null($user->details) && isset($user->details->image) ? $user->details->image : '' }}"
                                        id="image" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <div class="pad15 current_image">
                                        @if (!is_null($user->details) && $user->details->image != '')
                                            <img src="{{ env('ASSET_URL') }}/image_logo/{{ $user->details->image }}"
                                                alt="Logo" class="pull-right" height="50px">
                                        @else
                                            <img src="{{ env('ASSET_URL') }}/images/avatar5.png" alt="Logo" class="pull-right"
                                                height="50px">
                                        @endif

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="create-project-form-bgcolor">
                        <div class="create-project-form-header">
                            <a name="c"></a>
                            <h2>Contact Information</h2>
                        </div>
                        <div class="form-padding-wrapper text-left">
                            <div class="row row-first">
                                <div class="col-md-6">
                                    <div class="pad15 phone">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone"
                                            value="{{ !is_null($user->details) && isset($user->details->phone) ? $user->details->phone : '' }}"
                                            id="phone" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pad15 office_phone">
                                        <label>Office Phone</label>
                                        <input type="text" name="office_phone"
                                            value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->phone) ? $user->details->getCompany->phone : '' }}"
                                            id="office_phone" class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="row row-first">
                                <div class="col-md-6">
                                    <div class="pad15 phone">
                                        <label>Address</label>


                                        <textarea name="address" id="userAddress"
                                            class="form-control">{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->address) ? $user->details->getCompany->address : '' }}</textarea>


                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pad15 office_phone">
                                        <label>City</label>

                                        <input type="text" name="city"
                                            value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->city) ? $user->details->getCompany->city : '' }}"
                                            id="userCity" class="form-control">
                                    </div>


                                </div>

                            </div>
                            <div class="row row-first">
                                <div class="col-md-6">
                                    <div class="pad15 phone">
                                        <label>State</label>
                                        <select class="form-control" name="state" id="userState">
                                            <option value="">---Select---</option>
                                            @foreach ($states as $key => $state)
                                                <option value="{{ $key }}"
                                                    {{ $state_id == $key ? 'selected' : '' }}>{{ $state }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pad15 office_phone">
                                        <label>ZIP Code</label>
                                        <input type="text" name="zip"
                                            value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->zip) ? $user->details->getCompany->zip : '' }}"
                                            id="userZip" class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="row row-first">
                                <div class="col-md-6">
                                    <div class="pad15 phone">
                                        <label>Country</label>
                                        <input type="text" name="phone"
                                            value="{{ !is_null($user->details) && isset($user->details->phone) ? $user->details->phone : '' }}"
                                            id="phone" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pad15 office_phone">
                                        <label>Company Website</label>
                                        <input type="url" name="website"
                                            value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->website) ? $user->details->getCompany->website : '' }}"
                                            id="userWebsite" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row row-first">
                                <div class="col-md-6">
                                    <div class="pad15 phone">
                                        <label>Company</label>
                                        <input type="text" name="company"
                                               value="{{ isset($user->details->getCompany) ? $user->details->getCompany->company : '' }}"
                                               id="userCompany" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>

                            <div class="row row-first">
                                <div class="col-md-6">
                                    {{ csrf_field() }}
                                    <input type="submit" class="blue-primary-btn" style="display: inline-block; margin-top: 25px;"
                                        value="Save Changes">
                                </div>
                            </div>


                        </div>
                    </div>

                </form>
                <div class="create-project-form-bgcolor">
                    <div class="create-project-form-header">
                        <a name="d"></a>
                        <h2>Add / Edit Users</h2>
                    </div>
                    <div class="form-padding-wrapper text-left">
                        <div class="row row-first">
                            <div class="col-md-12">

                                <div class="profile-box">
                                    <h4>Sub-users:</h4>
                                    <table class="table profile-table">
                                        <thead>
                                            <th>Company Name</th>
                                            <th>Title</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Direct Phone</th>
                                            <th>Cell</th>
                                            <th>Actions</th>
                                        </thead>
                                        <tbody>
                                            @if (isset($subusers))
                                                @forelse($subusers as $subuser)
                                                    <tr>
                                                        <td>
                                                            <div class="pad15">
                                                                {{ isset($subuser->mapcompanyContacts) && isset($subuser->mapcompanyContacts->company) ? $subuser->mapcompanyContacts->company->company : 'N/A' }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="pad15">N/A</div>
                                                        </td>
                                                        <td>
                                                            <div class="pad15">
                                                                {{ isset($subuser->details) ? $subuser->details->first_name : 'N/A' }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="pad15">
                                                                {{ isset($subuser->details) ? $subuser->details->last_name : 'N/A' }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="pad15">{{ $subuser->email }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="pad15">
                                                                {{ isset($subuser->details) ? $subuser->details->phone : 'N/A' }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="pad15">
                                                                {{ isset($subuser->details) && !is_null($subuser->details->office_phone) ? $subuser->details->office_phone : 'N/A' }}
                                                            </div>
                                                        </td>


                                                        <!-- <td><div class="pad15">{{ isset($subuser->name) ? $subuser->name : '' }}</div></td>
                                                                         <td><div class="pad15">{{ !is_null($subuser->mapcompanyContacts) && isset($subuser->mapcompanyContacts->company) ? $subuser->mapcompanyContacts->company->company : '' }}</div></td>
                                                                         <td><div class="pad15">{{ isset($subuser->email) ? $subuser->email : '' }}</div></td> -->
                                                        <td>
                                                            <a href="javascript:void(0)" class="addSubUser"
                                                                data-subuser_id="{{ $subuser->id }}" data-type="edit"
                                                                data-first_name="{{ $subuser->details->first_name }}"
                                                                data-last_name="{{ $subuser->details->last_name }}"
                                                                data-company="{{ $subuser->mapcompanyContacts->company->company }}"
                                                                data-address="{{ $subuser->mapcompanyContacts->address }}"
                                                                data-city="{{ $subuser->mapcompanyContacts->city }}"
                                                                data-state="{{ $subuser->mapcompanyContacts->state_id }}"
                                                                data-phone="{{ $subuser->mapcompanyContacts->phone }}"
                                                                data-zip="{{ $subuser->mapcompanyContacts->zip }}"
                                                                data-email="{{ $subuser->email }}"
                                                                data-username="{{ $subuser->user_name }}"
                                                                data-password="{{ $subuser->password }}">EDIT
                                                            </a> |
                                                            <a href="javascript:void(0)" class="delete"
                                                                data-id="{{ $subuser->id }}">DELETE
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9">
                                                            <div class="text-center">No Results Found.</div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            @else
                                                <tr>
                                                    <td colspan="9">
                                                        <div class="text-center">No Results Found.</div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row row-first">
                                    <div class="col-md-6">
                                        <button style="display: inline-block; margin-top: 25px;" type="button" class="blue-primary-btn addSubUser" data-type="add">Create subusers</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>


                <div class="create-project-form-bgcolor">
                    <div class="create-project-form-header">
                        <a name="e"></a>
                        <h2>User Notification Settings</h2>
                    </div>
                    <form action="{{ route('member.action.notification') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-padding-wrapper text-left">
                            <div class="row row-first">
                                <div class="col-md-12">
                                    <h4 class="modal-title"><span class="modal_title"></span>Sort By:</h4>
                                    <div class="radio_button" style="padding-left: 30px">
                                        <label class="radio_label">
                                            <input type="radio" name="notify" value="7">7 Days
                                        </label>
                                        <br />
                                        <label class="radio_label">
                                            <input type="radio" name="notify" value="14">14 Days
                                        </label>
                                        <br />

                                        <label class="radio_label">
                                            <input type="radio" name="notify" value="21">21 Days
                                        </label>
                                        <br />
                                        <label class="radio_label">
                                            <input type="radio" name="notify" value="28">28 Days
                                        </label>
                                    </div>

                                    <div class="row row-first">
                                        <div class="col-md-6">
                                            <input type="submit" class="blue-primary-btn"
                                                style="display: inline-block; margin-top: 25px" value="Save Changes">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>


{{--         <div class="container">--}}
{{--         <div class="row">--}}
{{--         <div class="col-md-10 col-sm-12 col-md-offset-1">--}}
{{--         <div class="center-part">--}}
{{--         <div class="tab-area1">--}}
{{--         <ul class="nav nav-tabs">--}}
{{--         <li class="active"><a data-toggle="tab" href="#home">Update Profile </a></li>--}}
{{--         <li><a data-toggle="tab" href="javascript:void(0)">Financial Accounts </a></li>--}}
{{--         <li><a data-toggle="tab" href="javascript:void(0)">My Transactions </a></li>--}}
{{--         </ul>--}}


{{--         <div id="home" class="tab-pane fade in active">--}}
{{--         <div class="tab-content-body update-back">--}}
{{--         <div class="row">--}}
{{--         <div class="col-md-10 col-sm-10  col-sm-offset-1">--}}
{{--         <div class="profile-box">--}}
{{--         @if ($errors->any())--}}
{{--         <div class="alert alert-danger">--}}
{{--         <ul>--}}
{{--         @foreach ($errors->all() as $error)--}}
{{--         <li>{{ $error }}</li>--}}
{{--         @endforeach--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         @endif--}}
{{--         <div class="table-responsive">--}}
{{--         <form action="{{ route('member.action.profile') }}" method="post" enctype="multipart/form-data">--}}
{{--         {{ csrf_field() }}--}}
{{--         <table class="table profile-table">--}}
{{--         @if (Session::has('success-update'))--}}
{{--         <p class="alert alert-success">{{ Session::get('success-update') }}</p>--}}
{{--         @endif--}}
{{--         <tr class="table-select">--}}
{{--         <td colspan="3">--}}
{{--         <table class="table inner-table">--}}
{{--         <tr>--}}
{{--         <td colspan="2">--}}
{{--         <div class="pad15 email" id="email">--}}
{{--         <ul>--}}
{{--         <li>USER EMAIL</li>--}}
{{--         <li class="editEmail"><a href="javascript:void(0)">EDIT</a>--}}
{{--         </li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">--}}
{{--         <ul>--}}
{{--         <li>PASSWORD</li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td colspan="2">--}}
{{--         <div class="pad15 email">--}}
{{--         <input type="text" name="email"--}}
{{--         id="userEmail"--}}
{{--         class="form-control"--}}
{{--         value="{{ Auth::user()->email }}"--}}
{{--         >--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">--}}
{{--         <a href="#" data-toggle="modal"--}}
{{--         data-target="#myModal">CHANGE--}}
{{--         PASSWORD</a>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}

{{--         </table>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td colspan="3">--}}
{{--         <table class="table inner-table">--}}

{{--         <tr>--}}
{{--         <td>--}}
{{--         <div class="pad15 company">--}}
{{--         <ul>--}}
{{--         <li>COMPANY NAME--}}
{{--         </li>--}}
{{--         <li><a href="javascript:void(0)" class="editCompany">EDIT</a>--}}
{{--         </li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 name">--}}
{{--         <ul>--}}
{{--         <li>FIRST NAME--}}
{{--         </li>--}}
{{--         <li><a href="javascript:void(0)" class="editNames">EDIT</a>--}}
{{--         </li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 name">--}}
{{--         <ul>--}}
{{--         <li>LAST NAME</li>--}}
{{--         <li><a href="javascript:void(0)">EDIT</a></li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td>--}}
{{--         <div class="pad15 company">--}}
{{--         <input type="text" name="companyName" readonly--}}
{{--         value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->company)? $user->details->getCompany->company : ''}}"--}}
{{--         id="userCompany"--}}
{{--         class="form-control">--}}
{{--         <select readonly name="companyName" id="userCompany" class="form-control chosen">--}}
{{--         <option value=""></option>--}}
{{--         @isset($companies)--}}
{{--         @foreach ($companies as $key => $company)--}}
{{--         <option value="{{$key}}" @if (!is_null($user->details) && $user->details->company_id == $key){{'selected'}}@endif>{{$company}}</option>--}}
{{--         @endforeach--}}
{{--         @endisset--}}
{{--         </select>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 name">--}}
{{--         <div class="row">--}}
{{--         <input type="text"--}}
{{--         name="firstName"--}}
{{--         value="{{ !is_null($user->details) && isset($user->details->first_name)? $user->details->first_name : ''}}"--}}
{{--         id="userFirstName"--}}
{{--         class="form-control">--}}
{{--         </div>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 name">--}}
{{--         <input type="text"--}}
{{--         name="lastName"--}}
{{--         value="{{ !is_null($user->details) && isset($user->details->last_name)? $user->details->last_name : ''}}"--}}
{{--         id="userLastName"--}}
{{--         class="form-control">--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         </table>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td colspan="3">--}}
{{--         <table class="table inner-table">--}}

{{--         <tr>--}}
{{--         <td>--}}
{{--         <div class="pad15 image">--}}
{{--         <ul>--}}
{{--         <li>--}}
{{--         UPLOAD IMAGE--}}
{{--         </li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 current_image">--}}
{{--         <ul>--}}
{{--         <li>--}}
{{--         CURRENT IMAGE--}}
{{--         </li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td>--}}
{{--         <div class="pad15 image">--}}
{{--         <input type="file" name="image"--}}
{{--         value="{{ !is_null($user->details) && isset($user->details->image) ? $user->details->image : ''}}"--}}
{{--         id="image"--}}
{{--         class="form-control">--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 current_image">--}}
{{--         @if (!is_null($user->details) && $user->details->image != '')--}}
{{--         <img src="{{ env('ASSET_URL') }}/image_logo/{{ $user->details->image }}" alt="Logo" class="pull-right" height="50px">--}}
{{--         @else--}}
{{--         <img src="{{ env('ASSET_URL') }}/images/avatar5.png" alt="Logo" class="pull-right" height="50px">--}}
{{--         @endif--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         </table>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td colspan="3">--}}
{{--         <table class="table inner-table">--}}

{{--         <tr>--}}
{{--         <td width="50%">--}}
{{--         <div class="pad15 phone">--}}
{{--         <ul>--}}
{{--         <li>PHONE--}}
{{--         </li>--}}
{{--         <li><a href="javascript:void(0)" class="editCompany">EDIT</a>--}}
{{--         </li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td width="50%">--}}
{{--         <div class="pad15 office_phone">--}}
{{--         <ul>--}}
{{--         <li>OFFICE PHONE--}}
{{--         </li>--}}
{{--         <li><a href="javascript:void(0)" class="editNames">EDIT</a>--}}
{{--         </li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td width="50%">--}}
{{--         <div class="pad15 phone">--}}
{{--         <input type="text" name="phone"--}}
{{--         value="{{ !is_null($user->details) && isset($user->details->phone)? $user->details->phone : ''}}"--}}
{{--         id="phone"--}}
{{--         class="form-control">--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td width="50%">--}}
{{--         <div class="pad15 office_phone">--}}

{{--         <input type="text"--}}
{{--         name="office_phone"--}}
{{--         value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->phone) ? $user->details->getCompany->phone : ''}}"--}}
{{--         id="office_phone"--}}
{{--         class="form-control">--}}

{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         </table>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td colspan="3">--}}
{{--         <table class="table inner-table">--}}

{{--         <tr>--}}
{{--         <td width="50%">--}}
{{--         <div class="pad15 address">--}}
{{--         <ul>--}}
{{--         <li>ADDRESS</li>--}}
{{--         <li><a href="javascript:void(0)" class="editAddress">EDIT</a>--}}
{{--         </li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td width="50%">--}}
{{--         <div class="pad15 website">--}}
{{--         <ul>--}}
{{--         <li>COMPANY WEBSITE</li>--}}
{{--         <li><a href="javascript:void(0)" class="editWebsite">EDIT</a></li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}

{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td width="50%">--}}
{{--         <div class="pad15 address">--}}
{{--         <textarea name="address"--}}
{{--         id="userAddress"--}}
{{--         class="form-control">{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->address)? $user->details->getCompany->address : ''}}</textarea>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td width="50%">--}}
{{--         <div class="pad15 website">--}}
{{--         <input type="url" name="website"--}}
{{--         value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->website)? $user->details->getCompany->website : ''}}"--}}
{{--         id="userWebsite"--}}
{{--         class="form-control">--}}
{{--         </div>--}}
{{--         </td>--}}


{{--         </tr>--}}

{{--         </table>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td colspan="3">--}}
{{--         <table class="table inner-table">--}}

{{--         <tr>--}}
{{--         <td>--}}
{{--         <div class="pad15 city">--}}
{{--         <ul>--}}
{{--         <li>CITY</li>--}}
{{--         <li><a href="javascript:void(0)" class="editCity">EDIT</a>--}}
{{--         </li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 state">--}}
{{--         <ul>--}}
{{--         <li>STATE</li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 zip">--}}
{{--         <ul>--}}
{{--         <li>ZIP CODE</li>--}}
{{--         <li><a href="javascript:void(0)" class="editZip">EDIT</a></li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">--}}
{{--         <ul>--}}
{{--         <li>COUNTRY</li>--}}
{{--         </ul>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td>--}}
{{--         <div class="pad15 city">--}}
{{--         <input type="text" name="city"--}}
{{--         value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->city)? $user->details->getCompany->city : ''}}"--}}
{{--         id="userCity"--}}
{{--         class="form-control">--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 state">--}}
{{--         <select class="form-control"--}}
{{--         name="state" id="userState">--}}
{{--         <option value="">---Select---</option>--}}
{{--         @foreach ($states as $key => $state)--}}
{{--         <option value="{{$key}}" {{ ($state_id == $key) ? 'selected' : '' }}>{{$state}}</option>--}}
{{--         @endforeach--}}
{{--         </select>--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15 zip">--}}
{{--         <input type="text" name="zip"--}}
{{--         value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->zip)? $user->details->getCompany->zip : ''}}"--}}
{{--         id="userZip"--}}
{{--         class="form-control">--}}
{{--         </div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">--}}
{{--         <select class="form-control">--}}
{{--         <option>United States</option>--}}
{{--         </select>--}}
{{--         <input type="hidden" name="company_name" id="company_name"--}}
{{--         value="{{ !is_null($user->details) && !is_null($user->details->getCompany) ?  $user->details->getCompany->company : '' }}">--}}

{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         </table>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         <tr>--}}
{{--         <td colspan="3">--}}
{{--         <div class="row">--}}


{{--         <div class="align-left col-md-4 col-sm-4 col-xs-4">--}}
{{--         <button type="button" class="blue-btn trigger-modal addSubUser" data-type="add"--}}
{{--         >Create subusers--}}
{{--         </button>--}}
{{--         </div>--}}

{{--         <div class="align-right col-md-4 col-sm-4 col-xs-4 col-md-push-4">--}}
{{--         <a class="save-btn" href="#">Save Changes</a>--}}
{{--         <input type="submit" class="blue-btn save-btn align-right"--}}
{{--         style="display: inline-block" value="Save Changes">--}}
{{--         </div>--}}

{{--         </div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         </table>--}}
{{--         </form>--}}

{{--         <div class="profile-box">--}}
{{--         <h4>Sub-users:</h4>--}}
{{--         <table class="table profile-table">--}}
{{--         <thead>--}}
{{--         <th>Company Name</th>--}}
{{--         <th>Title</th>--}}
{{--         <th>First Name</th>--}}
{{--         <th>Last Name</th>--}}
{{--         <th>Email</th>--}}
{{--         <th>Direct Phone</th>--}}
{{--         <th>Cell</th>--}}
{{--         <th>Actions</th>--}}
{{--         </thead>--}}
{{--         <tbody>--}}
{{--         @if (isset($subusers))--}}
{{--         @forelse($subusers as $subuser)--}}
{{--         <tr>--}}
{{--         <td>--}}
{{--         <div class="pad15">{{isset($subuser->mapcompanyContacts) && isset($subuser->mapcompanyContacts->company) ? $subuser->mapcompanyContacts->company->company : 'N/A'}}</div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">N/A</div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">{{isset($subuser->details) ? $subuser->details->first_name : 'N/A' }}</div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">{{isset($subuser->details) ? $subuser->details->last_name : 'N/A' }}</div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">{{$subuser->email}}</div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">{{isset($subuser->details) ? $subuser->details->phone : 'N/A' }}</div>--}}
{{--         </td>--}}
{{--         <td>--}}
{{--         <div class="pad15">{{isset($subuser->details) && !is_null($subuser->details->office_phone) ? $subuser->details->office_phone : 'N/A' }}</div>--}}
{{--         </td>--}}


{{--         <!-- <td><div class="pad15">{{isset($subuser->name) ? $subuser->name : '' }}</div></td>--}}
{{--         <td><div class="pad15">{{!is_null($subuser->mapcompanyContacts) && isset($subuser->mapcompanyContacts->company) ? $subuser->mapcompanyContacts->company->company : ''}}</div></td>--}}
{{--         <td><div class="pad15">{{isset($subuser->email) ? $subuser->email : ''}}</div></td> -->--}}
{{--         <td>--}}
{{--         <a href="javascript:void(0)" class="addSubUser"--}}
{{--         data-subuser_id="{{ $subuser->id }}"--}}
{{--         data-type="edit"--}}
{{--         data-first_name="{{ $subuser->details->first_name }}"--}}
{{--         data-last_name="{{ $subuser->details->last_name }}"--}}
{{--         data-company="{{ $subuser->mapcompanyContacts->company->company }}"--}}
{{--         data-address="{{ $subuser->mapcompanyContacts->address }}"--}}
{{--         data-city="{{ $subuser->mapcompanyContacts->city }}"--}}
{{--         data-state="{{ $subuser->mapcompanyContacts->state_id }}"--}}
{{--         data-phone="{{ $subuser->mapcompanyContacts->phone }}"--}}
{{--         data-zip="{{ $subuser->mapcompanyContacts->zip }}"--}}
{{--         data-email="{{ $subuser->email }}"--}}
{{--         data-username="{{ $subuser->user_name }}"--}}
{{--         data-password="{{ $subuser->password }}"--}}
{{--         >EDIT--}}
{{--         </a> |--}}
{{--         <a href="javascript:void(0)" class="delete" data-id="{{ $subuser->id }}">DELETE--}}
{{--         </a>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         @empty--}}
{{--         <tr>--}}
{{--         <td colspan="9">--}}
{{--         <div class="text-center">No Results Found.</div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         @endforelse--}}
{{--         @else--}}
{{--         <tr>--}}
{{--         <td colspan="9">--}}
{{--         <div class="text-center">No Results Found.</div>--}}
{{--         </td>--}}
{{--         </tr>--}}
{{--         @endif--}}
{{--         </tbody>--}}
{{--         </table>--}}
{{--         </div>--}}
{{--         </div>--}}
{{--         </div>--}}
{{--         </div>--}}
{{--         </div>--}}
{{--         </div>--}}
{{--         </div>--}}

{{--         </div>--}}
{{--         <div id="menu1" class="tab-pane fade">--}}
{{--         <h3>Menu 1</h3>--}}
{{--         <p>Some content in menu 1.</p>--}}
{{--         </div>--}}
{{--         <div id="menu2" class="tab-pane fade">--}}
{{--         <h3>Menu 3</h3>--}}
{{--         <p>Some content in menu 1.</p>--}}
{{--         </div>--}}
{{--         </div>--}}
{{--         </div>--}}
{{--         </div>--}}
{{--         </div>--}}




    @endsection


    @section('modal')
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Change Password</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="#" method="post" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Enter old password : </label>
                                        <div class="col-md-8">
                                            <input type="password" id="oldPassword" name="oldPassword"
                                                class="form-control input">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Enter new password : </label>
                                        <div class="col-md-8">
                                            <input type="password" id="newPassword" name="newPassword"
                                                class="form-control input">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Re-enter new password : </label>
                                        <div class="col-md-8">
                                            <input type="password" id="cNewPassword" name="cNewPassword"
                                                class="form-control input">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4 error"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="button" class="blue-primary-btnchange-password">
                                                Change password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        @include('basicUser.modals.sub_user_modal',['user' => $user,'states' => $states])
    @endsection


    @section('script')
        <script>
            /*   var chosen = $('#userCompany').chosen({width: "100%",no_results_text: "Oops, nothing found! <a class='add_company_from_search'>Click here to add company</a>"})
                                   .change(
                                           function () {
                                               if($(this).val() != "new_data") {
                                                   $.ajax({
                                                       url: '{{ route('autocomplete.company') }}',
                                            dataType: "json",
                                            data: {
                                                key: $(this).val(),
                                                _token: '{{ csrf_token() }}'
                                            },
                                            success: function (response) {
                                                if(response.success && response.data !==  null) {
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
                    $('#company_name').val('');
                    $('#userWebsite').val("");
                    $('#userAddress').val("");
                    $('#userCity').val("");
                    $('#userState').val("");
                    $('#userZip').val("");
                    $('#office_phone').val("");
                    $('#fax').val("");
                }

                function setData(response) {
                    $('#company_id').val(response.data.company);
                    $('#userWebsite').val(response.data.website);
                    $('#userAddress').val(response.data.address);
                    $('#userCity').val(response.data.city);
                    $('#userState').val(response.data.state_id);
                    $('#userZipuserZip').val(response.data.zip);
                    $('#office_phone').val(response.data.phone);
                    $('#fax').val(response.data.fax);
                }

                $(document).delegate('.add_company_from_search','click',function () {
                    var company = chosen.data('chosen').get_search_text();
                    $("#userCompany option[value='new_data']").remove();
                    $('#userCompany').append("<option value='new_data'>"+company+"</option>");
                    $('#userCompany').val('new_data'); // if you want it to be automatically selected
                    $('#userCompany').trigger("chosen:updated");
                    $('#company_name').val(company);

                    $('#userWebsite').val("");
                    $('#userAddress').val("");
                    $('#userCity').val("");
                    $('#userState').val("");
                    $('#userZip').val("");
                    $('#office_phone').val("");
                    $('#fax').val("");
                });*/

            $(document).ready(function() {
                $('#addUserModel').on('hidden.bs.modal', function() {
                    $('#addUserForm').trigger("reset");
                });

                $('.editEmail').on('click', function() {
                    $('.email').addClass('white-bg');
                    $('#userEmail').removeAttr("readonly");

                });
                $('.editCompany').on('click', function() {
                    $('.company').addClass('white-bg');
                    $('#userCompany').removeAttr("readonly");

                });
                $('.editNames').on('click', function() {
                    $('.name').addClass('white-bg');
                    $('#userFirstName').removeAttr("readonly");
                    $('#userLastName').removeAttr("readonly");

                });
                $('.editAddress').on('click', function() {
                    $('.address').addClass('white-bg');
                    $('#userAddress').removeAttr("readonly");
                });
                $('.editCity').on('click', function() {
                    $('.city').addClass('white-bg');
                    $('#userCity').removeAttr("readonly");
                });
                $('.editZip').on('click', function() {
                    $('.zip').addClass('white-bg');
                    $('#userZip').removeAttr("readonly");
                });
                $('.editWebsite').on('click', function() {
                    $('.website').addClass('white-bg');
                    $('#userWebsite').removeAttr("readonly");
                });
                $('.change-password').on('click', function() {
                    var oldPassword = $('#oldPassword').val();
                    var newPassword = $('#newPassword').val();
                    var cNewPassword = $('#cNewPassword').val();
                    var flag = true;
                    var html = '';
                    if (oldPassword == '') {
                        $('#oldPassword').addClass('input-error');
                        html += '<p style="color: red;">Please enter your old password</p>';
                        flag = false;
                    }
                    if (newPassword == '') {
                        $('#newPassword').addClass('input-error');
                        html += '<p style="color: red;">Please enter a new password</p>';
                        flag = false;
                    }
                    if (cNewPassword == '') {
                        $('#cNewPassword').addClass('input-error');
                        html += '<p style="color: red;">Please re-enter your new password</p>';
                        flag = false;
                    }
                    if (newPassword != cNewPassword) {
                        $('#cNewPassword').addClass('input-error');
                        html += '<p style="color: red;">password and confirm password does not match</p>';
                        flag = false;
                    }
                    if (flag) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('member.change.password') }}",
                            data: {
                                user_id: '{{ Auth::user()->id }}',
                                oldPassword: oldPassword,
                                newPassword: newPassword,
                                cNewPassword: cNewPassword,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                if (data.status) {
                                    swal({
                                        title: "Success!",
                                        text: "Password changed successfully",
                                        type: "success"
                                    }).then(function(isConfirm) {
                                        window.location.reload();
                                    });
                                } else {
                                    $('.error').html('<p class="input-error" style="color: red;">' +
                                        data.message + '</p>');
                                }
                            }
                        });
                    } else {
                        $('.error').html(html);
                    }

                });
                $('.input').on('focus', function() {
                    $('#oldPassword').removeClass('input-error');
                    $('#newPassword').removeClass('input-error');
                    $('#cNewPassword').removeClass('input-error');
                    $('.error').html('');
                });
            });

            $('.addSubUser').click(function() {
                // $('.title').html('Add');
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
                $('#error-message').html('');
                $('#addUserModel').modal('show');
            });

            $('.formSubmit').on('click', function() {
                var company = $('#company1').val();
                var flag = true;
                var id = $('#companyId').val();
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
                var type = $(this).data('type');
                if (type == 'add') {
                    var password = $('#password1').val();
                    if (password == '') {
                        $('#password1').addClass('input-error');
                        flag = false;
                    }
                } else {
                    if($('#password1').val() != '') {
                        var password = $('#password1').val();
                    }

                }


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
                            company_id: id,
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
                            password: password,
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
                                /* $('#error-message').html('<p class="input-success">' + data.message + '</p>');
                                 location.reload();*/

                            } else {
                                html = '';
                                $.each(data.message, function(index, value) {
                                    html += '<p class="input-error">' + value + '</p>'
                                });
                                $('#error-message').html(html);
                                // $('#error-message').html('<p class="input-error">' + data.message + '</p>');
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
        </script>
    @endsection
