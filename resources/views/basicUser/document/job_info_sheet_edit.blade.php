@extends('basicUser.layout.main')

@section('title', 'Job Info Sheet')

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
                                                    <input type="text" name="company_name"
                                                        value="{{ $job_info->company_name }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Address</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="company_address"
                                                        value="{{ $job_info->company_address }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2"><label>City</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="company_city"
                                                        value="{{ $job_info->company_city }}">
                                                </div>
                                                <div class="col-md-2"><label>State</label></div>
                                                <div class="col-md-2 smallField">
                                                    <select name="company_state">
                                                        <option value="">Select a State</option>
                                                        @foreach ($states as $state)
                                                            <option value="{{ $state->id }}"
                                                                {{ $job_info->company_state == $state->id ? 'selected' : '' }}>
                                                                {{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2"><label>Zip</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="company_zip"
                                                        value="{{ $job_info->company_zip }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"><label>Office Number</label></div>
                                                <div class="col-md-8 field">
                                                    <input type="text" name="company_office_phone"
                                                        value="{{ $job_info->company_office_phone }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 formTable rightTable">
                                            <div class="row">
                                                <div class="col-md-2"><label>First</label></div>
                                                <div class="col-md-4 smallField">
                                                    <input type="text" name="company_fname"
                                                        value="{{ $job_info->company_fname }}">
                                                </div>
                                                <div class="col-md-2"><label>Last</label></div>
                                                <div class="col-md-4 smallField">
                                                    <input type="text" name="company_lname"
                                                        value="{{ $job_info->company_lname }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"><label>Email</label></div>
                                                <div class="col-md-8 field">
                                                    <input type="text" name="company_email"
                                                        value="{{ $job_info->company_email }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Phone</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="company_phone"
                                                        value="{{ $job_info->company_phone }}">
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
                                <h3>JOB DESCRIPTION</h3>
                                <div class="formTable">
                                    <div class="row">
                                        <div class="col-md-2">
                                            JOB NAME
                                        </div>
                                        <div class="col-md-10 field">
                                            <input type="text" name="job_name" value="{{ $job_info->job_name }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            JOB ADDRESS
                                        </div>
                                        <div class="col-md-10 field">
                                            <input type="text" name="job_address" value="{{ $job_info->job_address }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            JOB CITY
                                        </div>
                                        <div class="col-md-10 field">
                                            <input type="text" name="job_city" value="{{ $job_info->job_city }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1">
                                            STATE
                                        </div>
                                        <div class="col-md-5 smallField">
                                            <select name="job_state" class="col-md-12">
                                                <option value="">Select a State</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ $job_info->job_state == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            ZIP
                                        </div>
                                        <div class="col-md-5 smallField">
                                            <input type="text" name="job_zip" value="{{ $job_info->job_zip }}">
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-4">
                                            ARE YOU THE SUBCONTRACTOR?
                                        </div>
                                        <div class="col-md-2">
                                            YES<input type="radio" name="is_subcontractor"
                                                      value="0" {{ $job_info->is_subcontractor == '0' ? 'checked' : '' }}>
                                            NO<input type="radio" name="is_subcontractor"
                                                     value="1" {{ $job_info->is_subcontractor == '1' ? 'checked' : '' }}>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <!-- Customer Information -->
                        <div class="block-two">
                            <div class="black-box-main">
                                <h3>
                                    <div class="row">
                                        Your Customer
                                        <span class="gc_show"
                                            style="display: {{ $job_info->is_gc == '0' ? 'block' : 'none' }};">
                                            / General Contractor
                                        </span>
                                    </div>
                                    <small style="font-size: 8px;">( <em>*To add edit customer contact please click on
                                            Add/Edit contact button</em> )</small>
                                </h3>
                                <input type="hidden" name="customer_id"
                                    value="{{ $job_info->customerContract ? $job_info->customerContract->id : '' }}">
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="col-lg-6 formTable">
                                            <div class="row">
                                                <div class="col-md-3"><label>Company</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_company" readonly
                                                        value="{{ $job_info->customerContract ? $job_info->customerContract->company : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Address</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_address" readonly
                                                        value="{{ $job_info->customerContract ? $job_info->customerContract->address : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2"><label>City</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="customer_city" readonly
                                                        value="{{ $job_info->customerContract ? $job_info->customerContract->city : '' }}">
                                                </div>
                                                <div class="col-md-2"><label>State</label></div>
                                                <div class="col-md-2 smallField">
                                                    <select name="job_state" class="col-md-12" disabled>
                                                        <option value="">Select a State</option>
                                                        @foreach ($states as $state)
                                                            <option value="{{ $state->id }}"
                                                                {{ $job_info->customerContract ? ($job_info->customerContract->state_id == $state->id ? 'selected' : 'disabled') : 'disabled' }}>
                                                                {{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2"><label>Zip</label></div>
                                                <div class="col-md-2 smallField">
                                                    <input type="text" name="customer_zip" readonly
                                                        value="{{ $job_info->customerContract ? $job_info->customerContract->zip : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Telephone</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_phone" readonly
                                                        value="{{ $job_info->customerContract ? $job_info->customerContract->phone : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Fax</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_fax" readonly
                                                        value="{{ $job_info->customerContract ? $job_info->customerContract->fax : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label>Web</label></div>
                                                <div class="col-md-9 field">
                                                    <input type="text" name="customer_zip" readonly
                                                        value="{{ $job_info->customerContract ? $job_info->customerContract->website : '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 formTable rightTable">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="button" value="Add/Edit Contact"
                                                        class="btn btn-primary editContact" data-contact="customer"
                                                        data-id="{{ $job_info->customerContract ? $job_info->customerContract->id : '' }}"
                                                        data-type="edit"
                                                        data-company="{{ $job_info->customerContract ? $job_info->customerContract->company : '' }}"
                                                        data-website="{{ $job_info->customerContract ? $job_info->customerContract->website : '' }}"
                                                        data-address="{{ $job_info->customerContract ? $job_info->customerContract->address : '' }}"
                                                        data-city="{{ $job_info->customerContract ? $job_info->customerContract->city : '' }}"
                                                        data-state="{{ $job_info->customerContract ? $job_info->customerContract->state_id : '' }}"
                                                        data-zip="{{ $job_info->customerContract ? $job_info->customerContract->zip : '' }}"
                                                        data-phone="{{ $job_info->customerContract ? $job_info->customerContract->phone : '' }}"
                                                        data-fax="{{ $job_info->customerContract ? $job_info->customerContract->fax : '' }}"
                                                        data-contactInformation="{{ $job_info->customerContract ? $job_info->customerContract->contactInformation : '' }}">
                                                </div>
                                            </div>
                                            @if ($job_info->customerContract != '' && count($job_info->customerContract->contactInformation) > 0)
                                                @foreach ($job_info->customerContract->contactInformation as $customerKey => $contactInformation)
                                                    @if ($customerKey == 0)
                                                        <div class="row">
                                                            <div class="col-md-2"><label>First</label></div>
                                                            <div class="col-md-2 smallField">
                                                                <input type="text" name="customerFirstName[]" readonly
                                                                    value="{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}">
                                                            </div>
                                                            <div class="col-md-2"><label>Last</label></div>
                                                            <div class="col-md-2 smallField">
                                                                <input type="text" name="customerLastName[]" readonly
                                                                    value="{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}">
                                                            </div>
                                                            <div class="col-md-2 "><label>Title</label></div>
                                                            <div class="col-md-2 smallField">
                                                                <input type="text" name="customerLastName[]" readonly
                                                                    value="{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4"><label>Direct Phone</label></div>
                                                            <div class="col-md-8 field">
                                                                <input type="text" name="customerDirectPhone[]" readonly
                                                                    value="{{ $contactInformation->direct_phone }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4"><label>Cell Phone</label></div>
                                                            <div class="col-md-8 field">
                                                                <input type="text" name="customerCellPhone[]" readonly
                                                                    value="{{ $contactInformation->cell }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4"><label>Email</label></div>
                                                            <div class="col-md-8 field">
                                                                <input type="text" name="customerEmail[]" readonly
                                                                    value="{{ $contactInformation->email }}">
                                                            </div>
                                                        </div>
                                                        @if (count($job_info->customerContract->contactInformation) > 1)
                                                            <p>
                                                                <a href="javascript:void(0)" class="show_more"
                                                                    id="customerMore{{ $job_info->customerContract->id }}"
                                                                    data-id="{{ $job_info->customerContract->id }}">Show
                                                                    More</a>
                                                                <a href="javascript:void(0)" class="show_less"
                                                                    id="customerLess{{ $job_info->customerContract->id }}"
                                                                    data-id="{{ $job_info->customerContract->id }}">Show
                                                                    Less</a>
                                                            </p>
                                                        @endif
                                                    @else
                                                        <div class="customer{{ $job_info->customerContract->id }}"
                                                            style="display: none;">
                                                            <div class="row">
                                                                <div class="col-md-2"><label>First</label></div>
                                                                <div class="col-md-2 smallField">
                                                                    <input type="text" name="customerFirstName[]" readonly
                                                                        value="{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}">
                                                                </div>
                                                                <div class="col-md-2"><label>Last</label></div>
                                                                <div class="col-md-2 smallField">
                                                                    <input type="text" name="customerLastName[]" readonly
                                                                        value="{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}">
                                                                </div>
                                                                <div class="col-md-2 "><label>Title</label></div>
                                                                <div class="col-md-2 smallField">
                                                                    <input type="text" name="customerLastName[]" readonly
                                                                        value="{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4"><label>Direct Phone</label></div>
                                                                <div class="col-md-8 field">
                                                                    <input type="text" name="customerDirectPhone[]" readonly
                                                                        value="{{ $contactInformation->direct_phone }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4"><label>Cell Phone</label></div>
                                                                <div class="col-md-8 field">
                                                                    <input type="text" name="customerCellPhone[]" readonly
                                                                        value="{{ $contactInformation->cell }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4"><label>Email</label></div>
                                                                <div class="col-md-8 field">
                                                                    <input type="text" name="customerEmail[]" readonly
                                                                        value="{{ $contactInformation->email }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
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
                                                            <input type="text" name="contract_amount"
                                                                value="{{ $job_info->contract_amount }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 formTable rightTable">
                                            <div class="row">
                                                <div class="col-md-4"><label>First day of work</label></div>
                                                <div class="col-md-8 field">
                                                    <input type="text" name="first_day_of_work" class="date"
                                                        value="{{ $job_info->first_day_of_work }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            if your customer is the General Contractor?
                                        </div>
                                        <div class="col-md-3">
                                            Yes <input type="radio" name="is_gc" class="is_gc" value="0"
                                                {{ $job_info->is_gc == '0' ? 'checked' : '' }}>
                                            No <input type="radio" name="is_gc" class="is_gc" value="1"
                                                {{ $job_info->is_gc == '1' ? 'checked' : '' }}>
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

                        @if (count($job_info->industryContacts) > 0)
                            @foreach ($job_info->industryContacts as $key => $contact)
                                @if ($contact->contacts->contact_type != 'General Contractor')
                                    @php($gc++)
                                @endif
                            @endforeach
                        @endif
                        @if ($gc == '1')
                            <div class="block-two gc_hide"
                                style="display: {{ $job_info->is_gc == '0' ? 'none' : 'block' }}">
                                <div class="black-box-main">
                                    <h3>General Contractor <small style="font-size: 8px;">( <em>*To add edit General
                                                Contractor please click on Add/Edit contact button</em> )</small></h3>
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
                                                                <option value="{{ $state->id }}">{{ $state->name }}
                                                                </option>
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
                                            <div class="col-lg-6 formTable">
                                                <div class="row">
                                                    <input type="button" value="Add Project Contact"
                                                        class="btn btn-primary editContact" data-contact="industry"
                                                        data-type="add" data-contactType="General Contractor" readonly>
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
                        @if (count($job_info->industryContacts) > 0)
                            @foreach ($job_info->industryContacts as $key => $contact)
                                @if ($contact->contacts->contact_type != 'Owner')
                                    @php($owner++)
                                @endif
                            @endforeach
                        @endif
                        @if ($owner == 1)
                            <!-- Owner -->
                            <div class="block-two">
                                <div class="black-box-main">
                                    <h3>Owner <small style="font-size: 8px;">( <em>*To add edit Owner please click on
                                                Add/Edit contact button</em> )</small></h3>
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
                                                                <option value="{{ $state->id }}">{{ $state->name }}
                                                                </option>
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
                                                <div class="row">
                                                    <input type="button" value="Add Project Contact"
                                                        class="btn btn-primary editContact" data-contact="industry"
                                                        data-type="add" data-contactType="Owner">
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
                            @if (count($job_info->industryContacts) > 0)
                                @foreach ($job_info->industryContacts as $key => $contact)
                                    <div class="block-two">
                                        <div class="black-box-main">
                                            <h3>{{ $contact->contacts->contact_type }} <small style="font-size: 8px;">(
                                                    <em>*To add edit customer contact please click on Add/Edit contact
                                                        button</em> )</small></h3>
                                            <input type="hidden" name="industry_type[{{ $key }}]"
                                                value="{{ $contact->contacts->contact_type }}">
                                            <input type="hidden" name="industry_id[{{ $key }}]"
                                                value="{{ $contact->contacts->id }}">
                                            <div class="table-responsive">
                                                <div class="row">
                                                    <div class="col-lg-6 formTable">
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Company</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text"
                                                                    name="industry_contact[{{ $key }}]" readonly
                                                                    value="{{ $contact->contacts ? $contact->contacts->company : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Address</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text"
                                                                    name="industry_contact[{{ $key }}]" readonly
                                                                    value="{{ $contact->contacts ? $contact->contacts->address : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-2"><label>City</label></div>
                                                            <div class="col-md-2 smallField">
                                                                <input type="text"
                                                                    name="industry_city[{{ $key }}]" readonly
                                                                    value="{{ $contact->contacts ? $contact->contacts->city : '' }}">
                                                            </div>
                                                            <div class="col-md-2"><label>State</label></div>
                                                            <div class="col-md-2 smallField">
                                                                <select name="industry_state[{{ $key }}]"
                                                                    class="col-md-12" disabled>
                                                                    <option value="">Select a State</option>
                                                                    @foreach ($states as $state)
                                                                        <option value="{{ $state->id }}"
                                                                            {{ $contact->contacts ? ($contact->contacts->state_id == $state->id ? 'selected' : 'disabled') : 'disabled' }}>
                                                                            {{ $state->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2"><label>Zip</label></div>
                                                            <div class="col-md-2 smallField">
                                                                <input type="text" name="industry_zip[{{ $key }}]"
                                                                    readonly
                                                                    value="{{ $contact->contacts ? $contact->contacts->zip : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Telephone</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text"
                                                                    name="industry_phone[{{ $key }}]" readonly
                                                                    value="{{ $contact->contacts ? $contact->contacts->phone : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Fax</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text" name="industry_fax[{{ $key }}]"
                                                                    readonly
                                                                    value="{{ $contact->contacts ? $contact->contacts->fax : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3"><label>Web</label></div>
                                                            <div class="col-md-9 field">
                                                                <input type="text"
                                                                    name="industry_website[{{ $key }}]" readonly
                                                                    value="{{ $contact->contacts ? $contact->contacts->website : '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 formTable rightTable">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="button" value="Add/Edit Contact"
                                                                    class="btn btn-primary editContact"
                                                                    data-contact="industry"
                                                                    data-id="{{ $contact->contacts->id }}"
                                                                    data-type="edit"
                                                                    data-Contact_type="{{ $contact->contacts->contact_type }}"
                                                                    data-company="{{ $contact->contacts->company }}"
                                                                    data-website="{{ $contact->contacts->website }}"
                                                                    data-address="{{ $contact->contacts->address }}"
                                                                    data-city="{{ $contact->contacts->city }}"
                                                                    data-state="{{ $contact->contacts->state_id }}"
                                                                    data-zip="{{ $contact->contacts->zip }}"
                                                                    data-phone="{{ $contact->contacts->phone }}"
                                                                    data-fax="{{ $contact->contacts->fax }}"
                                                                    data-contactInformation="{{ $contact->contacts->contactInformation }}">
                                                            </div>
                                                        </div>
                                                        @if (count($contact->contacts->contactInformation) > 0)
                                                            @foreach ($contact->contacts->contactInformation as $customerKey => $contactInformation)
                                                                @if ($customerKey == 0)
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
                                                                                value="{{ $contactInformation->direct_phone }}">
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
                                                                    @if (count($contact->contacts->contactInformation) > 1)
                                                                        <p>
                                                                            <a href="javascript:void(0)" class="show_more"
                                                                                id="customerMore{{ $contact->contacts->id }}"
                                                                                data-id="{{ $contact->contacts->id }}">Show
                                                                                More</a>
                                                                            <a href="javascript:void(0)" class="show_less"
                                                                                id="customerLess{{ $contact->contacts->id }}"
                                                                                data-id="{{ $contact->contacts->id }}">Show
                                                                                Less</a>
                                                                        </p>
                                                                    @endif
                                                                @else
                                                                    <div class="customer{{ $contact->contacts->id }}"
                                                                        style="display: none;">
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
                                                                                    value="{{ $contactInformation->direct_phone }}">
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
                                    <div class="col-md-12 footerTxt">
                                        <div class="col-md-4">
                                            <label>Attach a Document (Choose A FILE)</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="file" id="uploadFile">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 footerTxt">
                                            <div class="row">
                                                @if (count($job_info->jobFiles) > 0)
                                                    @foreach ($job_info->jobFiles as $file)

                                                        <div class="col-md-6" id="id{{ $file->id }}">
                                                            <div class="fileRow">
                                                                <div class="col-xs-8">
                                                                    <div class="fileName">
                                                                        <a href="{{ env('ASSET_URL') }}/upload/{{ $file->file }}" target="_blank"><i class="fa fa-file mr-2"></i> {{ $file->file }}</a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-4 fileBtn">
                                                                    <button type="button"
                                                                        class="btn btn-success removeBtn"><i
                                                                            class="fa fa-eye"></i></button>
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
                                            <label><input type="checkbox" name="Agree" id="agree">
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
                                                <input name="Signature" type="text"
                                                    value="{{ $job_info->signature }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-2">
                                                <strong>Date:</strong>
                                            </div>
                                            <div class="col-md-4 field">
                                                <input name="SignatureDate" type="text" class="date"
                                                    value="{{ $job_info->signature_date }}" style="width:100px" />
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
                        <div class="block-two">
                            <div class="black-box-main">
                                <input type="submit" class="btn btn-success btn-lg save" name="submit"
                                    value="Send and Save">
                                <input type="submit" class="btn btn-info btn-lg save" name="submit" value="Save">
                                <input type="button" value="Add Project Contact" class="btn btn-primary btn-lg editContact"
                                    data-contact="industry" data-type="add" data-contacttype="NA">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div id="addIndustryModel" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="title"></span> Contact</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="IndustryEditForm" method="post" action="#" autocomplete="off">
                        <div class="form-group" id="contactType">
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
                        <input type="hidden" id="contact">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Company : </label>
                            <div class="col-md-8">
                                <input class="form-control error autocomplete" type="text" name="company" id="company1"
                                    placeholder="Enter Company Name" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Website : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="website" id="website1"
                                    placeholder="Enter Website" />
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
                                    placeholder="Enter City" />
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
                                    placeholder="Enter Zip Code" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Company Phone : </label>
                            <div class="col-md-8">
                                <input class="form-control error phone1" type="text" name="phone1" id="phone1"
                                    placeholder="Enter Phone Number" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Fax : </label>
                            <div class="col-md-8">
                                <input class="form-control error" type="text" name="fax" id="fax1"
                                    placeholder="Enter Fax Number" />
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
                                            <input type="hidden" name="customer_id[]" id="customer_id" value="0">
                                            <td>
                                                <input class="form-control error autocompleteContact" type="text"
                                                    name="firstName[]" id="firstName1" placeholder="First Name" />
                                            </td>
                                            <td>
                                                <input class="form-control error" type="text" name="lastName[]"
                                                    id="lastName1" placeholder="Last Name" />
                                            </td>
                                            <td>
                                                <input class="form-control error" type="email" name="email[]" id="email1"
                                                    placeholder="Email" />
                                            </td>
                                            <td>
                                                <input class="form-control error" type="text" name="directPhone[]"
                                                    id="directPhone1" placeholder="Direct Phone" />
                                            </td>
                                            <td>
                                                <input class="form-control error" type="text" name="cellPhone[]"
                                                    id="cellPhone1" placeholder="Cell Phone" />
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="add_button1" title="Add field"><img
                                                        src="{{ env('ASSET_URL') }}/images/add-icon.png" height="30px"
                                                        width="30px" /></a>
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
                                <button class="btn blue-btn form-control formSubmitIndustry" type="button">
                                    Save
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
    </div>
@endsection

@section('script')
    <script>
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
        $(document).ready(function() {
            $('#uploadFile').on('change', function() {
                var form = new FormData();
                form.append("lien", $("#uploadFile")[0].files[0]);
                form.append("_token", '{{ csrf_token() }}');
                $.ajax({
                    type: "POST",
                    url: "{{ route('add.job.info.file') }}",
                    data: form,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        HoldOn.open();
                    },
                    complete: function() {
                        HoldOn.close();
                    },
                    success: function(data) {
                        if (data.status == true) {
                            $('.uploadedFiles').append(
                                '<div class="col-md-6" id="id' + data.time + '"><div class="fileRow"><div class="col-xs-8"> <div class="fileName"><a href="{{ env('ASSET_URL') }}/upload/' + data.name + '" target="_blank" >' + 
                                '<i class="fa fa-file mr-2"></i> {{ $file->file }}</a>' + 
                                '<div></div>div class="col-xs-4 fileBtn">' +
                                '<button type="button" class="btn btn-success removeBtn" data-id="' + data.time + '"><i class="fa fa-eye"></i></button>' +
                                '<button type="button" class="btn btn-danger removeBtn" data-id="' + data.time + '"><i class="fa fa-times"></i> </button></div> ' +
                                '</div><input type="hidden" name="newfiles[]" value="' + data.name + '">' +
                                '</div></div>');
                        } else {
                            $('#error').text(data.message);
                            $('.error-field').show();
                        }

                    }
                });
            });
            $(document).on("click", ".removeBtn", function() {
                var id = $(this).data('id');
                $('#id' + id).remove();
            });
            $('.save').on('click', function(e) {
                var chkPassport = document.getElementById("agree");
                if (!chkPassport.checked) {
                    e.preventDefault();
                    swal({
                        type: 'warning',
                        title: 'Warning',
                        text: 'Please accept the Terms of Service!',
                    })
                }
            });
            $('.is_gc').on('change', function() {
                var value = $(this).val();
                if (value == 0) {
                    $('.gc_show').show();
                    $('.gc_hide').hide();
                } else {
                    $('.gc_hide').show();
                    $('.gc_show').hide();
                }
            });
            $('.show_more').click(function() {
                var id = $(this).data('id');
                $(this).addClass('show-inactive');
                $('#customerLess' + id).removeClass('show-inactive');
                $('.customer' + id).show();
            });
            $('.show_less').click(function() {
                var id = $(this).data('id');
                $(this).addClass('show-inactive');
                $('#customerMore' + id).removeClass('show-inactive');
                $('.customer' + id).hide();
            }).addClass('show-inactive');
            $('.editContact').on('click', function() {
                $('#IndustryEditForm')[0].reset();
                $('.external').remove();
                var type = $(this).data('type');
                var contact = $(this).data('contact');
                $('#id1').val($(this).data('id'));
                if (contact == 'customer') {
                    $('#contactType').hide();
                    $('.autocomplete').attr('data-type', 'customer');
                } else {
                    $('#contactType').show();
                    $('.autocomplete').attr('data-type', 'industry');
                }
                $('#contact').val(contact);
                if (type == 'add') {
                    $('.formSubmitIndustry').attr('data-type', 'add');
                    $('.formSubmit').attr('data-type', 'add');
                    $('.title').html('Add ' + contact);
                    var contactType = $(this).data('contacttype');
                    $('#contactType1').removeAttr('disabled');
                    if (contactType != 'NA') {
                        $('#contactType1').val(contactType);
                        $('#contactType1').attr('disabled', true);
                    }
                } else {
                    $('#contactType1').removeAttr('disabled');
                    $('.formSubmitIndustry').attr('data-type', 'edit');
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
                        $.each(contactInformation, function(index, value) {
                            if (index == 0) {
                                if (value.title == 'Other') {
                                    $('#title1').hide();
                                    $('#title_other').show();
                                }
                                $('#title1').val(value.title);
                                $('#customer_id').val(value.id);
                                $('#title_other').val(value.title_other);
                                $('#firstName1').val(value.first_name);
                                $('#lastName1').val(value.last_name);
                                $('#email1').val(value.email);
                                $('#directPhone1').val(value.direct_phone);
                                $('#cellPhone1').val(value.cell);
                            } else {
                                var title_other = value.title_other != null ? value.title_other :
                                    '';
                                var firstName = value.first_name != null ? value.first_name : '';
                                var lastName = value.last_name != null ? value.last_name : '';
                                var email = value.email != null ? value.email : '';
                                var direct_phone = value.direct_phone != null ? value.direct_phone :
                                    '';
                                var cell = value.cell != null ? value.cell : '';
                                if (value.title != '') {
                                    if (value.title != 'Other') {
                                        html +=
                                            '<tr class="external"><td> <select class="form-control error title1" name="title[]">' +
                                            ' <option value="CEO" ' + (value.title === "CEO" ?
                                                "selected" : "") + '>CEO</option> ' +
                                            '<option value="CFO" ' + (value.title === "CFO" ?
                                                "selected" : "") + '>CFO</option> ' +
                                            '<option value="Credit" ' + (value.title === "Credit" ?
                                                "selected" : "") + '>Credit</option>' +
                                            ' <option value="PM" ' + (value.title === "PM" ?
                                                "selected" : "") + '>PM</option> ' +
                                            '<option value="Corporation Counsel" ' + (value
                                                .title === "Corporation Counsel" ? "selected" : ""
                                            ) + '>Corporation Counsel</option> ' +
                                            '<option value="A/R Manager" ' + (value.title ===
                                                "A/R Manager" ? "selected" : "") +
                                            '>A/R Manager</option> ' +
                                            '<option value="Other">Other</option> </select>' +
                                            '<div class="title_other" style="display: none;"> ' +
                                            '<input type="text" name="titleOther[]" class="form-control error" value="' +
                                            title_other + '">' +
                                            ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>';
                                    } else {
                                        html +=
                                            '<tr class="external"><td> <select class="form-control error title1" name="title[]"  style="display: none;">' +
                                            ' <option value="CEO">CEO</option> ' +
                                            '<option value="CFO" >CFO</option> ' +
                                            '<option value="Credit" >Credit</option> <option value="PM">PM</option> ' +
                                            '<option value="Corporation Counsel" >Corporation Counsel</option> ' +
                                            '<option value="A/R Manager">A/R Manager</option> ' +
                                            '<option value="Other">Other</option> </select>' +
                                            '<div class="title_other"> ' +
                                            '<input type="text" name="titleOther[]" class="form-control error" value="' +
                                            title_other + '">' +
                                            ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>';
                                    }
                                } else {
                                    html +=
                                        '<tr class="external"><td> <select class="form-control error title1" name="title[]" >' +
                                        ' <option value="CEO">CEO</option> ' +
                                        '<option value="CFO">CFO</option> ' +
                                        '<option value="Credit">Credit</option> <option value="PM">PM</option> ' +
                                        '<option value="Corporation Counsel">Corporation Counsel</option> ' +
                                        '<option value="A/R Manager">A/R Manager</option> ' +
                                        '<option value="Other">Other</option> </select>' +
                                        '<div class="title_other" style="display: none;"> ' +
                                        '<input type="text" name="titleOther[]" class="form-control error" value="' +
                                        value.title_other + '">' +
                                        ' <a href="#" class="titleOtherBtn">Change</a> </div> </td>';
                                }

                                html +=
                                    '<td><input class="form-control error autocompleteContact" type="text" name="firstName[]" value="' +
                                    firstName + '" placeholder="First Name"/></td>' +
                                    '<td><input class="form-control error" type="text" name="lastName[]" value="' +
                                    lastName + '" placeholder="Last Name"/>' +
                                    '<input class="form-control error" type="hidden" name="customer_id[]" value="' +
                                    value.id + '" placeholder="Last Name"/></td>' +
                                    '<td><input class="form-control error" type="email" name="email[]" value="' +
                                    email + '" placeholder="Email"/></td>' +
                                    '<td><input class="form-control error" type="text" name="directPhone[]" value="' +
                                    direct_phone + '" placeholder="Direct Phone"/></td>' +
                                    '<td><input class="form-control error" type="text" name="cellPhone[]" value="' +
                                    cell + '" placeholder="Cell Phone"/></td>' +
                                    '<td><a href="javascript:void(0);" class="remove_button1" title="Remove field">' +
                                    '<img src="{{ env('ASSET_URL') }}/images/remove-icon.png" height="30px" width="30px"/></a></td></tr>';
                            }
                        });
                    }
                    $('.field_wrapper3 tr:last').after(html);
                    $('#id1').val($(this).data('id'));
                    $('.title').html('Edit ' + contact);
                }
                $('#addIndustryModel').modal('show');
            });
            $('.formSubmitIndustry').on('click', function() {
                var company = $('#company1').val();
                var contact = $('#contact').val();
                var flag = true;
                var id = $('#id1').val();
                if (company == '') {
                    $('#company1').addClass('input-error');
                    flag = false;
                }
                var contactType = $('#contactType1').val();
                if (contact == 'industry') {
                    if (contactType == '') {
                        $('#contactType1').addClass('input-error');
                        flag = false;
                    }
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
                    .map(function() {
                        return $(this).val();
                    }).get();


                var lastName = $("input[name='lastName[]']")
                    .map(function() {
                        return $(this).val();
                    }).get();

                var email = $("input[name='email[]']")
                    .map(function() {
                        return $(this).val();
                    }).get();

                var directPhone = $("input[name='directPhone[]']")
                    .map(function() {
                        return $(this).val();
                    }).get();

                var cellPhone = $("input[name='cellPhone[]']")
                    .map(function() {
                        return $(this).val();
                    }).get();

                var title = $("select[name='title[]']")
                    .map(function() {
                        return $(this).val();
                    }).get();

                var titleOther = $("input[name='titleOther[]']")
                    .map(function() {
                        return $(this).val();
                    }).get();
                var type = $(this).data('type');

                if (flag) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('customer.submit.contact') }}",
                        data: {
                            id: id,
                            type: type,
                            contact: contact,
                            contactType: contactType,
                            formContactType: 'industryProject',
                            project_id: '{{ $project->id }}',
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
                        success: function(data) {
                            if (data.status) {
                                $('#success-message').html('<p class="alert alert-success">' +
                                    data.message + '</p>');
                                $('#IndustryEditForm')[0].reset();
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                $('#success-message').html('<p class="input-error">' + data
                                    .message + '</p>');
                            }
                        }
                    });
                }
            });
            $('.error').on('focus', function() {
                $(this).removeClass('input-error');
                $('#error-message').html('');
            });
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
                '<td><input class="form-control error autocompleteContact" type="text" name="firstName[]" placeholder="First Name"/></td>' +
                '<td><input class="form-control error" type="text" name="lastName[]" placeholder="Last Name"/>' +
                '<input class="form-control error" type="hidden" name="customer_id[]" value="0" placeholder="Last Name"/></td>' +
                '<td><input class="form-control error" type="email" name="email[]" placeholder="Email"/></td>' +
                '<td><input class="form-control error" type="text" name="directPhone[]" placeholder="Direct Phone"/></td>' +
                '<td><input class="form-control error" type="text" name="cellPhone[]" placeholder="Cell Phone"/></td>' +
                '<td><a href="javascript:void(0);" class="remove_button1" title="Remove field">' +
                '<img src="{{ env('ASSET_URL') }}/images/remove-icon.png" height="30px" width="30px"/></a></td></tr>'; //New input field html
            var x1 = 1; //Initial field counter is 1
            $(addButton1).click(function() { //Once add button is clicked
                if (x1 < maxField1) { //Check maximum number of input fields
                    x1++; //Increment field counter
                    $('.field_wrapper3 tr:last').after(fieldHTML1); // Add field html
                }
            });
            $(wrapper1).on('click', '.remove_button1', function(e) { //Once remove button is clicked
                e.preventDefault();
                $(this).parents('tr').remove(); //Remove field html
                x1--; //Decrement field counter
            });
            $('.autocomplete').autocomplete({
                source: function(request, response) {
                    var type = $('.autocomplete').data('type');
                    var key = $('.autocomplete').val();
                    $.ajax({
                        url: "{{ route('autocomplete') }}",
                        dataType: "json",
                        data: {
                            type: type,
                            key: key,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
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
                    $('.external').remove();
                    $('#contactType1').removeAttr('disabled');
                    $('.formSubmitIndustry').attr('data-type', 'edit');
                    $('#contactType1').val(ui.item.data.contact_type);
                    $('#id1').val(ui.item.id);
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
            $(document).delegate('.autocompleteContact', 'focus', function(e) {
                $(this).autocomplete({
                    source: function(request, response) {
                        var id = $('#id1').val();
                        //var key = $(this).val();
                        $.ajax({
                            url: "{{ route('autocomplete.contract') }}",
                            dataType: "json",
                            data: {
                                id: id,
                                //   key: key,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                var array = $.map(data.data, function(item) {
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
                    select: function(event, ui) {
                        if (ui.item.data.title != '') {
                            if (ui.item.data.title != 'Other') {
                                $(this).closest("tr").find(".title1").val(ui.item.data.title);
                            } else {
                                $(this).closest("tr").find("input[name='titleOther[]']").val(ui
                                    .item.data.title_other);
                                $(this).closest("tr").find(".title_other").show();
                                $(this).closest("tr").find(".title1").hide();
                            }
                        } else {
                            $(this).closest("tr").find(".title1").val('CEO');
                        }
                        $(this).closest("tr").find("input[name='lastName[]']").val(ui.item.data
                            .last_name);
                        $(this).closest("tr").find("input[name='customer_id[]']").val(ui.item
                            .data.id);
                        $(this).closest("tr").find("input[name='email[]']").val(ui.item.data
                            .email);
                        $(this).closest("tr").find("input[name='directPhone[]']").val(ui.item
                            .data.direct_phone);
                        $(this).closest("tr").find("input[name='cellPhone[]']").val(ui.item.data
                            .cell);
                    }
                });
            });
            $(document).delegate('autocompleteContact', 'blur', function(e) {
                var target = $(this);
                if (target.hasClass('ui-autocomplete-input')) {
                    target.autocomplete('destroy');
                }
            });

        });
    </script>
@endsection
