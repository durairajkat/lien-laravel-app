@extends('basicUser.layout.main')

@section('title', 'Industry Contacts')
{{-- @section('style')
    <style>
        .input-error {
            border: 2px solid red;

        }
    </style>
@endsection --}}

@section('content')
    <style>
        .fa-sort {
            display: inline-block;
            font-size: 1.2em;
        }

    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-sm-10 col-sm-offset-1">
                <div class="table-block">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <h2>Contacts</h2>
                        </div>
                        <div class="col-md-7 col-sm-7 table-middle">
                            <ul class="contactsList">
                                <li><a href="{{ route('member.contacts.contacts') }}" {{-- class="customerContacts" --}}>Customer
                                        Contacts</a></li>
                                <li class="select"><a href="{{ route('member.contacts.industry') }}"
                                        {{-- class="industryContacts" --}}>Industry
                                        Contacts</a></li>
                                {{-- <li><a href="javascript:void(0)" class="addCustomerButton" data-type="add" data-toggle="modal" ><span><i class="fa fa-plus-circle" aria-hidden="true"></i></span> Create New customer</a></li> --}}
                                <li><a href="javascript:void(0)" class="addIndustryButton" data-type="Add"
                                        data-contact="industry" data-toggle="modal"><span><i class="fa fa-plus-circle"
                                                aria-hidden="true"></i></span> Create New
                                        Industry</a></li>
                            </ul>
                        </div>
                        <span class="search dropdown-toggle" id="dropdownMenuButton1" role="alert" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <a href="#">
                                <img src="{{ env('ASSET_URL') }}/images/search.png" alt="img" class="search-img">
                            </a>
                        </span>
                        <div class="dropdown-menu dropdown-search" aria-labelledby="dropdownMenuButton" style="top:40px;">

                            <input type="text" id="projectSearch" placeholder="Search.."
                                value="{{ isset($_GET['search']) && $_GET['search'] != '' ? $_GET['search'] : '' }}"
                                class="form-control">
                            <button type="submit" data-type="project" class="btn s-button search1"><i class="fa fa-search"
                                    aria-hidden="true"></i></button>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="table-responsive white-table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Role
                                                <i class="fa fa-sort" aria-hidden="true" data-col="contact_type"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'contact_type' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}"></i>
                                            </th>
                                            <th>
                                                Company
                                                <i class="fa fa-sort" aria-hidden="true" data-col="industry_name"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'industry_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}"></i>
                                            </th>
                                            <th>
                                                Title
                                                <i class="fa fa-sort" aria-hidden="true" data-col="title"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'title' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}"></i>
                                            </th>
                                            <th>
                                                First Name
                                                <i class="fa fa-sort" aria-hidden="true" data-col="first_name"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'first_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}"></i>
                                            </th>
                                            <th>
                                                Last Name
                                                <i class="fa fa-sort" aria-hidden="true" data-col="last_name"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'last_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}"></i>
                                            </th>
                                            <th>
                                                Email
                                                <i class="fa fa-sort" aria-hidden="true" data-col="email"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'email' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}"></i>
                                            </th>
                                            <th>
                                                Direct Phone
                                                <i class="fa fa-sort" aria-hidden="true" data-col="phone"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'phone' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}"></i>
                                            </th>
                                            <th>
                                                Cell
                                                <i class="fa fa-sort" aria-hidden="true" data-col="cell"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'cell' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}"></i>
                                            </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($contacts) > 0)
                                            @foreach ($contacts as $key => $contact)
                                                <tr>
                                                    <td>{{ $contact->contact_type }}</td>
                                                    <!--td>{{ !is_null($contact->getCompany) ? $contact->getCompany->company : 'N/A' }}</td-->
                                                    <td>{{ $contact->company }}</td>
                                                    <td>{{ $contact->title }}</td>
                                                    <td>{{ $contact->first_name }}</td>
                                                    <td>{{ $contact->last_name }}</td>
                                                    <td>{{ $contact->email }}</td>
                                                    <td>{{ $contact->phone }}</td>
                                                    <td>{{ $contact->cell }}</td>
                                                    <td>
                                                        {{-- @php
                                                         $allContacts = [];
                                                         if(!is_null($contact->mapContactCompany)) {
                                                             foreach ($contact->mapContactCompany as $eachMap) {
                                                                 $allContacts[] = $eachMap->contacts;
                                                             }
                                                         }
                                                         $allContacts = json_encode($allContacts);
                                                     @endphp --}}
                                                        {{-- <a href="javascript:void(0)" class="addCustomerButton"
                                                       data-id="{{ $contact->id }}"
                                                       data-type="edit"
                                                       data-company="{{ $contact->company }}"
                                                       data-website="{{ $contact->website }}"
                                                       data-address="{{ $contact->address }}"
                                                       data-city="{{ $contact->city }}"
                                                       data-state="{{ $contact->state_id }}"
                                                       data-phone="{{ $contact->phone }}"
                                                       data-zip="{{ $contact->zip }}"
                                                       data-fax="{{ $contact->fax }}"
                                                       data-contactInformation="{{  (isset($allContacts) && count($allContacts) > 0 ) ? $allContacts : "[]" }}"
                                                    >EDIT
                                                    </a> --}}
                                                        @php
                                                            $individualContact = [];
                                                            $individualContact[] = $contact;
                                                        @endphp
                                                        <a href="javascript:void(0)" class="addIndustryButton"
                                                            data-id="{{ !is_null($contact->getCompany) ? $contact->getCompany->id : 0 }}"
                                                            data-type="Edit" data-contact="industry"
                                                            data-contact_type="{{ $contact->contact_type }}"
                                                            data-company="{{ $contact->company }}"
                                                            data-website="{{ !is_null($contact->getCompany) ? $contact->getCompany->website : '' }}"
                                                            data-address="{{ !is_null($contact->mapContactCompany) ? $contact->mapContactCompany->address : '' }}"
                                                            data-city="{{ !is_null($contact->mapContactCompany) ? $contact->mapContactCompany->city : '' }}"
                                                            data-state="{{ !is_null($contact->mapContactCompany) ? $contact->mapContactCompany->state_id : '' }}"
                                                            data-phone="{{ !is_null($contact->mapContactCompany) ? $contact->mapContactCompany->phone : '' }}"
                                                            data-zip="{{ !is_null($contact->mapContactCompany) ? $contact->mapContactCompany->zip : '' }}"
                                                            data-fax="{{ !is_null($contact->mapContactCompany) ? $contact->mapContactCompany->fax : '' }}"
                                                            data-contactInformation="{{ isset($individualContact) && count($individualContact) > 0 ? json_encode($individualContact) : '[]' }}">EDIT
                                                        </a> |
                                                        <a href="javascript:void(0)" class="delete"
                                                            data-id="{{ $contact->id }}">DELETE

                                                        </a>
                                                    </td>
                                                </tr>

                                                {{-- <tr>
                                                <td>{{ (!is_null($industry->mapContactCompany()->first()) && !is_null($industry->mapContactCompany()->first()->contacts)) ? $industry->mapContactCompany()->first()->contacts->contact_type : "" }}</td>
                                                <td>{{ $industry->company }}</td>
                                                <td>{{ $industry->state->name }}</td>

                                                @php
                                                    $allContacts = [];
                                                    if(!is_null($industry->mapContactCompany)) {
                                                        foreach ($industry->mapContactCompany as $eachMap) {
                                                            $allContacts[] = $eachMap->contacts;
                                                        }
                                                    }
                                                    $allContacts = json_encode($allContacts);
                                                @endphp

                                                <td>
                                                    <a href="javascript:void(0)" class="addIndustryButton"
                                                       data-id="{{ $industry->id }}"
                                                       data-type="edit"
                                                       data-Contact_type="{{ (!is_null($industry->mapContactCompany()->first()) && !is_null($industry->mapContactCompany()->first()->contacts)) ? $industry->mapContactCompany()->first()->contacts->contact_type : ""  }}"
                                                       data-company="{{ $industry->company }}"
                                                       data-website="{{ $industry->website }}"
                                                       data-address="{{ $industry->address }}"
                                                       data-city="{{ $industry->city }}"
                                                       data-state="{{ $industry->state_id }}"
                                                       data-zip="{{ $industry->zip }}"
                                                       data-phone="{{ $industry->phone }}"
                                                       data-fax="{{ $industry->fax }}"
                                                       data-contactInformation="{{  (isset($allContacts) && count($allContacts) > 0 ) ? $allContacts : "[]" }}"
                                                    >EDIT
                                                    </a>
                                                    |
                                                    <a href="javascript:void(0)" class="delete"
                                                       data-id="{{ $industry->id }}">DELETE

                                                    </a>
                                                </td>
                                            </tr> --}}
                                            @endforeach
                                            <tr>
                                                <td colspan="9">
                                                    {{ $contacts->appends($_GET)->links() }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="9">
                                                    <select name="paginate" id="paginate">
                                                        <option value="" @if (isset($_GET['paginate']) && $_GET['paginate'] == ''){{ 'selected' }}@endif>---Select---</option>
                                                        <option value="20" @if (isset($_GET['paginate']) && $_GET['paginate'] == '20'){{ 'selected' }}@endif>20</option>
                                                        <option value="50" @if (isset($_GET['paginate']) && $_GET['paginate'] == '50'){{ 'selected' }}@endif>50</option>
                                                        <option value="100" @if (isset($_GET['paginate']) && $_GET['paginate'] == '100'){{ 'selected' }}@endif>100</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="9"> No contact found...</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h2>Project Actions
                                <img data-toggle="tooltip" title="Manage Projects"
                                    src="{{ env('ASSET_URL') }}/images/ask.png" alt="img">
                            </h2>
                            <div class="white-box ">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4">
                                        <a href="{{ route('admin.new.claim_step1') }}">
                                            <div class="sky-box">
                                                <h3>File Your Claim <br>
                                                    (Lien | Notice)
                                                </h3>
                                                <img src="{{ env('ASSET_URL') }}/images/claim-icon.png" alt="img">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                        <a href="{{ route('member.get.collect.receivables') }}">
                                            <div class="sky-box">
                                                <h3>Collect Receivables</h3>
                                                <img src="{{ env('ASSET_URL') }}/images/collect-icon.png" alt="img">
                                            </div>
                                        </a>
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
                                        <a href="{{ route('member.get.consultation') }}">
                                            <div class="sky-box">
                                                <h3>Consultation</h3>
                                                <img src="{{ env('ASSET_URL') }}/images/consultation-icon.png"
                                                    alt="img">
                                            </div>
                                        </a>
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('basicUser.modals.contact_modal',['companies' => $companies,'first_names' => $first_names])
    <!-- Modal -->
    {{-- <div id="addIndustryModel" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="title"></span> Industry Contact</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="IndustryEditForm" method="post" action="#">
                        <div class="form-group">
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
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Company : </label>
                            <div class="col-md-8">
                                <input class="form-control error autocomplete" type="text" name="company" id="company1"
                                       placeholder="Enter Company Name"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Website : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="website" id="website1"
                                       placeholder="Enter Website"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Address : </label>
                            <div class="col-md-8">
                                <textarea name="address" class="form-control error" placeholder="Enter Address"
                                          id="address1"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">City : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="city" id="city1"
                                       placeholder="Enter City"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">State <span>*</span>: </label>
                            <div class="col-md-8">
                                <select class="form-control error" name="state" id="state1">
                                    <option value="">Select a state</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Zip : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="zip" id="zip1"
                                       placeholder="Enter Zip Code"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Company Phone : </label>
                            <div class="col-md-8">
                                <input class="form-control error phone1" type="text" name="phone1" id="phone1"
                                       placeholder="Enter Phone Number"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Fax : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="fax" id="fax1"
                                       placeholder="Enter Fax Number"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Contacts : </label>
                        </div>
                        <div class="form-group field_wrapper2">
                            <div class="col-md-12">
                                <table class="table field_wrapper3">
                                    <thead>
                                    <tr>
                                        <td>Title</td>
                                        <td>First Name</td>
                                        <td>Last Name</td>
                                        <td>Email</td>
                                        <td>Direct Phone</td>
                                        <td>Cell</td>
                                        <td>Action</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control error title1" name="title[]" id="title1">
                                                <option value="CEO">CEO</option>
                                                <option value="CFO">CFO</option>
                                                <option value="Credit">Credit</option>
                                                <option value="PM">PM</option>
                                                <option value="Corporation Counsel">Corporation Counsel</option>
                                                <option value="A/R Manager">A/R Manager</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div class="title_other" style="display: none;">
                                                <input type="text" id="title_other" name="titleOther[]"
                                                       class="form-control error">
                                                <a href="#" class="titleOtherBtn">Change</a>
                                            </div>
                                        </td>
                                        <td>
                                            <input class="form-control error autocomplete_contact_first_name" type="text" name="firstName[]"
                                                   id="firstName1"
                                                   placeholder="First Name"/>
                                        </td>
                                        <td>
                                            <input class="form-control error" type="text" name="lastName[]"
                                                   id="lastName1"
                                                   placeholder="Last Name"/>
                                        </td>
                                        <td>
                                            <input class="form-control error" type="email" name="email[]" id="email1"
                                                   placeholder="Email"/>
                                        </td>
                                        <td>
                                            <input class="form-control error" type="text" name="directPhone[]"
                                                   id="directPhone1"
                                                   placeholder="Direct Phone"/>
                                        </td>
                                        <td>
                                            <input class="form-control error" type="text" name="cellPhone[]"
                                                   id="cellPhone1"
                                                   placeholder="Cell Phone"/>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="add_button1" title="Add field"><img
                                                        src="{{ env('ASSET_URL') }}/images/add-icon.png" height="30px"
                                                        width="30px"/></a>
                                            <input class="form-control error" type="hidden" name="contactId[]"
                                                   id="contactId1"/>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div id="success-message">

                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="0" id="id1">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn blue-btn form-control formSubmitIndustry" type="button"><span
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
        var token = '{{ csrf_token() }}';
        var baseUrl = "{{ env('ASSET_URL') }}";
        var user_id = "{{ Auth::user()->id }}";
        var autoComplete = "{{ route('autocomplete.contact.company') }}";
        var autoCompleteCompany = "{{ route('autocomplete.company') }}";
        var autoCompleteContact = "{{ route('autocomplete.contact.firstname') }}";
        var autoCompleteCompanyOnRoleChange = "{{ route('autocomplete.contact.company.rolechange') }}";
        var getContactData = "{{ route('get.contact.data') }}";
        var newContacts = "{{ route('create.new.contacts') }}";
        var deleteContacts = "{{ route('customer.delete.contact') }}";
        var url = '{{ env('ASSET_URL') }}';
        $(document).ready(function() {
            $('.search1').on('click', function() {
                var type = $(this).data('type');
                if (type == 'project') {
                    var project = $('#projectSearch').val();
                    var location = appendToQueryString('search', project);
                    window.location.href = location;
                }

            });
        })
    </script>
    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/industry_contacts.js"></script>
    {{-- <script>
        $(document).ready(function () {
            /* $('.industry').hide();
             $('.addIndustryButton').hide();*/
            $('.industryContacts').on('click', function () {
                $('.industry').show();
                $('.customer').hide();
                $('.addCustomerButton').hide();
                $('.addIndustryButton').show();

            });
            $('.customerContacts').on('click', function () {
                $('.industry').hide();
                $('.customer').show();
                $('.addCustomerButton').show();
                $('.addIndustryButton').hide();

            });
            $('.contactsList > li').on('click', function () {
                $('.contactsList > li').removeClass("select");
                $(this).addClass('select');

            });
            $('.addCustomerButton').on('click', function () {
                $('#customerContactForm')[0].reset();
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
                    // $('#phone').val($(this).data('phone'));
                    var phone = $(this).data('phone');
                    var html = '';
                    $.each(phone, function (index, value) {
                        if (index == 0) {
                            $('#phone').val(value)
                        } else {
                            html += '<div style="padding-top:50px;">' +
                                '<div class="col-md-4">&nbsp;</div>' +
                                '<div class="col-md-6"><input type="text" name="phone[]" value="' + value + '" class="form-control error phone" placeholder="Enter Phone Number"/></div>' +
                                '<div class="col-md-2"><a href="javascript:void(0);" class="remove_button" title="Remove field">' +
                                '<img src="{{ env('ASSET_URL') }}/images/remove-icon.png" height="30px" width="30px"/></a></div></div>';
                        }
                    });
                    $('.field_wrapper1').html(html);
                    $('#fax').val($(this).data('fax'));
                    $('#email').val($(this).data('email'));
                    $('#id').val($(this).data('id'));
                    $('.formSubmit').attr('data-type', 'edit');
                    $('.title').html('Edit');
                }
                $('#addCustomerModel').modal('show');
            });
            $('.addIndustryButton').on('click', function () {
                $('#IndustryEditForm')[0].reset();
                var type = $(this).data('type');
                $('.external').remove();
                $('#contactId1').val(0);
                if (type == 'add') {
                    $('.formSubmit').attr('data-type', 'add');
                    $('.title').html('Add');
                } else {
                    $('#contactType1').val($(this).data('contact_type'));
                    $('#company1').val($(this).data('company'));
                    $('#website1').val($(this).data('website'));
                    $('#address1').val($(this).data('address'));
                    $('#city1').val($(this).data('city'));
                    $('#state1').val($(this).data('state'));
                    $('#zip1').val($(this).data('zip'));
                    $('#phone1').val($(this).data('phone'));
                    $('#fax1').val($(this).data('fax'));
                    var contactInformation = $(this).data('contactinformation');
                    var html = '';
                    if (contactInformation.length > 0) {
                        $.each(contactInformation, function (index, value) {
                            if (index == 0) {
                                if (value.title == 'Other') {
                                    $('#title1').hide();
                                    $('#title_other').show();
                                }
                                $('#title1').val(value.title);
                                $('#title_other').val(value.title_other);
                                $('#firstName1').val(value.first_name);
                                $('#lastName1').val(value.last_name);
                                $('#email1').val(value.email);
                                $('#directPhone1').val(value.phone);
                                $('#cellPhone1').val(value.cell);
                                $('#contactId1').val(value.id);
                            } else {
                                var title_other = value.title_other != null ? value.title_other : '';
                                var firstName = value.first_name != null ? value.first_name : '';
                                var lastName = value.last_name != null ? value.last_name : '';
                                var email = value.email != null ? value.email : '';
                                var direct_phone = value.phone != null ? value.phone : '';
                                var cell = value.cell != null ? value.cell : '';
                                var id = value.id != null ? value.id : '';
                                if (value.title != '') {
                                    if (value.title != 'Other') {
                                        html += '<tr class="external"><td> <select class="form-control error title1" name="title[]">' +
                                            ' <option value="CEO" ' + (value.title === "CEO" ? "selected" : "") + '>CEO</option> ' +
                                            '<option value="CFO" ' + (value.title === "CFO" ? "selected" : "") + '>CFO</option> ' +
                                            '<option value="Credit" ' + (value.title === "Credit" ? "selected" : "") + '>Credit</option>' +
                                            ' <option value="PM" ' + (value.title === "PM" ? "selected" : "") + '>PM</option> ' +
                                            '<option value="Corporation Counsel" ' + (value.title === "Corporation Counsel" ? "selected" : "") + '>Corporation Counsel</option> ' +
                                            '<option value="A/R Manager" ' + (value.title === "A/R Manager" ? "selected" : "") + '>A/R Manager</option> ' +
                                            '<option value="Other">Other</option> </select>' +
                                            '<div class="title_other" style="display: none;"> ' +
                                            '<input type="text" name="titleOther[]" class="form-control error" value="' + title_other + '">' +
                                            ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>';
                                    } else {
                                        html += '<tr class="external"><td> <select class="form-control error title1" name="title[]"  style="display: none;">' +
                                            ' <option value="CEO">CEO</option> ' +
                                            '<option value="CFO" >CFO</option> ' +
                                            '<option value="Credit" >Credit</option> <option value="PM">PM</option> ' +
                                            '<option value="Corporation Counsel" >Corporation Counsel</option> ' +
                                            '<option value="A/R Manager">A/R Manager</option> ' +
                                            '<option value="Other">Other</option> </select>' +
                                            '<div class="title_other"> ' +
                                            '<input type="text" name="titleOther[]" class="form-control error" value="' + title_other + '">' +
                                            ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>';
                                    }
                                } else {
                                    html += '<tr class="external"><td> <select class="form-control error title1" name="title[]" >' +
                                        ' <option value="CEO">CEO</option> ' +
                                        '<option value="CFO">CFO</option> ' +
                                        '<option value="Credit">Credit</option> <option value="PM">PM</option> ' +
                                        '<option value="Corporation Counsel">Corporation Counsel</option> ' +
                                        '<option value="A/R Manager">A/R Manager</option> ' +
                                        '<option value="Other">Other</option> </select>' +
                                        '<div class="title_other" style="display: none;"> ' +
                                        '<input type="text" name="titleOther[]" class="form-control error" value="' + value.title_other + '">' +
                                        ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>';
                                }

                                html += '<td><input class="form-control error autocomplete_contact_first_name" type="text" name="firstName[]" value="' + firstName + '" placeholder="First Name"/></td>' +
                                    '<td><input class="form-control error" type="text" name="lastName[]" value="' + lastName + '" placeholder="Last Name"/></td>' +
                                    '<td><input class="form-control error" type="email" name="email[]" value="' + email + '" placeholder="Email"/></td>' +
                                    '<td><input class="form-control error" type="text" name="directPhone[]" value="' + direct_phone + '" placeholder="Direct Phone"/></td>' +
                                    '<td><input class="form-control error" type="text" name="cellPhone[]" value="' + cell + '" placeholder="Cell Phone"/></td>' +
                                    '<td><a href="javascript:void(0);" class="remove_button1" title="Remove field">' +
                                    '<img src="{{ env('ASSET_URL') }}/images/remove-icon.png" height="30px" width="30px"/></a><input class="form-control error" type="hidden" name="contactId[]" value="' + id + '" /></td></tr>';
                            }
                        });
                    }
                    $('.field_wrapper3 tr:last').after(html);
                    $('#id1').val($(this).data('id'));
                    $('.formSubmitIndustry').attr('data-type', 'edit');
                    $('.title').html('Edit');
                }
                $('#addIndustryModel').modal('show');
            });
            $('.formSubmit').on('click', function () {
                var company = $('#company').val();
                var flag = true;
                var id = $('#id').val();
                if (company == '') {
                    $('#company').addClass('input-error');
                    flag = false;
                }
                var firstName = $('#firstName').val();
                if (firstName == '') {
                    $('#firstName').addClass('input-error');
                    flag = false;
                }
                var lastName = $('#lastName').val();
                if (lastName == '') {
                    $('#lastName').addClass('input-error');
                    flag = false;
                }
                var address = $('#address').val();
                if (address == '') {
                    $('#address').addClass('input-error');
                    flag = false;
                }
                var city = $('#city').val();
                if (city == '') {
                    $('#city').addClass('input-error');
                    flag = false;
                }
                var state = $('#state').val();
                if (state == '') {
                    $('#state').addClass('input-error');
                    flag = false;
                }
                var zip = $('#zip').val();
                if (zip == '') {
                    $('#zip').addClass('input-error');
                    flag = false;
                }
                if (!validateNumber(zip, 'zip')) {
                    $('#zip').addClass('input-error');
                    flag = false;
                }
                var phone = $("input[name='phone[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();
                $.each(phone, function (index, value) {
                    if (value != '' && !validateNumber(value, 'phone')) {
                        $("input[name='phone[]']").addClass('input-error');
                        flag = false;
                    }
                });
                var fax = $('#fax').val();
                if (fax == '') {
                    $('#fax').addClass('input-error');
                    flag = false;
                }
                if (!validateNumber(fax, 'fax')) {
                    $('#fax').addClass('input-error');
                    flag = false;
                }
                var email = $('#email').val();
                if (email == '') {
                    $('#email').addClass('input-error');
                    flag = false;
                }
                if (!validateEmail(email)) {
                    $('#email').addClass('input-error');
                    flag = false;
                }
                var type = $(this).data('type');
                if (flag) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('create.contact') }}",
                        data: {
                            id: id,
                            type: type,
                            contact: '0',
                            company: company,
                            firstName: firstName,
                            lastName: lastName,
                            address: address,
                            city: city,
                            zip: zip,
                            phone: phone,
                            fax: fax,
                            email: email,
                            state: state,
                            user_id: '{{ Auth::user()->id }}',
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            if (data.status) {
                                $('#error-message').html('<p class="alert alert-success">' + data.message + '</p>');
                                $('#customerContactForm')[0].reset();
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                $('#error-message').html('<p class="alert alert-danger">' + data.message + '</p>');
                            }
                        }
                    });
                }
            });
            $('.formSubmitIndustry').on('click', function () {
                var company = $('#company1').val();
                var flag = true;
                var id = $('#id1').val();
                if (company == '') {
                    $('#company1').addClass('input-error');
                    flag = false;
                }
                var contactType = $('#contactType1').val();
                if (contactType == '') {
                    $('#contactType1').addClass('input-error');
                    flag = false;
                }
                var website = $('#website1').val();
                var address = $('#address1').val();
                /*if (address == '') {
                    $('#address1').addClass('input-error');
                    flag = false;
                }*/
                var city = $('#city1').val();
                /*if (city == '') {
                    $('#city1').addClass('input-error');
                    flag = false;
                }*/
                var state = $('#state1').val();
                if (state == '') {
                    $('#state1').addClass('input-error');
                    flag = false;
                }
                var zip = $('#zip1').val();
                if (zip != '' && !validateNumber(zip, 'zip')) {
                    $('#zip1').addClass('input-error');
                    flag = false;
                }
                var phone = $('#phone1').val();
                if (phone != '' && !validateNumber(phone, 'phone')) {
                    $("#phone1").addClass('input-error');
                    flag = false;
                }
                var fax = $('#fax1').val();
                /*if (fax == '') {
                    $('#fax1').addClass('input-error');
                    flag = false;
                }*/
                if (fax != '' && !validateNumber(fax, 'fax')) {
                    $('#fax1').addClass('input-error');
                    flag = false;
                }
                var firstName = $("input[name='firstName[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();


                var lastName = $("input[name='lastName[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();

                var email = $("input[name='email[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();

                var directPhone = $("input[name='directPhone[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();

                var cellPhone = $("input[name='cellPhone[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();

                var title = $("select[name='title[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();

                var titleOther = $("input[name='titleOther[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();

                var contactId = $("input[name='contactId[]']")
                        .map(function () {
                            return $(this).val();
                        }).get();

                var type = $(this).data('type');

                if (flag) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('create.contact') }}",
                        data: {
                            id: id,
                            type: type,
                            contact: '1',
                            contactId: contactId,
                            contactType: contactType,
                            company: company,
                            website: website,
                            address: address,
                            city: city,
                            state: state,
                            zip: zip,
                            phone: phone,
                            fax: fax,
                            title: title,
                            titleOther: titleOther,
                            firstName: firstName,
                            lastName: lastName,
                            email: email,
                            directPhone: directPhone,
                            cell: cellPhone,
                            user_id: '{{ Auth::user()->id }}',
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            if (data.status) {
                                $('#success-message').html('<p class="alert alert-success">' + data.message + '</p>');
                                $('#IndustryEditForm')[0].reset();
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                $('#success-message').html('<p class="input-error">' + data.message + '</p>');
                            }
                        }
                    });
                }
            });
            $('.delete').on('click', function () {
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
                }).then(function () {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('customer.delete.contact') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Contact deleted successfully',
                                }).then(function () {
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
            $('.error').on('focus', function () {
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

            var maxField1 = 10; //Input fields increment limitation
            var addButton1 = $('.add_button1'); //Add button selector
            var wrapper1 = $('.field_wrapper2'); //Input field wrapper
            var fieldHTML1 = '<tr class="external"><td> <select class="form-control error title1" name="title[]">' +
                ' <option value="CEO">CEO</option> <option value="CFO">CFO</option> ' +
                '<option value="Credit">Credit</option> <option value="PM">PM</option> ' +
                '<option value="Corporation Counsel">Corporation Counsel</option> ' +
                '<option value="A/R Manager">A/R Manager</option> ' +
                '<option value="Other">Other</option> </select>' +
                '<div class="title_other" style="display: none;"> ' +
                '<input type="text" name="titleOther[]" class="form-control error">' +
                ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>' +
                '<td><input class="form-control error autocomplete_contact_first_name" type="text" name="firstName[]" placeholder="First Name"/></td>' +
                '<td><input class="form-control error" type="text" name="lastName[]" placeholder="Last Name"/></td>' +
                '<td><input class="form-control error" type="email" name="email[]" placeholder="Email"/></td>' +
                '<td><input class="form-control error" type="text" name="directPhone[]" placeholder="Direct Phone"/></td>' +
                '<td><input class="form-control error" type="text" name="cellPhone[]" placeholder="Cell Phone"/></td>' +
                '<td><a href="javascript:void(0);" class="remove_button1" title="Remove field">' +
                '<img src="{{ env('ASSET_URL') }}/images/remove-icon.png" height="30px" width="30px"/></a><input class="form-control error" type="hidden" name="contactId[]" value="0" /></td></tr>'; //New input field html
            var x1 = 1; //Initial field counter is 1
            $(addButton1).click(function () { //Once add button is clicked
                if (x1 < maxField1) { //Check maximum number of input fields
                    x1++; //Increment field counter
                    $('.field_wrapper3 tr:last').after(fieldHTML1); // Add field html
                }
            });
            $(wrapper1).on('click', '.remove_button1', function (e) { //Once remove button is clicked
                e.preventDefault();
                $(this).parents('tr').remove(); //Remove field html
                x1--; //Decrement field counter
            });
        });
        $(document).delegate('.title1', 'change', function () {
            var item = $(this).val();
            if (item == 'Other') {
                $(this).hide();
                $(this).next('.title_other').show();
            }
        });
        $(document).delegate('.titleOtherBtn', 'click', function () {
            $(this).parent('div').hide();
            $(this).parent('div').parent('td').children('.title1').val('CEO');
            $(this).parent('div').parent('td').children('.title1').show();
        });

        /**
         * autocomplete functions
         */
        $('.autocomplete').autocomplete({
            source: function (request, response) {
                var key = request.term;
                $.ajax({
                    url: "{{ route('autocomplete.contact.company') }}",
                    dataType: "json",
                    data: {
                        key: key,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        var array = $.map(data.data, function (item) {
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
            select: function (event, ui) {
                // $('.external').remove();
                // $('#contactType1').removeAttr('disabled');
                // $('.formSubmitIndustry').attr('data-type', 'edit');
                // $('#contactType1').val(ui.item.data.contact_type);
                // $('#id1').val(ui.item.id);
                $('#website1').val(ui.item.data.website);
                $('#address1').val(ui.item.data.address);
                $('#city1').val(ui.item.data.city);
                $('#state1').val(ui.item.data.state_id);
                $('#zip1').val(ui.item.data.zip);
                $('#phone1').val(ui.item.data.phone);
                $('#fax1').val(ui.item.data.fax);
                $('.title1').val('CEO');
                $('#firstName1').val('');
                $('#lastName1').val('');
                $('#email1').val('');
                $('#directPhone1').val('');
                $('#cellPhone1').val('');
            }
        });

        $(document).delegate('.autocomplete_contact_first_name', 'focus', function(e) {
            $(this).autocomplete({
                source: function (request, response) {
                    //var id = $('#id1').val();
                    //var key = $(this).val();
                    $.ajax({
                        url: "{{ route('autocomplete.contact.firstname') }}",
                        dataType: "json",
                        data: {
                            // id: id,
                            //   key: key,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            var array = $.map(data.data, function (item) {
                                return {
                                    label: item.first_name,
                                    value: item.first_name,
                                    id: item.id,
                                    data: item
                                }
                            });
                            response(array)
                        }
                    });
                },
                minLength: 1,
                max: 10,
                select: function (event, ui) {</i>
                    if (ui.item.data.title != '') {
                        if (ui.item.data.title != 'Other') {
                            $(this).closest("tr").find(".title1").val(ui.item.data.title);
                        } else {
                            $(this).closest("tr").find("input[name='titleOther[]']").val(ui.item.data.title_other);
                            $(this).closest("tr").find(".title_other").show();
                            $(this).closest("tr").find(".title1").hide();
                        }
                    } else {
                        $(this).closest("tr").find(".title1").val('CEO');
                    }
                    $(this).closest("tr").find("input[name='lastName[]']").val(ui.item.data.last_name);
                    $(this).closest("tr").find("input[name='customer_id[]']").val(ui.item.data.id);
                    $(this).closest("tr").find("input[name='email[]']").val(ui.item.data.email);
                    $(this).closest("tr").find("input[name='directPhone[]']").val(ui.item.data.direct_phone);
                    $(this).closest("tr").find("input[name='cellPhone[]']").val(ui.item.data.cell);
                }
            });
        });
        $(document).delegate('autocomplete_contact_first_name', 'blur', function(e) {
            var target = $(this);
            if (target.hasClass('ui-autocomplete-input')) {
                target.autocomplete('destroy');
            }
        });
    </script> --}}
@endsection
