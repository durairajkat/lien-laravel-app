@extends('basicUser.layout.main')

@section('title', 'Customer Contacts')
{{-- @section('style')
    <style>
        .input-error {
            border: 2px solid red;

        }
         input[type=number]::-webkit-inner-spin-button,
         input[type=number]::-webkit-outer-spin-button {
             -webkit-appearance: none !important;
             -moz-appearance: none !important;
             appearance: none !important;
             margin: 0 !important;
         }
         .dropdown-menu{
             top:30px;
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
                        <div class="options-wrapper">
                            <div class="col-md-3 col-sm-3">
                                <h2>Contacts</h2>
                            </div>
                            <div class="col-md-7 col-sm-7 table-middle">
                                <ul class="contactsList">
                                    <li class="select"><a href="{{ route('member.contacts.contacts') }}"
                                            {{-- class="customerContacts" --}}>Customer
                                            Contacts</a></li>
                                    <li><a href="{{ route('member.contacts.industry') }}" {{-- class="industryContacts" --}}>Industry
                                            Contacts</a></li>
                                    <li><a href="javascript:void(0)" class="addCustomerButton" data-type="Add"
                                            data-contact="customer" data-toggle="modal"><span><i class="fa fa-plus-circle"
                                                    aria-hidden="true"></i></span> Create New
                                            customer</a></li>
                                </ul>
                            </div>
                            <span class="search dropdown-toggle" id="dropdownMenuButton1" role="alert"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <a href="#">
                                    <img src="{{ env('ASSET_URL') }}/images/search.png" alt="img" class="search-img">
                                </a>
                            </span>
                            <div class="dropdown-menu dropdown-search" aria-labelledby="dropdownMenuButton"
                                style="top:40px;">

                                <input type="text" id="projectSearch" placeholder="Search.."
                                    value="{{ isset($_GET['search']) && $_GET['search'] != '' ? $_GET['search'] : '' }}"
                                    class="form-control">
                                <button type="submit" data-type="project" class="btn s-button search1"><i
                                        class="fa fa-search" aria-hidden="true"></i></button>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="table-responsive white-table">
                                <table class="table customer">
                                    <thead>
                                        <tr>
                                            <th>
                                                Company
                                                <i class="fa fa-sort" aria-hidden="true" data-col="customer_name"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}"></i>
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
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($contacts) > 0)
                                            @foreach ($contacts as $key => $contact)
                                                <tr>
                                                    <td>{{ !is_null($contact->getCompany) ? $contact->getCompany->company : 'N/A' }}
                                                    </td>
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
                                                        <a href="javascript:void(0)" class="addCustomerButton"
                                                            data-id="{{ !is_null($contact->getCompany) ? $contact->getCompany->id : 0 }}"
                                                            data-type="Edit" data-contact="customer"
                                                            data-company="{{ !is_null($contact->getCompany) ? $contact->getCompany->company : '' }}"
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
                                            @endforeach
                                            <tr>
                                                <td colspan="8">
                                                    {{ $contacts->appends($_GET)->links() }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="8">
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
                                                <td colspan="5"> No contact found...</td>
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
    {{-- <div id="addCustomerModel" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="title"></span> Customer Contact</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="customerContactForm" method="post" action="#">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Company : </label>
                            <div class="col-md-8">
                                <input class="form-control error autocomplete" type="text" name="company" id="company"
                                       placeholder="Enter Company Name" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Website : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="website" id="website"
                                       placeholder="Enter Website"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Address : </label>
                            <div class="col-md-8">
                                <textarea name="address" class="form-control error" placeholder="Enter Address"
                                          id="address"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">City : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="city" id="city"
                                       placeholder="Enter City"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">State <span>*</span> : </label>
                            <div class="col-md-8">
                                <select class="form-control error" name="state" id="state">
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
                                <input class="form-control error" type="text" name="zip" id="zip"
                                       placeholder="Enter Zip Code"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Phone : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="phone" id="phone"
                                       placeholder="Enter Phone Number"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Fax : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="fax" id="fax"
                                       placeholder="Enter Fax Number"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Contacts : </label>
                        </div>
                        <div class="form-group field_wrapper">
                            <div class="col-md-12">
                                <table class="table field_wrapper4">
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
                                            <select class="form-control error title2" name="title[]" id="title">
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
                                                   id="firstName"
                                                   placeholder="First Name"/>
                                        </td>
                                        <td>
                                            <input class="form-control error" type="text" name="lastName[]"
                                                   id="lastName"
                                                   placeholder="Last Name"/>
                                        </td>
                                        <td>
                                            <input class="form-control error" type="email" name="email[]" id="email"
                                                   placeholder="Email"/>
                                        </td>
                                        <td>
                                            <input class="form-control error" type="text" name="directPhone[]"
                                                   id="directPhone"
                                                   placeholder="Direct Phone"/>
                                        </td>
                                        <td>
                                            <input class="form-control error" type="text" name="cellPhone[]"
                                                   id="cellPhone"
                                                   placeholder="Cell Phone"/>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="add_button" title="Add field"><img
                                                        src="{{ env('ASSET_URL') }}/images/add-icon.png" height="30px"
                                                        width="30px"/></a>

                                            <input class="form-control error" type="hidden" name="contactId[]"
                                                   id="contactId"/>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div id="error-message">

                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="0" id="id">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn blue-btn form-control formSubmit" type="button"><span
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
    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/customer_contacts.js"></script>
@endsection
