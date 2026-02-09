@extends('basicUser.projects.create')

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

@section('body')
    @php
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $mobile =
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) ||
        preg_match(
            '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4),
        );
    @endphp

    @php
    $customer_view = 'false';
    if (isset($_GET['customer'])) {
        $customer_view = 'true';
    }

    $company_view = 'false';
    if (isset($_GET['company'])) {
        $company_view = 'true';
    }
    @endphp
    <span id="stepNumDetailed" data-step="5"></span>

    @include('basicUser.partials.multi-step-form')

    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
    @if (isset($_GET['view']) && $_GET['view'] === 'detailed')

    @else
        <span id="stepNum" data-step="4"></span>
    @endif
    {{-- <div class="row" style="width:100%; margin:0; padding: 0 50px;">
    <input id="go_back" style="margin:0; float: left;" class="btn btn-primary btn-view-jobsheet project-create-continue"type="button" value="Back"/>
</div> --}}
    <div class="">

        <div class="row">
            @if (isset($_GET['create']))
                <div class="col-sm-12">
                    <div class="project-header">
                        @if (!isset($_GET['view']) || $_GET['view'] === 'express')
                            <h1>Create A New Project</h1>
                        @elseif(isset($_GET['view']) && $_GET['view'] === 'detailed')
                            <h1>File A Claim</h1>
                        @endif
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="25"
                                aria-valuemin="0" aria-valuemax="100">Step 3</div>
                        </div>
                    </div>
                @else
            @endif
            <div id="gc" data-gc='false'></div>
            @if (isset($_GET['view']) && $_GET['view'] === 'detailed')
                <form method="post" action="{{ route('save.new.job', ['view' => 'detailed']) }}"
                    class="form-horizontal create-project-form create-project-form--wide" id="jobform">
                @elseif(!isset($_GET['view']) && isset($_GET['create']) && $_GET['create'] === 'true')
                    <form method="post" action="{{ route('save.new.job', ['view' => 'express']) }}"
                        class="form-horizontal create-project-form create-project-form--wide" id="jobform">
                    @else
                        <form method="post" action="{{ route('save.new.job') }}"
                            class="form-horizontal create-project-form create-project-form--wide" id="jobform">
            @endif
            <input type="hidden" value="{{ $project->id }}" name="project_id">
            {{ csrf_field() }}
            <div class="create-project-form-header">
                <h2>Job Information Sheet</h2>
                <div class="expand">
                    <h4>Expand All</h4>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider" id="switch" data-action="expand"></span>
                    </label>
                </div>
            </div>

            <div id="company_information" class="job-info-accordion job-info-accordion--top-margin"
                data-target="company_info">
                Company Information <i class="fa fa-caret-down job-info-down" aria-hidden="true"></i>

            </div>
            <div class="job-info-panel" id="company_info">
                <input type="hidden" name="customer_company_id" id="customer_company_id" value="{{ $project->user_id }}">
                <!--<div class="block-one">
                        <div class="black-box-main">-->
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 formTable">
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_name">Company</label>
                                {{-- {{ var_dump($jobInfoSheet) }} --}}
                                <input  type="text" name="company_name" id="company_name" value="{{ $user->details->company }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_address">Address</label>
                                <input  type="text" name="company_address" id="company_address"
                                    value="{{$user->details->address }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_city">City</label>
                                <input  type="text" name="company_city" id="company_city"
                                    value="{{$user->details->city}}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_state">State</label>
								<select class="" name="company_state" id="userState">
                                            <option value="">---Select---</option>
                                            @foreach ($states as $key => $state)
                                                @if($state->id === $user->details->state_id)
                                                    <option selected value="{{ $key }}">{{ $state->name}}</option>
                                                @else
                                                    <option value="{{ $key }}">{{ $state->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>


                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_zip">Zip</label>
                                <input type="text" name="company_zip"  id="coompany_zip"
                                    value="{{$user->details->zip}}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_office_phone">Office Number</label>
                                <a href="tel:{{ $user->details ? $user->details->office_phone : '' }}">
                                    {{ $user->details ? $user->details->office_phone : '' }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 formTable">
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_fname">First Name</label>
                                <input type="text" name="company_fname"
                                    value="{{ $user->details ? $user->details->first_name : '' }}"
                                    class="autoCompleteSubUser" autocomplete="off">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_lname">Last Name</label>
                                <input type="text" name="company_lname"  id="company_lname"
                                    value="{{ $user->details ? $user->details->last_name : '' }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_email">Email</label>
                                <a href="mailto: {{ $user->details ? $user->email : '' }}">
                                    {{ $user->details ? $user->email : '' }}
                                </a>

                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="company_phone">Phone</label>

                                <a href="tel:{{ $user->details ? $user->details->phone : '' }}">
                                    {{ $user->details ? $user->details->phone : '' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- Ends Company Information Section -->
            <div id="customer_information" class="job-info-accordion" data-target="your_customer">
                Your Customer Information

                <i class="fa fa-caret-down job-info-down" aria-hidden="true"></i>
            </div>
            <div class="job-info-panel" id="your_customer">
                <div class="table-responsive">
                    <div class="row">
                        <input type="hidden" id="hiddenCustomerId" name="customer_id"
                            value="{{ $project->customer_contract ? $project->customer_contract->id : '' }}">
                        <span class="gc_show" id="show_general_contractor"
                            style="display:{{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '0' ? 'inline' : 'none' }}{{-- {{ isset($jobInfoSheet) && $jobInfoSheet != '' ? ($jobInfoSheet->is_gc == '0' ? 'inline' : 'none') : ($project->originalCustomer->name == 'Original Contractor' ? 'inline' : (!isset($jobInfoSheet) ? 'inline'  : 'none')) }}; --}}">
                            / General Contractor </span> {{-- <small style="font-size: 8px;">( <em>*To add edit customer contact please click on Add/Edit contact button</em> )</small> --}}
                        <div class="col-md-6 col-sm-12 formTable">
                            <div class="job-info-panel-input-wrapper">
                                <label for="customer_company">Company</label>

                                <input type="text" name="customer_company" readonly
                                    value="{{ @$companyExists->company}}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="customer_address">Address</label>
                                <input type="text" name="customer_address" readonly
                                    value="{{ @$companyExists->address}}">

                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="customer_city">City</label>
                                <input type="text" name="customer_city" readonly
                                    value="{{ @$companyExists->city }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="job_state">State</label>
                                @if (!empty($companyExists->state_id))
                                    @foreach ($states as $state)
                                        @if ($state->id === $companyExists->state_id)
                                        <select disabled class="" name="customer_state_id" id="customer_state_id">
                                            <option value="">---Select---</option>
                                            @foreach ($states as $key => $state)
                                                @if($state->id === $companyExists->state_id)
                                                    <option selected value="{{ $key }}">{{ $state->name}}</option>
                                                @else
                                                    <option value="{{ $key }}">{{ $state->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        @endif
                                    @endforeach
                                @else
										<select disabled class="" readonly name="customer_state_id" id="customer_state_id">
                                            <option value="">---Select---</option>
                                            @foreach ($states as $key => $state)
                                                <option value="{{ $key }}">{{ $state->name}}
                                                </option>

                                            @endforeach
                                        </select>

                                @endif
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="customer_zip">Zip</label>
                                <input type="text" name="customer_zip" readonly
                                    value="{{ @$companyExists->zip }}">
                            </div>
                            @php
                                $customer_phone = $project->customer_contract ? $project->customer_contract->contactsAd->phone : '';
                            @endphp
                            <div class="job-info-panel-input-wrapper">
                                <label for="customer_phone">Phone Number</label>
                                <a href="tel:{{ $customer_phone }}">
                                    {{ $customer_phone }}
                                </a>
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="customer_fax">Fax Number</label>
                                <input type="text" name="customer_fax" readonly
                                    value="{{ @$companyExists->fax }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="customer_web">Website</label>
                                <input type="text" name="customer_web" readonly
                                    value="{{ @$companyExists->website }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 formTable">
                            <div class="job-info-panel-input-wrapper">
                                <label for="customerFirstName[]">First Name</label>
                                <input type="text" name="customerFirstName[]" readonly
                                    value="{{ $project->customer_contract != '' ? $project->customer_contract->contacts->first_name : 'N/A' }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="customerLastName[]">Last Name</label>
                                <input type="text" name="customerLastName[]" readonly
                                    value="{{ $project->customer_contract != '' ? $project->customer_contract->contacts->last_name : 'N/A' }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="customerTitle">Title</label>
                                <input type="text" name="customerTitle" readonly
                                    value="{{ $project->customer_contract != '' ? ($project->customer_contract->contacts->title == 'Other' ? $project->customer_contract->contacts->title_other : $project->customer_contract->contacts->title) : 'N/A' }}">
                            </div>
                            @php
                                $contactPhone = $project->customer_contract != '' ? $project->customer_contract->contacts->phone : 'N/A';
                                $cellPhone = $project->customer_contract != '' ? $project->customer_contract->contacts->cell : 'N/A';
                                $email = $project->customer_contract != '' ? $project->customer_contract->contacts->email : 'N/A';
                            @endphp
                            <div class="job-info-panel-input-wrapper">
                                <label for="customerDirectPhone[]">Phone Number</label>
                                <a href="tel:{{ $contactPhone }}">
                                    {{ $contactPhone }}
                                </a>
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="customerCellPhone[]">Cell Phone</label>
                                <a href="tel:{{ $contactPhone }}">
                                    {{ $cellPhone }}
                                </a>
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="customerEmail[]">Email</label>
                                <a href="mailto: {{ $email }}">{{ $email }}</a>

                            </div>
                            <div class="row"
                                style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                <div class="col-md-12">
                                    @if (!is_null($project->customer_contract))

                                        <input type="button" value="Edit Contact"
                                            class="btn btn-primary editContact project-create-continue"
                                            data-contact="customer"
                                            data-id="{{ $project->customer_contract ? $project->customer_contract->id : '0' }}"
                                            data-map_id="{{ !is_null($project->customer_contract) ? $project->customer_contract->id : 0 }}"
                                            data-type="edit" data-contactType="N/A"
                                            data-customer_id="{{ $project->customer_contract ? $project->customer_contract->company_contact_id : '0' }}"
                                            data-company_id="{{ isset($project->customer_contract) ? $project->customer_contract->company_id : '0' }}">
                                    @else
                                        <input type="button" value="Edit Contact"
                                            class="btn btn-primary editContact project-create-continue"
                                            data-contact="customer" data-type="add" data-contacttype="N/A">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Ends Your Customer Section -->

            <div class="job-info-accordion" data-target="your_contract">
                <span class="col-md-6" style="padding-left:0px;">
                    Contract Information
                </span>
                Project Dates
                <i class="fa fa-caret-down job-info-down" aria-hidden="true"></i>
            </div>
            <div class="job-info-panel" id="your_contract">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-md-6 formTable contractInformation job-info-panel--label">
                            {{-- <h4>Contract Information</h4> --}}
                            @php
                                // Using PHP to check for contract amounts or set to 0
                                $baseAmount = isset($contract->base_amount) && !empty($contract->base_amount) ? $contract->base_amount : 0;
                                $extraCharges = isset($contract->extra_amount) && !empty($contract->extra_amount) ? $contract->extra_amount : 0;
                                $credits = isset($contract->credits) && !empty($contract->credits) ? $contract->credits : 0;
                                $revised = $baseAmount + $extraCharges;
                                $totalClaimAmount = $revised - $credits;
                                // contractCreated is used to set a value on data-contract, this watches for contracts not already created
                                $contractCreated = isset($contract) && !empty($contract) ? 'contract-exists' : 'no-contract';
                            @endphp
                            <!-- Calculate contract totals and format to 2 decimal places -->
                            <div class="col-sm-6 no-padding">
                                <label for="baseContract">Base Contract Amount</label>
                            </div>
                            <div class="col-sm-6 no-padding">
                                <input type="text" id="baseContract" name="baseContract"
                                    value="{{ number_format(isset($baseAmount) && !empty($baseAmount) ? $baseAmount : '0', 2) }}"
                                    data-contract="{{ $contractCreated }}">
                            </div>
                            <div class="col-sm-6 no-padding">
                                <label for="extraCharges" class="label-light label-light--mobile">+ Value of Extras</label>
                            </div>
                            <div class="col-sm-6 no-padding">
                                <input type="text" id="extraCharges" name="extraCharges"
                                    value="{{ number_format(isset($extraCharges) && !empty($extraCharges) ? $extraCharges : '0', 2) }}"
                                    data-contract="{{ $contractCreated }}">
                            </div>
                            <div class="col-sm-6 no-padding">
                                <label for="revisedTotal">Revised Contract Total</label>
                            </div>
                            <div class="col-sm-6 no-padding">
                                <input type="text" id="revisedTotal" name="revisedTotal"
                                    value="{{ number_format(isset($revised) && !empty($revised) ? $revised : '0', 2) }}"
                                    data-contract="{{ $contractCreated }}" >
                            </div>
                            <div class="col-sm-6 no-padding">
                                <label for="credits" class="label-light label-light--mobile">- Credits to Date</label>
                            </div>
                            <div class="col-sm-6 no-padding">
                                <input type="text" id="credits" name="credits"
                                    value="{{ number_format(isset($credits) && !empty($credits) ? $credits : '0', 2) }}"
                                    data-contract="{{ $contractCreated }}">
                            </div>
                            <div class="col-sm-6 no-padding">
                                <label for="contract_amount">Total Claim Amount</label>
                            </div>
                            <div class="col-sm-6 no-padding">
                                <input type="text" id="contract_amount" name="contract_amount"
                                    value="{{ number_format(isset($totalClaimAmount) && !empty($totalClaimAmount) ? $totalClaimAmount : '0', 2) }}"
                                    data-contract="{{ $contractCreated }}" >
                            </div>
                        </div>
                        <div class="col-md-6 formTable no-padding dateArea job-info-panel--label">
                            {{-- <h4 class="pad-left">Project Dates</h4> --}}
                            @foreach ($projectDates as $date)
                                <div class="row dateBreak">
                                    <div class="col-sm-6">
                                        <label>{{ $date['name'] }}</label>
                                    </div>
                                    <div class="col-sm-6 dateContainer">
                                        @if (empty($date['dates']))
                                            @if ($date['recurring'] === 1)
                                                <input type="text" class="form-control date multiple"
                                                    name="remedyDates[{{ $date['id'] }}]" value=""
                                                    data-provide="datepicker" data-recurring="{{ $date['recurring'] }}"
                                                    data-dateId="{{ $date['id'] }}" data-existing="false">
                                            @else
                                                <input type="text" class="form-control date multiple"
                                                    name="remedyDates[{{ $date['id'] }}]" value=""
                                                    data-provide="datepicker" data-recurring="{{ $date['recurring'] }}"
                                                    data-dateId="{{ $date['id'] }}" data-existing="false">
                                            @endif
                                        @else
                                            @foreach ($date['dates'] as $value)
                                                @if ($date['recurring'] === 1)
                                                    <input type="text" class="form-control date multiple"
                                                        name="remedyDates[{{ $date['id'] }}]"
                                                        value="{{ $value['value'] }}" data-provide="datepicker"
                                                        data-recurring="{{ $date['recurring'] }}"
                                                        data-dateId="{{ $value['id'] }}" data-existing="true">
                                                @else
                                                    <input type="text" class="form-control date multiple"
                                                        name="remedyDates[{{ $date['id'] }}]"
                                                        value="{{ $value['value'] }}" data-provide="datepicker"
                                                        data-recurring="{{ $date['recurring'] }}"
                                                        data-dateId="{{ $value['id'] }}" data-existing="true">

                                                @endif
                                            @endforeach
                                            @if ($date['recurring'] === 1)

                                                <input type="text" class="form-control date multiple"
                                                    name="remedyDates[{{ $date['id'] }}]" value=""
                                                    data-provide="datepicker" data-recurring="{{ $date['recurring'] }}"
                                                    data-dateId="1" data-existing="false">

                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div> <!-- Ends Your Customer Section -->


            <div class="job-info-accordion" data-target="job_description">
                Job Description

                <i class="fa fa-caret-down job-info-down" aria-hidden="true"></i>
            </div>
            <div class="job-info-panel" id="job_description">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 formTable">
                            <div class="job-info-panel-input-wrapper">
                                <label for="job_name">Job Name</label>
                                <input type="text" name="job_name"
                                    value="{{ $project ? $project->project_name : '' }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="job_address">Job Address</label>
                                <input type="text" name="job_address"
                                    value="{{ $project ? $project->address : '' }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="job_city">Job City</label>
                                <input type="text" name="job_city"  value="{{ $project ? $project->city : '' }}">
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="job_state">Job State</label>
                                @foreach ($states as $state)
                                    @if ($state->id === $project->state_id)
                                        <input type="text" value="{{ $state->name }}" >
                                    @endif
                                @endforeach
                            </div>
                            <div class="job-info-panel-input-wrapper">
                                <label for="job_zip">Job Zip</label>
                                <input type="text" name="job_zip"  value="{{ $project ? $project->zip : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 formTable"
                            style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                            <input type="button" value="Job Description Edit"
                                class="btn btn-primary jobDescription project-create-continue"
                                data-name="{{ $project ? $project->project_name : '' }}"
                                data-address="{{ $project->address }}" data-city="{{ $project->city }}"
                                data-zip="{{ $project->zip }}">
                        </div>
                    </div>
                </div>
            </div> <!-- Ends Job Description Section -->
            @php $gc = 1; @endphp

            @if (count($projectContacts) > 0)
                @foreach ($projectContacts as $key => $contact)
                    @if ($contact['type'] == 'General Contractor')
                        @php $gc++; @endphp
                    @endif
                @endforeach
            @endif
            @if ($gc == 1)
                <div class="job-info-accordion gc_hide generalContractor"
                    style="display: {{ $project->originalCustomer->name == 'Original Contractor' ? 'none' : 'block' }}"
                    data-target="general_contractor">
                    General Contractor<i class="fa fa-caret-down job-info-down" aria-hidden="true"></i>
                </div>
                <div class="job-info-panel" id="general_contractor">
                    <div class="table-responsive">
                        <div class="row">

                            <div class="col-md-3" style="display:none;">
                                <input type="radio" name="is_gc" class="is_gc" value="0" id="is_gc"
                                    {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '0' ? 'checked' : '' }}
                                    {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? '' : '' }}>
                                Yes
                                <input type="radio" name="is_gc" class="is_gc" id="not_gc" value="1"
                                    {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '1' ? 'checked' : (!isset($jobInfoSheet) ? 'checked' : '') }}
                                    {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? '' : '' }}>
                                No
                            </div>
                        </div>
                        <div class="row">
                            {{-- <small style="font-size: 8px;">( <em>*To add edit General Contractor please click on Add/Edit contact button</em> )</small> --}}
                            <div class="col-md-6 col-sm-12 formTable">
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_company">Company</label>
                                    <input type="text" name="gc_company" value="{{@$jobInfoSheet->gc_company}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_address">Address</label>
                                    <input type="text" name="gc_address" value="{{@$jobInfoSheet->gc_address}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_city">City</label>
                                    <input type="text" name="gc_city" value="{{@$jobInfoSheet->gc_city}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_state">State</label>
                                    <input type="text" value="{{@$jobInfoSheet->gc_state}}" readonly>

                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_zip">Zip</label>
                                    <input type="text" name="gc_zip" value="{{@$jobInfoSheet->gc_zip}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_phone">Phone Number</label>
                                    <input type="text" name="gc_phone" value="{{@$jobInfoSheet->gc_phone}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_fax">Fax Number</label>
                                    <input type="text" name="gc_fax" value="{{@$jobInfoSheet->gc_fax}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_web">Website</label>
                                    <input type="text" name="gc_web" value="{{@$jobInfoSheet->gc_web}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 formTable">
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_first_name">First Name</label>
                                    <input type="text" name="gc_first_name" value="{{@$jobInfoSheet->gc_first_name}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_last_name">Last Name</label>
                                    <input type="text" name="gc_last_name" value="{{@$jobInfoSheet->gc_last_name}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_title">Title</label>
                                    <input type="text" name="gc_title" value="{{@$jobInfoSheet->gc_title}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_direct_phone">Phone Number</label>
                                    <input type="text" name="gc_direct_phone" value="{{@$jobInfoSheet->gc_direct_phone}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_cell">Cell Phone</label>
                                    <input type="text" name="gc_cell" value="{{@$jobInfoSheet->gc_cell}}" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="gc_email">Email</label>
                                    <input type="text" name="gc_email" value="{{@$jobInfoSheet->gc_email}}" readonly>
                                </div>
                                <div class="row"
                                    style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                    {{-- <input type="button" value="Add/Edit Contact"
                                           class="btn btn-primary editContact project-create-continue"
                                           data-contact="industry"
                                           data-id="0"
                                           data-type="edit"
                                           data-contactType="General Contractor"
                                           data-customer_id="0"
                                           data-company_id="0"
                                           data-filter = "GC"
                                    > --}}
                                    <input type="button" value="Edit Contact"
                                        class="btn btn-primary editContact project-create-continue" data-contact="industry"
                                        data-type="add" data-contacttype="General Contractor" data-filter="GC">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Ends General Contractor Information -->
            @endif
            @php $owner = 1; @endphp
            @if (count($projectContacts) > 0)
                @foreach ($projectContacts as $key => $contact)
                    @if ($contact['type'] == 'Owner')
                        @php $owner++; @endphp
                    @endif
                @endforeach
            @endif
            @if ($owner == 1)
                <div class="job-info-accordion gc_hide generalContractor"
                    style="display: {{ $project->originalCustomer->name == 'Original Contractor' ? 'none' : 'block' }}"
                    data-target="owner">
                    Owner<i class="fa fa-caret-down job-info-down" aria-hidden="true"></i>
                </div>
                <div class="job-info-panel" id="owner">
                    <div class="table-responsive">
                        <div class="row">
                            {{-- <small style="font-size: 8px;">( <em>*To add edit Owner please click on Add/Edit contact button</em> )</small> --}}
                            <div class="col-md-6 col-sm-12 formTable">
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_company">Company</label>
                                    <input type="text" name="owner_company" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_address">Address</label>
                                    <input type="text" name="owner_address" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_city">City</label>
                                    <input type="text" name="owner_city" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_state">State</label>
                                    <input type="text" value="" readonly>

                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_zip">Zip</label>
                                    <input type="text" name="owner_zip" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_phone">Phone Number</label>
                                    <input type="text" name="owner_phone" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_fax">Fax Number</label>
                                    <input type="text" name="owner_fax" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_web">Website</label>
                                    <input type="text" name="owner_web" value="" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 formTable">
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_first_name">First Name</label>
                                    <input type="text" name="owner_first_name" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_last_name">Last Name</label>
                                    <input type="text" name="owner_last_name" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_title">Title</label>
                                    <input type="text" name="owner_title" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_direct_phone">Phone Number</label>
                                    <input type="text" name="owner_direct_phone" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_cell">Cell Phone</label>
                                    <input type="text" name="owner_cell" value="" readonly>
                                </div>
                                <div class="job-info-panel-input-wrapper">
                                    <label for="owner_email">Email</label>
                                    <input type="text" name="owner_email" value="" readonly>
                                </div>
                                <div class="row"
                                    style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                    {{-- <input type="button" value="Add/Edit Contact"
                                           class="btn btn-primary editContact project-create-continue"
                                           data-contact="industry"
                                           data-id="0"
                                           data-type="edit"
                                           data-contactType="Owner"
                                           data-customer_id="0"
                                           data-company_id="0"
                                    > --}}
                                    <input type="button" value="Edit Contact"
                                        class="btn btn-primary editContact project-create-continue" data-contact="industry"
                                        data-type="add" data-contacttype="Owner">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Owner Information Section -->
            @endif
            @if (count($projectContacts) > 0)
                @foreach ($projectContacts as $key => $contact)
                    <div class="job-info-accordion {{ $contact['type'] == 'General Contractor' ? 'generalContractor' : '' }}"
                        data-target="{{ $contact['company_id'] }}">
                        {{ $contact['type'] }}<i class="fa fa-caret-down job-info-down" aria-hidden="true"></i>
                    </div>
                    <div class="job-info-panel" id="{{ $contact['company_id'] }}">
                        <div class="table-responsive">
                            <div class="row">
                                <input type="hidden" name="industry_type[{{ $key }}]"
                                    value="{{ $contact['type'] }}">
                                <input type="hidden" name="company_id[{{ $key }}]"
                                    value="{{ $contact['company_id'] }}">
                                <div class="col-md-6 col-sm-12 formTable">
                                    <div class="job-info-panel-input-wrapper">
                                        <label>Company</label>
                                        <input type="text" name="industry_contact[{{ $key }}]" readonly
                                            value="{{ $contact['company'] ? $contact['company']->company : '' }}">
                                    </div>
                                    <div class="job-info-panel-input-wrapper">
                                        <label>Address</label>
                                        <input type="text" name="industry_contact[{{ $key }}]" readonly
                                            value="{{ $contact['company'] ? $contact['customers'][0]->address : '' }}">
                                    </div>
                                    <div class="job-info-panel-input-wrapper">
                                        <label>City</label>
                                        <input type="text" name="industry_city[{{ $key }}]" readonly
                                            value="{{ $contact['company'] ? $contact['customers'][0]->city : '' }}">
                                    </div>
                                    <div class="job-info-panel-input-wrapper job-info-panel-input-wrapper--select">
                                        <label>State</label>
                                        @if(isset($contact['company']))
                                            @if (!empty($contact['company']->state_id))
                                                @foreach ($states as $state)
                                                    @if ($state->id === $contact['company']->state_id)
                                                        <input type="text" value="{{ $state->name }}"  readonly>
                                                    @endif
                                                @endforeach
                                            @else
                                                <input type="text" value="" >
                                            @endif
                                        @else
                                            <input type="text" value="" >
                                        @endif

                                    </div>
                                    <div class="job-info-panel-input-wrapper">
                                        <label>Zip</label>
                                        <input type="text" name="industry_zip[{{ $key }}]" readonly
                                            value="{{ $contact['company'] ? $contact['customers'][0]->zip : '' }}">
                                    </div>
                                    <div class="job-info-panel-input-wrapper">
                                        <label>Telephone</label>
                                        <input type="text" name="industry_phone[{{ $key }}]" readonly
                                            value="{{ $contact['company'] ? $contact['customers'][0]->phone : '' }}">
                                    </div>
                                    <div class="job-info-panel-input-wrapper">
                                        <label>Fax</label>
                                        <input type="text" name="industry_fax[{{ $key }}]" readonly
                                            value="{{ $contact['company'] ? $contact['customers'][0]->fax : '' }}">
                                    </div>
                                    <div class="job-info-panel-input-wrapper">
                                        <label>Website</label>
                                        <input type="text" name="industry_website[{{ $key }}]" readonly
                                            value="{{ $contact['company'] ? $contact['company']->website : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 formTable">
                                    @if (count($contact['customer']) > 0)
                                        @foreach ($contact['customer'] as $customerKey => $contactInformation)
                                            @if ($customerKey == 0)
                                                <div class="job-info-panel-input-wrapper">
                                                    <label>First Name</label>
                                                    <input type="text" readonly
                                                        name="industryFirstName[{{ $key }}][]"
                                                        value="{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}">
                                                </div>
                                                <div class="job-info-panel-input-wrapper">
                                                    <label>Last Name</label>
                                                    <input type="text" readonly
                                                        name="industryLastName[{{ $key }}][]"
                                                        value="{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}">
                                                </div>
                                                <div class="job-info-panel-input-wrapper">
                                                    <label>Title</label>
                                                    <input type="text" readonly
                                                        name="industryTitle[{{ $key }}][]"
                                                        value="{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}">
                                                </div>

                                                <div class="job-info-panel-input-wrapper">
                                                    <label>Phone Number</label>
                                                    <a href="tel:{{ $contactInformation->phone }}">
                                                        {{ $contactInformation->phone }}
                                                    </a>
                                                </div>
                                                <div class="job-info-panel-input-wrapper">
                                                    <label>Cell Phone</label>
                                                    <a href="tel:{{ $contactInformation->cell }}">
                                                        {{ $contactInformation->cell }}
                                                    </a>
                                                </div>
                                                <div class="job-info-panel-input-wrapper">
                                                    <label>Email</label>
                                                    <a href="mailto: {{ $contactInformation->email }}">
                                                        {{ $contactInformation->email }}
                                                    </a>
                                                </div>
                                                @if (count($contact['customer']) > 1)
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
                                                <div class="customer{{ $key }}" style="display: none;">
                                                    <div class="job-info-panel-input-wrapper">
                                                        <label>First Name</label>
                                                        <input type="text" readonly
                                                            name="industryFirstName[{{ $key }}][]"
                                                            value="{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}">
                                                    </div>
                                                    <div class="job-info-panel-input-wrapper">
                                                        <label>Last Name</label>
                                                        <input type="text" readonly
                                                            name="industryLastName[{{ $key }}][]"
                                                            value="{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}">
                                                    </div>
                                                    <div class="job-info-panel-input-wrapper">
                                                        <label>Title</label>
                                                        <input type="text" readonly
                                                            name="industryTitle[{{ $key }}][]"
                                                            value="{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}">
                                                    </div>
                                                    <div class="job-info-panel-input-wrapper">
                                                        <label>Phone Number</label>
                                                        <input type="text" readonly
                                                            name="industryDirectPhone[{{ $key }}][]"
                                                            value="{{ $contactInformation->phone }}">
                                                    </div>
                                                    <div class="job-info-panel-input-wrapper">
                                                        <label>Cell Phone</label>
                                                        <input type="text"  readonly
                                                            name="industryCellPhone[{{ $key }}][]"
                                                            value="{{ $contactInformation->cell }}">
                                                    </div>
                                                    <div class="job-info-panel-input-wrapper">
                                                        <label>Email</label>
                                                        <a href="mailto: {{ $contactInformation->email }}">
                                                            {{ $contactInformation->email }}
                                                        </a>
                                                    </div>
                                                    <div class="row"
                                                        style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                                        <div class="col-md-12">
                                                            <input type="button" value="Edit Contact"
                                                                class="btn btn-primary editContact project-create-continue"
                                                                data-contact="industry"
                                                                data-id="{{ $contact['company']->id }}"
                                                                data-map_id="{{ !is_null($contactInformation->mapContactCompany) ? $contactInformation->mapContactCompany->id : 0 }}"
                                                                data-type="edit"
                                                                data-contactType="{{ $contactInformation->customer_type }}"
                                                                data-customer_id="{{ $contactInformation->id }}"
                                                                data-company_id="{{ $contact['company_id'] }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    <div class="row"
                                        style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                        {{-- <div class="col-md-12">
                                            <input type="button" value="Add/Edit Contact"
                                                   class="btn btn-primary editContact project-create-continue"
                                                   data-contact="industry"
                                                   data-id="{{ $contact['company']->company_id }}"
                                                   data-type="edit"
                                                   data-contactType="{{ $contact['type'] }}"
                                                   data-customer_id="{{ $contact['customer_id'] }}"
                                                   data-company_id="{{ $contact['company_id'] }}"
                                            >
                                        </div> --}}
                                    </div>
                                    @if (count($contact['customer']) > 0)
                                        @foreach ($contact['customer'] as $customerKey => $contactInformation)
                                            @if ($customerKey == 0)
                                                <div class="row"
                                                    style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                                    @if ($contact['type'] === 'General Contractor')
                                                        <div class="col-md-12">
                                                            <input type="button" value="Edit Contact"
                                                                class="btn btn-primary editContact project-create-continue"
                                                                data-contact="industry"
                                                                data-id="{{ $contact['company']->id }}"
                                                                data-map_id="{{ !is_null($contactInformation->mapContactCompany) ? $contactInformation->mapContactCompany->id : 0 }}"
                                                                data-type="edit"
                                                                data-contactType="{{ $contactInformation->customer_type }}"
                                                                data-customer_id="{{ $contactInformation->id }}"
                                                                data-company_id="{{ $contact['company_id'] }}"
                                                                data-filter="GC">
                                                        </div>

                                                    @else
                                                        <div class="col-md-12">
                                                            <input type="button" value="Edit Contact"
                                                                class="btn btn-primary editContact project-create-continue"
                                                                data-contact="industry"
                                                                data-id="{{ $contact['company']->id }}"
                                                                data-map_id="{{ !is_null($contactInformation->mapContactCompany) ? $contactInformation->mapContactCompany->id : 0 }}"
                                                                data-type="edit"
                                                                data-contactType="{{ $contactInformation->customer_type }}"
                                                                data-customer_id="{{ $contactInformation->id }}"
                                                                data-company_id="{{ $contact['company_id'] }}">
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> <!-- End Industry Contact Information -->
                @endforeach
            @endif
            <div class="block-two terms">
                <div class="table-responsive">
                    <div class="black-box-main">
                        <a href="{{ route('member.create.projectcontacts', [$project_id]) . '?edit=true' }}"
                            class="btn btn-primary btn-lg editContact mobile--button mobile--button--wide project-create-continue project-create-continue--left"
                            style="height: 45px;">
                            Add Project Contact
                        </a>

                        <div class="row">
                            <div class="col-md-12 field mobile--agree">
                                <label><input type="checkbox" name="Agree" id="agree"
                                        {{ isset($jobInfoSheet) && $jobInfoSheet != '' ? 'checked' : '' }}
                                        {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? '' : '' }}>
                                    &nbsp;By submitting this
                                    form, you agree to the Liability Limitation terms stated herein.</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="col-md-4">
                                    <strong>Customer Signature:</strong>
                                </div>
                                <div class="col-md-8">
                                    <input name="Signature" type="text"
                                        style="border: 0.5px solid #726d6d; background:#fff;"
                                        value="{{ isset($jobInfoSheet) && $jobInfoSheet != '' ? $jobInfoSheet->signature : '' }}"
                                        {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? '' : '' }} />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-md-4">
                                    <strong>Date:</strong>
                                </div>
                                <div class="col-md-8">
                                    <input name="SignatureDate" type="text" class="date " required="true"
                                        value="{{ isset($jobInfoSheet) && $jobInfoSheet != '' && !is_null($jobInfoSheet->signature_date) ? date('m/d/Y', strtotime($jobInfoSheet->signature_date)) : ''; }}"
                                        style="border: 0.5px solid #726d6d; background:#fff;"
                                        {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'disabled' : '' }} />
                                </div>
                            </div>
                        </div>

                        <br />
                        <br />

                        <div class="row">
                            <div class="col-md-12 footerTxt"
                                style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                <div class="col-md-4">
                                    <label>Attach a Document (Choose A FILE)</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" id="uploadFile">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!--Buttons-->
                            <div class="block-two"
                                style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                <div class="black-box-main">
                                    @if (!isset($_GET['edit']))
                                        @if (!isset($_GET['saved']))
                                            <button type="submit"
                                                class="btn btn-success btn-lg save mobile--button mobile--button--wide project-save-quit project-save-quit--terms"
                                                name="send" value="send">Send and Save</button>
                                            <button type="submit"
                                                class="btn btn-info btn-lg save mobile--button mobile--button--wide project-create-continue project-create-continue--terms"
                                                name="send" value="save">Save</button>

                                        @else
                                            <a href="{{ route('project.task.view', ['project_id' => $project->id, 'view' => 'detailed', 'create' => 'true', 'hide' => 'true']) }}"
                                                class="btn btn-success btn-lg mobile--button mobile--button--wide project-save-quit project-save-quit--terms">View
                                                Tasks</a>
                                            <button type="submit"
                                                class="btn btn-success btn-lg save mobile--button mobile--button--wide project-save-quit project-save-quit--terms"
                                                name="send" value="send">Send and Save</button>
                                            <button type="submit"
                                                class="btn btn-info btn-lg save mobile--button mobile--button--wide project-create-continue project-create-continue--terms"
                                                name="send" value="save">Save</button>


                                        @endif
                                    @else
                                        @if (!isset($_GET['saved']) || (isset($_GET['view']) && $_GET['view'] === 'express'))
                                            <button type="submit"
                                                class="btn btn-success btn-lg save mobile--button mobile--button--wide project-save-quit project-save-quit--terms"
                                                name="send" value="send">Send and Save</button>
                                            <button type="submit"
                                                class="btn btn-info btn-lg save mobile--button mobile--button--wide project-create-continue project-create-continue--terms"
                                                name="send" value="save">Save</button>

                                        @else
                                            <a href="{{ route('project.task.view', ['project_id' => $project->id, 'view' => 'detailed', 'edit' => 'true', 'hide' => 'true']) }}"
                                                class="btn btn-success btn-lg mobile--button mobile--button--wide project-save-quit project-save-quit--terms">View
                                                Tasks</a>
                                            <button type="submit"
                                                class="btn btn-success btn-lg save mobile--button mobile--button--wide project-save-quit project-save-quit--terms"
                                                name="send" value="send">Send and Save</button>
                                            <button type="submit"
                                                class="btn btn-info btn-lg save mobile--button mobile--button--wide project-create-continue project-create-continue--terms"
                                                name="send" value="save">Save</button>


                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
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
                                <p><strong>Liability Limitations:</strong> National Lien and Bond Claim Systems, a division
                                    of
                                    Network*50, Inc (NLB) does not guarantee or in any way represent or warrant the
                                    information transmitted or received by customer or third parties. Customer acknowledges
                                    and agrees that the service provided by NLB consists solely of providing access to a
                                    filing
                                    network which may in appropriate cases involve attorneys. NLB is not in any way
                                    responsible or liable for errors, omissions, inadequacy or interruptions. In the event
                                    any
                                    errors is attributable to NLB or to the equipment, customer should be entitled only to a
                                    refund of the cost for preparation of any notices. The refund shall be exclusively in
                                    lieu of
                                    any other damages or remedies against NLB.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 footerTxt">
                                <div class="row">
                                    @if (isset($jobInfoSheet) && count($jobInfoSheet->jobFiles) > 0)
                                        @foreach ($jobInfoSheet->jobFiles as $file)
                                            <div class="col-md-6" id="id{{ $file->id }}">
                                                <div class="fileRow">
                                                    <div class="col-xs-8">
                                                        <div class="fileName">
                                                            <a href="{{ env('ASSET_URL') }}/upload/{{ $file->file }}" target="_blank"><i class="fa fa-file mr-2"></i> {{ $file->file }}</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4 fileBtn"
                                                        style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                                        {{-- <button type="button" class="btn btn-success removeBtn"><i class="fa fa-eye"></i></button> --}}
                                                        <button type="button" class="btn btn-danger removeBtn"
                                                            data-id="{{ $file->id }}"><i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="newfiles[]" value="{{ $file->file }}">

                                        @endforeach
                                    @endif
                                </div>
                                <div class="uploadedFiles"></div>
                            </div>
                        </div>
                        <div class="row footerTxt">
                            <div style="text-align:center; "><img src="{{ env('ASSET_URL') }}/images/nlb_black.png"
                                    alt="NLB" align="middle"></div>
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
	 $(document).ready(function(){
      function autoSave()
      {

	   var formData = new FormData($('.create-project-form')[0]);
        let url = $('.create-project-form')[0].getAttribute('action');
		// console.log($('#jobform').serialize());
		// console.log(url);
        $.ajax({
            type: 'POST',
            url,
            data:$('#jobform').serialize(),
			      dataType: 'json',
            success: function (data) {
                //alert(data);
            },
        });
         /*   var title = $('#title').val();
           var description = $('#mytextarea').val();

           if(title != '' && description != '')
           {
                $.ajax({
                     url:"{{url('articles')}}",
                     method:"POST",
                     data:{title:title, description:description},
                     dataType:"text",
                     success:function(data)
                     {

                          $('#autoSave').text("Post save as draft");
                          setInterval(function(){
                               $('#autoSave').text('');
                          }, 3000);
                     },

                });
           }    */
      }
      // setInterval(function(){
      //     autoSave();
      //   }, 20000);
 });
        $(document).ready(function() {
            let customer_view = "{{ $customer_view }}"
            let $company_view = "{{ $company_view }}"
            console.log('customer view', customer_view)
            console.log('company view', $company_view)
            if (customer_view == 'true') {

                $('html, body').animate({
                    scrollTop: $('#customer_information').offset().top
                }, 'slow');
            } else if ($company_view == 'true') {

                $('html, body').animate({
                    scrollTop: $('#company_information').offset().top
                }, 'slow');
            } else {
                $('html, body').animate({
                    scrollTop: $('#gc').offset().top + 20
                }, 'slow');
            }
        });

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
        let submitDate = "{{ route('project.dates.submit') }}"
        let updateDate = "{{ route('project.dates.update') }}"
        var getContactDetailsURL = "{{ route('autocomplete.contact.details') }}";

        $('.btn-view-jobsheet').on('click', function(event) {
            //event.stopPropagation();
            event.preventDefault();
            // 16 aug
            window.location.href = '/member/project/summary/view/' + project_id + '?edit=true';

        });
    </script>

    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/job_info.js"></script>

    <script src="{{ env('ASSET_URL') }}/js/job_info_dates.js"></script>
    @if (isset($_GET['create']))
        <script src="{{ env('ASSET_URL') }}/js/createProject.js"></script>
    @endif
@endsection
