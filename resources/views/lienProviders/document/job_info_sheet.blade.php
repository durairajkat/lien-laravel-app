@extends('basicUser.layout.main')

@section('title', 'Job Info Sheet')

@section('style')
    <style>
        .dropdown-menu {
            width: 215px;
        }

        .blue-btn-ext {
            background: #1084ff;
            color: #fff;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0 !important;
        }

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="center-part">
                    <h3>JOB INFORMATION</h3>
                </div>
                <form method="post" action="{{ route('save.new.job') }}" class="form-horizontal">
                    <input type="hidden" value="{{ $project->id }}" name="project_id">
                    {{ csrf_field() }}
                    <div class="black-box">
                        <!-- Company Information -->
                        <div class="block-one">
                            <div class="black-box-main">
                                <h3>Company Information</h3>
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="col-lg-6 formTable">
                                            <div class="row">
                                                <div class="col-md-3"><label>Company</label></div>
                                                <div class="col-md-9 field">
                                                    <input disabled type="text" name="company_name" id="company_name"
                                                        value="{{ $user->details ? $user->details->getCompany->company : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Address</label></div>
                                                <div class="col-md-9 field">
                                                    <input disabled type="text" name="company_address" id="company_address"
                                                        value="{{ $user->details ? $user->details->address : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2"><label>City</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input disabled type="text" name="company_city" id="company_city"
                                                        value="{{ $user->details ? $user->details->city : '' }}">
                                                </div>
                                                <div class="col-md-2"><label>State</label></div>
                                                <div class="col-md-2 smallField">
                                                    <select disabled name="company_state" id="company_state">
                                                        <option value="">Select a State</option>
                                                        @foreach ($states as $state)
                                                            <option disabled value="{{ $state->id }}"
                                                                {{ $user->details && $user->details->state_id == $state->id ? 'selected' : '' }}>
                                                                {{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2"><label>Zip</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="company_zip" disabled id="coompany_zip"
                                                        value="{{ $user->details ? $user->details->zip : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"><label>Office Number</label></div>
                                                <div class="col-md-8 field">
                                                    <input type="text" name="company_office_phone" disabled id="company_fax"
                                                        value="{{ $user->details ? $user->details->office_phone : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 formTable rightTable">
                                            <div class="row">
                                                <div class="col-md-2"><label>First</label></div>
                                                <div class="col-md-4 smallField">
                                                    <input type="text" name="company_fname" disabled
                                                        value="{{ $user->details ? $user->details->first_name : '' }}"
                                                        class="autoCompleteSubUser" autocomplete="off">
                                                </div>
                                                <div class="col-md-2"><label>Last</label></div>
                                                <div class="col-md-4 smallField">
                                                    <input type="text" name="company_lname" disabled id="company_lname"
                                                        value="{{ $user->details ? $user->details->last_name : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"><label>Email</label></div>
                                                <div class="col-md-8 field">
                                                    <input type="text" name="company_email" disabled id="company_email"
                                                        value="{{ $user->details ? $user->email : '' }}">
                                                </div>
                                            </div>
                                            <input type="hidden" name="customer_company_id" id="customer_company_id"
                                                value="{{ $project->user_id }}">
                                            <div class="row">
                                                <div class="col-md-3"><label>Phone</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="company_phone" disabled id="company_phone"
                                                        value="{{ $user->details ? $user->details->phone : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Job Description -->
                        <div class="block-two">
                            <div class="black-box-main">
                                <h3>
                                    JOB DESCRIPTION
                                </h3>

                                <div class="row">
                                    <div class="formTable col-lg-6">
                                        <div class="row">
                                            <div class="col-md-3">
                                                JOB NAME
                                            </div>
                                            <div class="col-md-9 field">
                                                <input type="text" name="job_name" disabled
                                                    value="{{ $project ? $project->project_name : '' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                JOB ADDRESS
                                            </div>
                                            <div class="col-md-9 field">
                                                <input type="text" name="job_address" disabled
                                                    value="{{ $project->address }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                JOB CITY
                                            </div>
                                            <div class="col-md-9 field">
                                                <input type="text" name="job_city" disabled value="{{ $project->city }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                STATE
                                            </div>
                                            <div class="col-md-4 smallField">
                                                <select name="job_state" class="col-md-12" disabled>
                                                    <option value="">Select a State</option>
                                                    @foreach ($states as $state)
                                                        <option disabled value="{{ $state->id }}"
                                                            {{ $project->state_id == $state->id ? 'selected' : '' }}>
                                                            {{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                ZIP
                                            </div>
                                            <div class="col-md-4 smallField">
                                                <input type="text" name="job_zip" disabled value="{{ $project->zip }}">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="formTable col-lg-6 rightTable" style="display: {{ (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? 'none' : 'block' }};">
                                        <input type="button" value="Job Description Edit"
                                               class="btn btn-primary jobDescription"
                                               data-name="{{ $project ? $project->project_name : '' }}"
                                               data-address="{{ $project->address }}"
                                               data-city="{{ $project->city }}"
                                               data-zip="{{ $project->zip }}"
                                        >
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="block-two">
                            <div class="black-box-main">
                                <h3>Your Customer <span class="gc_show" id="show_general_contractor"
                                        style="display:{{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '0' ? 'inline' : 'none' }}{{-- {{ isset($jobInfoSheet) && $jobInfoSheet != '' ? ($jobInfoSheet->is_gc == '0' ? 'inline' : 'none') : ($project->originalCustomer->name == 'Original Contractor' ? 'inline' : (!isset($jobInfoSheet) ? 'inline'  : 'none')) }}; --}}">
                                        / General Contractor </span> {{-- <small style="font-size: 8px;">( <em>*To add edit customer contact please click on Add/Edit contact button</em> )</small> --}}</h3>
                                <input type="hidden" name="customer_id"
                                    value="{{ $project->customer_contract ? $project->customer_contract->id : '' }}">
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="col-lg-6 formTable">
                                            <div class="row">
                                                <div class="col-md-3"><label>Company</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_company" readonly
                                                        value="{{ $project->customer_contract ? $project->customer_contract->company->company : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Address</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_address" readonly
                                                        value="{{ $project->customer_contract ? $project->customer_contract->company->address : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2"><label>City</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="customer_city" readonly
                                                        value="{{ $project->customer_contract ? $project->customer_contract->company->city : '' }}">
                                                </div>
                                                <div class="col-md-2"><label>State</label></div>
                                                <div class="col-md-2 smallField">
                                                    <select name="job_state" class="col-md-12" disabled>
                                                        <option value="">Select a State</option>
                                                        @foreach ($states as $state)
                                                            <option value="{{ $state->id }}"
                                                                {{ $project->customer_contract ? ($project->customer_contract->company->state_id == $state->id ? 'selected' : 'disabled') : 'disabled' }}>
                                                                {{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2"><label>Zip</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="customer_zip" readonly
                                                        value="{{ $project->customer_contract ? $project->customer_contract->company->zip : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Telephone</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_phone" readonly
                                                        value="{{ $project->customer_contract ? $project->customer_contract->company->phone : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Fax</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_fax" readonly
                                                        value="{{ $project->customer_contract ? $project->customer_contract->company->fax : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Web</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_zip" readonly
                                                        value="{{ $project->customer_contract ? $project->customer_contract->company->website : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 formTable rightTable">
                                            {{-- <div class="row" style="display: {{ (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? 'none' : 'block' }};">
                                                <div class="col-md-12">
                                                    @if (!is_null($project->customer_contract))
                                                        <input type="button" value="Add/Edit Contact"
                                                               class="btn btn-primary editContact"
                                                               data-contact="customer"
                                                               data-id="{{ $project->customer_contract ? $project->customer_contract->id : '0' }}"
                                                               data-map_id="{{ !is_null($project->customer_contract) ? $project->customer_contract->id : 0 }}"
                                                               data-type="edit"
                                                               data-contactType="N/A"
                                                               data-customer_id="{{ $project->customer_contract ? $project->customer_contract->company_contact_id : '0' }}"
                                                               data-company_id="{{ $project->customer_contract ? $project->customer_contract->company_id : '0' }}"
                                                        >
                                                    @else
                                                        <input type="button" value="Add/Edit Contact"
                                                               class="btn btn-primary editContact"
                                                               data-contact="customer"
                                                               data-type="add"
                                                               data-contacttype="N/A"
                                                        >
                                                    @endif
                                                </div>
                                            </div> --}}
                                            <div class="row">
                                                <div class="col-md-2"><label>First</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="customerFirstName[]" readonly
                                                        value="{{ $project->customer_contract != '' ? $project->customer_contract->contacts->first_name : 'N/A' }}">
                                                </div>
                                                <div class="col-md-2"><label>Last</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="customerLastName[]" readonly
                                                        value="{{ $project->customer_contract != '' ? $project->customer_contract->contacts->last_name : 'N/A' }}">
                                                </div>
                                                <div class="col-md-2 "><label>Title</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="customerLastName[]" readonly
                                                        value="{{ $project->customer_contract != '' ? ($project->customer_contract->contacts->title == 'Other' ? $project->customer_contract->contacts->title_other : $project->customer_contract->contacts->title) : 'N/A' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"><label>Direct Phone</label></div>
                                                <div class="col-md-8 field">
                                                    <input type="text" name="customerDirectPhone[]" readonly
                                                        value="{{ $project->customer_contract != '' ? $project->customer_contract->contacts->phone : 'N/A' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"><label>Cell Phone</label></div>
                                                <div class="col-md-8 field">
                                                    <input type="text" name="customerCellPhone[]" readonly
                                                        value="{{ $project->customer_contract != '' ? $project->customer_contract->contacts->cell : 'N/A' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"><label>Email</label></div>
                                                <div class="col-md-8 field">
                                                    <input type="text" name="customerEmail[]" readonly
                                                        value="{{ $project->customer_contract != '' ? $project->customer_contract->contacts->email : 'N/A' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 formTable">
                                            <div class="row">
                                                <div class="col-md-4"><label>Contract Amount </label></div>
                                                <div class="col-md-8 field">
                                                    <div class="row">
                                                        <div class="col-md-2"><span class="pull-right">$</span></div>
                                                        <div class="col-md-10">
                                                            <input type="text" name="contract_amount" disabled
                                                                value="{{ isset($jobInfoSheet) && $jobInfoSheet != '' ? $jobInfoSheet->contract_amount : '' }}"
                                                                {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'disabled' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 formTable rightTable">
                                            <div class="row">
                                                <div class="col-md-4"><label>First day of work</label></div>
                                                <div class="col-md-8 field">
                                                    <input type="text" name="first_day_of_work" class="date" disabled
                                                        value="{{ isset($jobInfoSheet) && $jobInfoSheet != '' ? $jobInfoSheet->first_day_of_work : '' }}"
                                                        {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            if your customer is the General Contractor?
                                        </div>
                                        <div class="col-md-3">
                                            {{-- <input type="radio" name="is_gc" class="is_gc" value="0" id="is_gc"
                                                    {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '0' ? 'checked' : ($project->originalCustomer->name == 'Original Contractor' ? 'checked' : '') }} {{ (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? 'disabled' : '' }}> Yes
                                            <input type="radio" name="is_gc" class="is_gc" id="not_gc" value="1" {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '1' ? 'checked' : ($project->originalCustomer->name != 'Original Contractor' ? 'checked' : (!isset($jobInfoSheet) ? 'checked'  : '')) }} {{ (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? 'disabled' : '' }}> No --}}
                                            <input type="radio" name="is_gc" class="is_gc" value="0" id="is_gc" disabled
                                                {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '0' ? 'checked' : '' }}
                                                {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'disabled' : '' }}>
                                            Yes
                                            <input type="radio" disabled name="is_gc" class="is_gc" id="not_gc" value="1"
                                                {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '1' ? 'checked' : (!isset($jobInfoSheet) ? 'checked' : '') }}
                                                {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'disabled' : '' }}>
                                            No
                                        </div>
                                        <div class="col-md-5">
                                            * If not, please fill General contractor information
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- General Conductor -->
                        @php($gc = 1)

                        @if (count($projectContacts) > 0)
                            @foreach ($projectContacts as $key => $contact)
                                @if ($contact['type'] == 'General Contractor')
                                    @php($gc++)
                                @endif
                            @endforeach
                        @endif
                        @if ($gc == 1)
                            <div class="block-two gc_hide generalContractor"
                                style="display: {{ $project->originalCustomer->name == 'Original Contractor' ? 'none' : 'block' }}">
                                <div class="black-box-main">
                                    <h3>General Contractor {{-- <small style="font-size: 8px;">( <em>*To add edit General Contractor please click on Add/Edit contact button</em> )</small> --}}</h3>
                                    <div class="table-responsive">
                                        <div class="row">
                                            <div class="col-lg-6 formTable">
                                                <div class="row">
                                                    <div class="col-md-3"><label>Company</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="gc_company" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3"><label>Address</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="gc_address" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2"><label>City</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="gc_city" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2"><label>State</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <select name="gc_state" class="col-md-12">
                                                            <option value="" disabled>Select a State</option>
                                                            @foreach ($states as $state)
                                                                <option value="{{ $state->id }}" disabled>
                                                                    {{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2"><label>Zip</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="gc_zip" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3"><label>Telephone</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="gc_phone" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3"><label>Fax</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="gc_fax" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3"><label>Web</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="gc_web" value="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 formTable rightTable">
                                                <div class="row"
                                                    style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                                    {{-- <input type="button" value="Add/Edit Contact"
                                                           class="btn btn-primary editContact"
                                                           data-contact="industry"
                                                           data-id="0"
                                                           data-type="edit"
                                                           data-contactType="General Contractor"
                                                           data-customer_id="0"
                                                           data-company_id="0"
                                                    > --}}
                                                    {{-- <input type="button" value="Add/Edit Contact"
                                                           class="btn btn-primary editContact"
                                                           data-contact="industry"
                                                           data-type="add"
                                                           data-contacttype="General Contractor"
                                                    > --}}
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2"><label>First</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="gc_first_name" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2"><label>Last</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="gc_last_name" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2 "><label>Title</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="gc_title" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><label>Direct Phone</label></div>
                                                    <div class="col-md-8 field">
                                                        <input type="text" name="gc_direct_phone" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><label>Cell Phone</label></div>
                                                    <div class="col-md-8 field">
                                                        <input type="text" name="gc_cell" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><label>Email</label></div>
                                                    <div class="col-md-8 field">
                                                        <input type="text" name="gc_email" value="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php($owner = 1)
                        @if (count($projectContacts) > 0)
                            @foreach ($projectContacts as $key => $contact)
                                @if ($contact['type'] == 'Owner')
                                    @php($owner++)
                                @endif
                            @endforeach
                        @endif
                        @if ($owner == 1)
                            <!-- Owner -->
                            <div class="block-two">
                                <div class="black-box-main">
                                    <h3>Owner{{-- <small style="font-size: 8px;">( <em>*To add edit Owner please click on Add/Edit contact button</em> )</small> --}}</h3>
                                    <div class="table-responsive">
                                        <div class="row">
                                            <div class="col-lg-6 formTable">
                                                <div class="row">
                                                    <div class="col-md-3"><label>Company</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="owner_company" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3"><label>Address</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="owner_address" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2"><label>City</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="owner_city" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2"><label>State</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <select name="owner_state" class="col-md-12" disabled>
                                                            <option value="">Select a State</option>
                                                            @foreach ($states as $state)
                                                                <option value="{{ $state->id }}" disabled>
                                                                    {{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2"><label>Zip</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="owner_zip" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3"><label>Telephone</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="owner_phone" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3"><label>Fax</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="owner_fax" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3"><label>Web</label></div>
                                                    <div class="col-md-9 field">
                                                        <input type="text" name="owner_web" value="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 formTable rightTable">
                                                <div class="row"
                                                    style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                                    {{-- <input type="button" value="Add/Edit Contact"
                                                           class="btn btn-primary editContact"
                                                           data-contact="industry"
                                                           data-id="0"
                                                           data-type="edit"
                                                           data-contactType="Owner"
                                                           data-customer_id="0"
                                                           data-company_id="0"
                                                    > --}}
                                                    {{-- <input type="button" value="Add/Edit Contact"
                                                           class="btn btn-primary editContact"
                                                           data-contact="industry"
                                                           data-type="add"
                                                           data-contacttype="Owner"
                                                    > --}}
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2"><label>First</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="owner_first_name" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2"><label>Last</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="owner_last_name" value="" readonly>
                                                    </div>
                                                    <div class="col-md-2 "><label>Title</label></div>
                                                    <div class="col-md-2 smallField">
                                                        <input type="text" name="owner_title" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><label>Direct Phone</label></div>
                                                    <div class="col-md-8 field">
                                                        <input type="text" name="owner_direct_phone" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><label>Cell Phone</label></div>
                                                    <div class="col-md-8 field">
                                                        <input type="text" name="owner_cell" value="" readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4"><label>Email</label></div>
                                                    <div class="col-md-8 field">
                                                        <input type="text" name="owner_email" value="" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Industry Contact -->
                        <div>
                            @if (count($projectContacts) > 0)
                                @foreach ($projectContacts as $key => $contact)
                                    <div
                                        class="block-two {{ $contact['type'] == 'General Contractor' ? 'generalContractor' : '' }}">
                                        <div class="black-box-main">
                                            <h3>{{ $contact['type'] }} {{-- <small style="font-size: 8px;">( <em>*To add edit customer contact please click on Add/Edit contact button</em> )</small> --}}</h3>
                                            <input type="hidden" name="industry_type[{{ $key }}]"
                                                value="{{ $contact['type'] }}">
                                            <input type="hidden" name="company_id[{{ $key }}]"
                                                value="{{ $contact['company_id'] }}">
                                            <div class="table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-6 formTable">
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Company</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text"
                                                                    name="industry_contact[{{ $key }}]" readonly
                                                                    value="{{ $contact['company'] ? $contact['company']->company : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Address</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text"
                                                                    name="industry_contact[{{ $key }}]" readonly
                                                                    value="{{ $contact['company'] ? $contact['company']->address : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2"><label>City</label></div>
                                                            <div class="col-md-2 smallField">
                                                                <input type="text"
                                                                    name="industry_city[{{ $key }}]" readonly
                                                                    value="{{ $contact['company'] ? $contact['company']->city : '' }}">
                                                            </div>
                                                            <div class="col-md-2"><label>State</label></div>
                                                            <div class="col-md-2 smallField">
                                                                <select name="industry_state[{{ $key }}]"
                                                                    class="col-md-12" disabled>
                                                                    <option value="">Select a State</option>
                                                                    @foreach ($states as $state)
                                                                        <option value="{{ $state->id }}"
                                                                            {{ $contact['company'] ? ($contact['company']->state_id == $state->id ? 'selected' : 'disabled') : 'disabled' }}>
                                                                            {{ $state->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2"><label>Zip</label></div>
                                                            <div class="col-md-2 smallField">
                                                                <input type="text" name="industry_zip[{{ $key }}]"
                                                                    readonly
                                                                    value="{{ $contact['company'] ? $contact['company']->zip : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Telephone</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text"
                                                                    name="industry_phone[{{ $key }}]" readonly
                                                                    value="{{ $contact['company'] ? $contact['company']->phone : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Fax</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text" name="industry_fax[{{ $key }}]"
                                                                    readonly
                                                                    value="{{ $contact['company'] ? $contact['company']->fax : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Web</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text"
                                                                    name="industry_website[{{ $key }}]" readonly
                                                                    value="{{ $contact['company'] ? $contact['company']->website : '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 formTable rightTable">
                                                        <div class="row"
                                                            style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                                            {{-- <div class="col-md-12">
                                                                <input type="button" value="Add/Edit Contact"
                                                                       class="btn btn-primary editContact"
                                                                       data-contact="industry"
                                                                       data-id="{{ $contact['company']->company_id }}"
                                                                       data-type="edit"
                                                                       data-contactType="{{ $contact['type'] }}"
                                                                       data-customer_id="{{ $contact['customer_id'] }}"
                                                                       data-company_id="{{ $contact['company_id'] }}"
                                                                >
                                                            </div> --}}
                                                        </div>
                                                        @if (count($contact['customers']) > 0)
                                                            @foreach ($contact['customers'] as $customerKey => $contactInformation)
                                                                @if ($customerKey == 0)
                                                                    {{-- <div class="row" style="display: {{ (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? 'none' : 'block' }};">
                                                                        <div class="col-md-12">
                                                                            <input type="button" value="Add/Edit Contact"
                                                                                   class="btn btn-primary editContact"
                                                                                   data-contact="industry"
                                                                                   data-id="{{ $contact['company']->id }}"
                                                                                   data-map_id = "{{ !is_null($contactInformation->mapContactCompany) ? $contactInformation->mapContactCompany->id : 0 }}"
                                                                                   data-type="edit"
                                                                                   data-contactType="{{ $contactInformation->customer_type }}"
                                                                                   data-customer_id="{{ $contactInformation->id }}"
                                                                                   data-company_id="{{ $contact['company_id'] }}"
                                                                            >
                                                                        </div>
                                                                    </div> --}}
                                                                    <div class="row">
                                                                        <div class="col-md-2"><label>First</label></div>
                                                                        <div class="col-md-2 smallField">
                                                                            <input type="text" readonly
                                                                                name="industryFirstName[{{ $key }}][]"
                                                                                value="{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}">
                                                                        </div>
                                                                        <div class="col-md-2"><label>Last</label></div>
                                                                        <div class="col-md-2 smallField">
                                                                            <input type="text" readonly
                                                                                name="industryLastName[{{ $key }}][]"
                                                                                value="{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}">
                                                                        </div>
                                                                        <div class="col-md-2 "><label>Title</label>
                                                                        </div>
                                                                        <div class="col-md-2 smallField">
                                                                            <input type="text" readonly
                                                                                name="industryTitle[{{ $key }}][]"
                                                                                value="{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4"><label>Direct
                                                                                Phone</label></div>
                                                                        <div class="col-md-8 field">
                                                                            <input type="text" readonly
                                                                                name="industryDirectPhone[{{ $key }}][]"
                                                                                value="{{ $contactInformation->phone }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4"><label>Cell Phone</label>
                                                                        </div>
                                                                        <div class="col-md-8 field">
                                                                            <input type="text" readonly
                                                                                name="industryCellPhone[{{ $key }}][]"
                                                                                value="{{ $contactInformation->cell }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4"><label>Email</label></div>
                                                                        <div class="col-md-8 field">
                                                                            <input type="text" readonly
                                                                                name="industryEmail[{{ $key }}][]"
                                                                                value="{{ $contactInformation->email }}">
                                                                        </div>
                                                                    </div>
                                                                    @if (count($contact['customers']) > 1)
                                                                        <p>
                                                                            <a href="javascript:void(0)" class="show_more"
                                                                                id="customerMore{{ $key }}"
                                                                                data-id="{{ $key }}">Show
                                                                                More</a>
                                                                            <a href="javascript:void(0)" class="show_less"
                                                                                id="customerLess{{ $key }}"
                                                                                data-id="{{ $key }}">Show
                                                                                Less</a>
                                                                        </p>
                                                                    @endif
                                                                @else
                                                                    <div class="customer{{ $key }}"
                                                                        style="display: none;">
                                                                        {{-- <div class="row" style="display: {{ (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? 'none' : 'block' }};">
                                                                            <div class="col-md-12">
                                                                                <input type="button" value="Add/Edit Contact"
                                                                                       class="btn btn-primary editContact"
                                                                                       data-contact="industry"
                                                                                       data-id="{{ $contact['company']->id }}"
                                                                                       data-map_id = "{{ !is_null($contactInformation->mapContactCompany) ? $contactInformation->mapContactCompany->id : 0 }}"
                                                                                       data-type="edit"
                                                                                       data-contactType="{{ $contactInformation->customer_type }}"
                                                                                       data-customer_id="{{ $contactInformation->id }}"
                                                                                       data-company_id="{{ $contact['company_id'] }}"
                                                                                >
                                                                            </div>
                                                                        </div> --}}
                                                                        <div class="row">
                                                                            <div class="col-md-2"><label>First</label>
                                                                            </div>
                                                                            <div class="col-md-2 smallField">
                                                                                <input type="text"
                                                                                    name="industryFirstName[{{ $key }}][]"
                                                                                    readonly
                                                                                    value="{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}">
                                                                            </div>
                                                                            <div class="col-md-2"><label>Last</label>
                                                                            </div>
                                                                            <div class="col-md-2 smallField">
                                                                                <input type="text" readonly
                                                                                    name="industryLastName[{{ $key }}][]"
                                                                                    readonly
                                                                                    value="{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}">
                                                                            </div>
                                                                            <div class="col-md-2 "><label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-2 smallField">
                                                                                <input type="text" readonly
                                                                                    name="industryTitle[{{ $key }}][]"
                                                                                    value="{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4"><label>Direct
                                                                                    Phone</label></div>
                                                                            <div class="col-md-8 field">
                                                                                <input type="text" readonly
                                                                                    name="industryDirectPhone[{{ $key }}][]"
                                                                                    value="{{ $contactInformation->phone }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4"><label>Cell
                                                                                    Phone</label></div>
                                                                            <div class="col-md-8 field">
                                                                                <input type="text" readonly
                                                                                    name="industryCellPhone[{{ $key }}][]"
                                                                                    value="{{ $contactInformation->cell }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-4"><label>Email</label>
                                                                            </div>
                                                                            <div class="col-md-8 field">
                                                                                <input type="text" readonly
                                                                                    name="industryEmail[{{ $key }}][]"
                                                                                    value="{{ $contactInformation->email }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!-- Terms and contact -->
                        <div class="block-two">
                            <div class="table-responsive">
                                <div class="black-box-main">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Agree Terms and Conditions </h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 footerTxt">
                                            Please remember to fax to NLB all documentation related to this
                                            project. This includes contracts, invoices, notices, purchase orders,
                                            etc.<strong>FAX: 847-432-8950</strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 footerTxt">
                                            <p><strong>Liability Limitations:</strong> National Lien and Bond Claim Systems,
                                                a division of
                                                Network*50, Inc (NLB) does not guarantee or in any way represent or warrant
                                                the
                                                information transmitted or received by customer or third parties. Customer
                                                acknowledges
                                                and agrees that the service provided by NLB consists solely of providing
                                                access to a filing
                                                network which may in appropriate cases involve attorneys. NLB is not in any
                                                way
                                                responsible or liable for errors, omissions, inadequacy or interruptions. In
                                                the event any
                                                errors is attributable to NLB or to the equipment, customer should be
                                                entitled only to a
                                                refund of the cost for preparation of any notices. The refund shall be
                                                exclusively in lieu of
                                                any other damages or remedies against NLB.</p>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-12 footerTxt" style="display: {{ (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? 'none' : 'block' }};">
                                        <div class="col-md-4">
                                            <label>Attach a Document (Choose A FILE)</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" id="uploadFile">
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-md-12 footerTxt">
                                            <div class="row">
                                                @if (isset($jobInfoSheet) && count($jobInfoSheet->jobFiles) > 0)
                                                    @foreach ($jobInfoSheet->jobFiles as $file)

                                                        <div class="col-md-6" id="id{{ $file->id }}">
                                                            <div class="fileRow">
                                                                <div class="col-xs-8">
                                                                    <div class="fileName">
                                                                        <a href="{{ env('ASSET_URL') }}/upload/{{ $file->file }}" target="_blank">
                                                                            <i class="fa fa-file mr-2"></i> {{ $file->file }}</a>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-4 fileBtn"
                                                                    style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                                                    {{-- <button type="button" class="btn btn-success removeBtn"><i class="fa fa-eye"></i></button> --}}
                                                                    <button type="button" class="btn btn-danger removeBtn"
                                                                        data-id="{{ $file->id }}"><i
                                                                            class="fa fa-times"></i> </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="newfiles[]"
                                                            value="{{ $file->file }}">

                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="uploadedFiles"></div>
                                        </div>
                                    </div>
                                    <div class="row footerTxt">
                                        <div style="text-align:center; "><img
                                                src="{{ env('ASSET_URL') }}/images/nlb_black.png" alt="NLB"
                                                align="middle"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 field">
                                            <label><input type="checkbox" disabled name="Agree" id="agree"
                                                    {{ isset($jobInfoSheet) && $jobInfoSheet != '' ? 'checked' : '' }}
                                                    {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'disabled' : '' }}>
                                                &nbsp;By submitting this
                                                form, you agree to the Liability Limitation terms stated herein.</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-6">
                                                <strong>Customer Signature:</strong>
                                            </div>
                                            <div class="col-md-6 field">
                                                <input name="Signature" disabled type="text"
                                                    value="{{ isset($jobInfoSheet) && $jobInfoSheet != '' ? $jobInfoSheet->signature : '' }}"
                                                    {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'disabled' : '' }} />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-2">
                                                <strong>Date:</strong>
                                            </div>
                                            <div class="col-md-4 field">
                                                <input name="SignatureDate" type="text" disabled class="date"
                                                    value="{{ isset($jobInfoSheet) && $jobInfoSheet != '' && !is_null($jobInfoSheet->signature_date) ? date('m/d/Y', strtotime($jobInfoSheet->signature_date)) : '' }}"
                                                    style="width:100px"
                                                    {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'disabled' : '' }} />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Show error -->
                        <div class="form-group error-field" style="display: none; color: red;">
                            <label for="error" class="col-sm-4 control-label">Error</label>

                            <div class="col-sm-8">
                                <span id="error"></span>
                            </div>
                        </div>
                        <!--Buttons-->
                        {{-- <div class="block-two" style="display: {{ (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? 'none' : 'block' }};">
                            <div class="black-box-main">
                                <button type="submit" class="btn btn-success btn-lg save" name="send" value="send">Send and Save</button>
                                <button type="submit" class="btn btn-info btn-lg save" name="send" value="save">Save</button>
                                <input type="button" value="Add Project Contact"
                                       class="btn btn-primary btn-lg editContact" data-contact="industry"
                                       data-type="add" data-contacttype="NA">
                            </div>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('basicUser.modals.contact_modal')
    <div id="jobDescriptionModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form class="form-horizontal" id="jobDescriptionForm" method="post" action="#">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Job Description Modal</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-4 control-label">Job Name : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="job_name" id="job_name"
                                    placeholder="Enter Job Name" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Job Address : </label>
                            <div class="col-md-8">
                                <textarea name="job_address" class="form-control error" placeholder="Enter Job Address"
                                    id="job_address"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Job City : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="job_city" id="job_city"
                                    placeholder="Enter Job City" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Job Zip : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="job_zip" id="job_zip"
                                    placeholder="Enter Job Zip Code" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div id="job-success-message">

                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <div class="col-md-12">
                                <button class="btn blue-btn form-control jobDescriptionSubmit" type="button">
                                    Save
                                </button>
                            </div>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button class="btn blue-btn-ext mr-auto jobDescriptionSubmit" type="button">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var token = '{{ csrf_token() }}';
        var addFileUrl = "{{ route('add.job.info.file') }}";
        var baseUrl = "{{ env('ASSET_URL') }}";
        var customerContactRoute = "{{ route('customer.submit.contact') }}";
        var removeFile = "{{ route('remove.job.info.file') }}"
        var project_id = "{{ $project->id }}";
        var user_id = "{{ Auth::user()->id }}";
        var autoComplete = "{{ route('autocomplete.contact.company') }}";
        var autoCompleteCompany = "{{ route('autocomplete.company') }}";
        var autoCompleteContact = "{{ route('autocomplete.contact.firstname') }}";
        var editJobDescriptionRoute = "{{ route('edit.job.description') }}";
        var getContactData = "{{ route('get.contact.data') }}";
        var newContacts = "{{ route('create.new.contacts') }}";
        var autoCompleteSubUserUrl = "{{ route('get.all.subuser.details') }}";
        var autoCompleteCompanyOnRoleChange = "{{ route('autocomplete.contact.company.rolechange') }}";
        var fetchCompanies = "{{ route('fetch.companies') }}";
    </script>
    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/job_info.js"></script>
@endsection
