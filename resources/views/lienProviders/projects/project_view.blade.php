@extends('lienProviders.layout.main')
@section('title','Project Details')
@section('style')
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
    <style>
        .select button {
            width: 100%;
            text-align: left;
        }

        .select .caret {
            position: absolute;
            right: 10px;
            margin-top: 10px;
        }

        .select:last-child > .btn {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .selected {
            padding-right: 10px;
        }

        .option {
            width: 100%;
        }

        .input-error {
            border: 2px solid red;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-sm-12 col-md-offset-1">
                <div class="center-part">
                    <h1>Project Details</h1>
                    <div class="tab-area">
                        <div class="tab-content">
                            <div class="border-table">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="main-tab-sec">
                                            <div class="">
                                                <div class="col-md-4 col-md-offset-4">
                                                    <ul class="nav nav-tabs" id="myTab">
                                                        <li ><a  data-toggle="tab" href="#express">Express</a>
                                                        </li>
                                                        <li  class="active"><a class="active" id="detailed-tab" data-toggle="tab" href="#detailed">Detailed</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-content tab-final">
                                                <div id="express" class="tab-pane">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12">
                                                            <div class="main-tab-sec">
                                                                <div class="main-tab-menu">
                                                                    <ul class="nav nav-tabs">
                                                                        <!-- <li class="active">
                                                                            <a data-toggle="tab" href="#project_details_express">Project</a>
                                                                        </li> -->
                                                                        <li class="active">
                                                                            <a data-toggle="tab" href="#lienLawChart">Dashboard</a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-toggle="tab"
                                                                               href="#documentsLienLaw">Documents</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="tab-content tab-final">
                                                                    {{--<div id="project_details_express" class="tab-pane fade in active">
                                                                        <div class="project-heading">
                                                                            <a href="javascript:void(0)">Project Name</a>
                                                                            <strong>{{ $project->project_name }}</strong>
                                                                        </div>
                                                                        <div class="row tab-final">

                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Project Location by State :
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @foreach($states as $state)
                                                                                                        @if($state->id == $project->state_id)
                                                                                                            <strong> {{$state->name}}</strong>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Your Role :
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @foreach($roles as $role)
                                                                                                        @if($role->id == $project->role_id)
                                                                                                            <strong>{{$role->project_roles}}</strong>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row tab-final">
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Project Type :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @foreach($types as $type)
                                                                                                        @if($type->id == $project->project_type_id)
                                                                                                            <strong>{{$type->project_type}}</strong>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Who Hired You :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @if(isset($project) && $project != '')
                                                                                                        <strong>{{ $project->tier->customer->name }}</strong>
                                                                                                    @else
                                                                                                        Select a Select your customer
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row tab-final">
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Customer Contract :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ !is_null($project->customer_contract) && !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?  $project->customer_contract->company->company : '' }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Industry Contract :
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @if($project->industryContacts != '')
                                                                                                        @foreach($project->industryContacts as $contact)
                                                                                                            {{ $contact->contacts ? $contact->contacts->company.', ' : '' }}
                                                                                                        @endforeach
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row tab-final">
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Address :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ $project->address }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        City :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ $project->city }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row tab-final">
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Country :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ $project->country }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Your Company's Work :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ $project->company_work }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>--}}
                                                                    <div id="lienLawChart"
                                                                         class="tab-pane fade in active">
                                                                        <div class="box">
                                                                            <div class="head-top">
                                                                                {{ $project->state->name }}
                                                                                <div class="pull-right">
                                                                                    <button class="btn btn-success expandall-icon">
                                                                                        <i class="fa fa-plus fa-2x"></i>
                                                                                    </button>
                                                                                    <button class="btn btn-warning collapseall-icon">
                                                                                        <i class="fa fa-minus fa-2x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="head-top2">{{ $project->project_type->project_type }}</div>
                                                                            <div class="panel-group" id="accordion">
                                                                                @if(count($remedyNames) > 0)
                                                                                    @foreach($remedyNames as $remedyKey => $name)
                                                                                        <div class="panel panel-default">
                                                                                            <div class="panel-heading">
                                                                                                <h4 class="panel-title">
                                                                                                    <a data-toggle="collapse"
                                                                                                       data-parent="#accordion"
                                                                                                       href="#{{ $remedyKey }}">{{ $name }}</a>
                                                                                                </h4>
                                                                                            </div>
                                                                                            @if(count($liens) > 0)
                                                                                                @foreach($liens as $key => $lien)
                                                                                                    @if( $name == $lien['remedy'] )
                                                                                                        <div id="{{ $key }}"
                                                                                                             class="panel-collapse collapse">
                                                                                                            <div class="panel-body">
                                                                                                                <div class="panel-main">
                                                                                                                    <p style="font-size: medium;">
                                                                                                                        <strong>Description:</strong> {{ $lien->description }}
                                                                                                                    </p>
                                                                                                                    <p style="font-size: medium;">
                                                                                                                        <strong>Tiers:</strong> {{ $lien->tier_limit }}
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                                <div class="panel panel-default">
                                                                                    <div class="panel-heading">
                                                                                        <h4 class="panel-title">
                                                                                            <a data-toggle="collapse"
                                                                                               data-parent="#accordion"
                                                                                               href="#jobDescription">Job
                                                                                                Description (
                                                                                                <small><strong>{{ $project->project_name }}</strong></small>
                                                                                                )</a>
                                                                                        </h4>
                                                                                    </div>
                                                                                    <div id="jobDescription"
                                                                                         class="panel-collapse collapse">
                                                                                        <div class="panel-body">
                                                                                            <div class="panel-main">
                                                                                                <div class="col-md-12">
                                                                                                    <div align="left">
                                                                                                        <p>Job Name :
                                                                                                            <strong>{{ $project->project_name }}</strong>
                                                                                                        </p>
                                                                                                        <p>Job Address :
                                                                                                            <strong>{{ $project->address != '' ? $project->address : 'N/A' }}</strong>
                                                                                                        </p>
                                                                                                        <p>Job City :
                                                                                                            <strong>{{ $project->city != '' ? $project->city : 'N/A' }}</strong>
                                                                                                        </p>
                                                                                                        <p>Job State :
                                                                                                            <strong>{{ $project->state->name }}</strong>
                                                                                                        </p>
                                                                                                        <p>Job Zip :
                                                                                                            <strong>{{ $project->zip != '' ? $project->zip : 'N/A' }}</strong>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @if(!is_null($project->customer_contract))
                                                                                    <div class="panel panel-default">
                                                                                        <div class="panel-heading">
                                                                                            <h4 class="panel-title">
                                                                                                <a data-toggle="collapse"
                                                                                                   data-parent="#accordion"
                                                                                                   href="#customer">Customer
                                                                                                    -
                                                                                                    <small><strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?$project->customer_contract->company->company : 'N/A' }}</strong></small>
                                                                                                </a>
                                                                                            </h4>
                                                                                        </div>
                                                                                        <div id="customer"
                                                                                             class="panel-collapse collapse">
                                                                                            <div class="panel-body">
                                                                                                <div class="panel-main">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="col-md-5">
                                                                                                            <div align="left">
                                                                                                                <p>
                                                                                                                    Company
                                                                                                                    Name:
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?$project->customer_contract->company->company : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                                <p>
                                                                                                                    Address
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->address) ?$project->customer_contract->company->company : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                                <p>City
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->city) ?$project->customer_contract->company->city : 'N/A' }}</strong>,
                                                                                                                    State
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->state) ?$project->customer_contract->company->state->name : 'N/A'  }}</strong>,
                                                                                                                    Zip
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->zip) ?$project->customer_contract->company->zip : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                                <p>
                                                                                                                    Telephone
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->phone) ?$project->customer_contract->company->phone : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                                <p>Fax :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->fax) ?$project->customer_contract->company->fax : 'N/A'  }}</strong>
                                                                                                                </p>
                                                                                                                <p>Web :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->website) ?$project->customer_contract->company->website : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-2">
                                                                                                            &nbsp;
                                                                                                        </div>
                                                                                                        <div class="col-md-5">
                                                                                                            <div align="right">
                                                                                                                @if(isset($project->customer_contract->contactInformation))
                                                                                                                    @if(count($project->customer_contract->contactInformation) > 0)
                                                                                                                        @foreach($project->customer_contract->contactInformation as $customerKey => $contactInformation)
                                                                                                                            @if($customerKey == 0)
                                                                                                                                <p>
                                                                                                                                    First
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A'}}</strong>,
                                                                                                                                    Last
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}</strong>,
                                                                                                                                    Title
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->title != ''? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}</strong>
                                                                                                                                </p>
                                                                                                                                <p>
                                                                                                                                    Direct
                                                                                                                                    Phone
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->direct_phone }}</strong>
                                                                                                                                </p>
                                                                                                                                <p>
                                                                                                                                    Cell
                                                                                                                                    Phone
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->cell }}</strong>
                                                                                                                                </p>
                                                                                                                                <p>
                                                                                                                                    Email
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->email }}</strong>
                                                                                                                                </p>
                                                                                                                                @if(count($project->customer_contract->contactInformation) > 1)
                                                                                                                                    <p>
                                                                                                                                        <a href="javascript:void(0)"
                                                                                                                                           class="show_more"
                                                                                                                                           id="customerMore{{ $project->customer_contract->id }}"
                                                                                                                                           data-id="{{ $project->customer_contract->id }}">Show
                                                                                                                                            More</a>
                                                                                                                                        <a href="javascript:void(0)"
                                                                                                                                           class="show_less"
                                                                                                                                           id="customerLess{{ $project->customer_contract->id }}"
                                                                                                                                           data-id="{{ $project->customer_contract->id }}">Show
                                                                                                                                            Less</a>
                                                                                                                                    </p>
                                                                                                                                @endif
                                                                                                                            @else
                                                                                                                                <div class="customer{{ $project->customer_contract->id }}"
                                                                                                                                     style="display: none;">
                                                                                                                                    <p>
                                                                                                                                        First
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A'}}</strong>,
                                                                                                                                        Last
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}</strong>,
                                                                                                                                        Title
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->title != ''? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}</strong>
                                                                                                                                    </p>
                                                                                                                                    <p>
                                                                                                                                        Direct
                                                                                                                                        Phone
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->direct_phone }}</strong>
                                                                                                                                    </p>
                                                                                                                                    <p>
                                                                                                                                        Cell
                                                                                                                                        Phone
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->cell }}</strong>
                                                                                                                                    </p>
                                                                                                                                    <p>
                                                                                                                                        Email
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->email }}</strong>
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            @endif
                                                                                                                        @endforeach
                                                                                                                    @endif
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                {{--@if(count($project->industryContacts) > 0)
                                                                                    @foreach($project->industryContacts as $contact)
                                                                                        @if(!is_null($contact->fetchMap) && !is_null($contact->fetchMap->getContacts))

                                                                                        <div class="panel panel-default">
                                                                                            <div class="panel-heading">
                                                                                                <h4 class="panel-title">
                                                                                                    <a data-toggle="collapse"
                                                                                                       data-parent="#accordion"
                                                                                                       href="#industry{{ $contact->contacts->id }}">{{ $contact->contacts->contact_type }}
                                                                                                        -
                                                                                                        <small><strong>{{ $contact->contacts->company }}</strong></small>
                                                                                                    </a>
                                                                                                </h4>
                                                                                            </div>
                                                                                            <div id="industry{{ $contact->contacts->id }}"
                                                                                                 class="panel-collapse collapse">
                                                                                                <div class="panel-body">
                                                                                                    <div class="panel-main">
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="col-md-5">
                                                                                                                <div align="left">
                                                                                                                    <p>
                                                                                                                        Company
                                                                                                                        Name:
                                                                                                                        <strong>{{ $contact->contacts->company }}</strong>
                                                                                                                    </p>
                                                                                                                    <p>
                                                                                                                        Address
                                                                                                                        :
                                                                                                                        <strong>{{ $contact->contacts->address != '' ? $contact->contacts->address : 'N/A' }}</strong>
                                                                                                                    </p>
                                                                                                                    <p>
                                                                                                                        City
                                                                                                                        :
                                                                                                                        <strong>{{ $contact->contacts->city != '' ? $contact->contacts->city : 'N/A' }}</strong>,
                                                                                                                        State
                                                                                                                        :
                                                                                                                        <strong>{{ $contact->contacts->state->name }}</strong>,
                                                                                                                        Zip
                                                                                                                        :
                                                                                                                        <strong>{{ $contact->contacts->zip != '' ? $contact->contacts->zip : 'N/A' }}</strong>
                                                                                                                    </p>
                                                                                                                    <p>
                                                                                                                        Telephone
                                                                                                                        :
                                                                                                                        <strong>{{ $contact->contacts->phone != '' ? $contact->contacts->phone : 'N/A' }}</strong>
                                                                                                                    </p>
                                                                                                                    <p>
                                                                                                                        Fax
                                                                                                                        :
                                                                                                                        <strong>{{ $contact->contacts->fax != '' ? $contact->contacts->fax : 'N/A' }}</strong>
                                                                                                                    </p>
                                                                                                                    <p>
                                                                                                                        Web
                                                                                                                        :
                                                                                                                        <strong>{{ $contact->contacts->website != '' ? $contact->contacts->website : 'N/A' }}</strong>
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-md-2">
                                                                                                                &nbsp;
                                                                                                            </div>
                                                                                                            <div class="col-md-5">
                                                                                                                <div align="right">
                                                                                                                    @if(count($contact->contacts->contactInformation) > 0)
                                                                                                                        @foreach($contact->contacts->contactInformation as $industryKey => $contactInformationIns)
                                                                                                                            @if($industryKey == 0)
                                                                                                                                <p>
                                                                                                                                    First
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A'}}</strong>,
                                                                                                                                    Last
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>,
                                                                                                                                    Title
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformationIns->title != ''? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong>
                                                                                                                                </p>
                                                                                                                                <p>
                                                                                                                                    Direct
                                                                                                                                    Phone
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformationIns->direct_phone }}</strong>
                                                                                                                                </p>
                                                                                                                                <p>
                                                                                                                                    Cell
                                                                                                                                    Phone
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformationIns->cell }}</strong>
                                                                                                                                </p>
                                                                                                                                <p>
                                                                                                                                    Email
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformationIns->email }}</strong>
                                                                                                                                </p>
                                                                                                                                @if(count($contact->contacts->contactInformation) > 1)
                                                                                                                                    <p>
                                                                                                                                        <a href="javascript:void(0)"
                                                                                                                                           class="show_more"
                                                                                                                                           id="customerMore{{ $contact->contacts->id }}"
                                                                                                                                           data-id="{{ $contact->contacts->id }}">Show
                                                                                                                                            More</a>
                                                                                                                                        <a href="javascript:void(0)"
                                                                                                                                           class="show_less"
                                                                                                                                           id="customerLess{{ $contact->contacts->id }}"
                                                                                                                                           data-id="{{ $contact->contacts->id }}">Show
                                                                                                                                            Less</a>
                                                                                                                                    </p>
                                                                                                                                @endif
                                                                                                                            @else
                                                                                                                                <div class="customer{{ $contact->contacts->id }}"
                                                                                                                                     style="display: none;">
                                                                                                                                    <p>
                                                                                                                                        First
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A'}}</strong>,
                                                                                                                                        Last
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>,
                                                                                                                                        Title
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformationIns->title != ''? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong>
                                                                                                                                    </p>
                                                                                                                                    <p>
                                                                                                                                        Direct
                                                                                                                                        Phone
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformationIns->direct_phone }}</strong>
                                                                                                                                    </p>
                                                                                                                                    <p>
                                                                                                                                        Cell
                                                                                                                                        Phone
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformationIns->cell }}</strong>
                                                                                                                                    </p>
                                                                                                                                    <p>
                                                                                                                                        Email
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformationIns->email }}</strong>
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            @endif
                                                                                                                        @endforeach
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif--}}
                                                                                @if(count($projectContacts) > 0)
                                                                                    @foreach($projectContacts as $key => $contact)
                                                                                        @php
                                                                                            $customerDetails = \App\Models\CompanyContact::find($contact['customer_id']);
                                                                                            $mapDetails = $customerDetails->mapContactCompany;
                                                                                        @endphp
                                                                                        <div class="panel panel-default">
                                                                                            <div class="panel-heading">
                                                                                                <h4 class="panel-title">
                                                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#industry{{ $key }}">{{ $contact['type'] }} - <small><strong>{{ $contact['company']->company }}</strong></small></a>
                                                                                                </h4>
                                                                                            </div>
                                                                                            <div id="industry{{ $key }}" class="panel-collapse collapse">
                                                                                                <div class="panel-body">
                                                                                                    <div class="panel-main">
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="col-md-5">
                                                                                                                <div align="left">
                                                                                                                    <p>Company Name: <strong>{{ $contact['company']->company }}</strong></p>
                                                                                                                    <p>Address : <strong>{{ $contact['company']->address != '' ? $contact['company']->address : 'N/A' }}</strong></p>
                                                                                                                    <p>City : <strong>{{ $contact['company']->city != '' ? $contact['company']->city : 'N/A' }}</strong>, State : <strong>{{ $contact['company']->state->name }}</strong>, Zip : <strong>{{ $contact['company']->zip != '' ? $contact['company']->zip : 'N/A' }}</strong></p>
                                                                                                                    <p>Telephone : <strong>{{ $contact['company']->phone != '' ? $contact['company']->phone : 'N/A' }}</strong></p>
                                                                                                                    <p>Fax : <strong>{{ $contact['company']->fax != '' ? $contact['company']->fax : 'N/A' }}</strong></p>
                                                                                                                    <p>Web : <strong>{{ $contact['company']->website != '' ? $contact['company']->website : 'N/A' }}</strong></p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-md-2">
                                                                                                                &nbsp;
                                                                                                            </div>
                                                                                                            <div class="col-md-5">
                                                                                                                <div align="right">
                                                                                                                    {{-- <p>
                                                                                                                         <input type="button" value="Add/Edit Contact"
                                                                                                                                class="btn btn-primary editContact"
                                                                                                                                data-contact="industry"
                                                                                                                                data-id="{{ $contact['company']->company_id }}"
                                                                                                                                data-map_id = "{{ !is_null($mapDetails) ? $mapDetails->id : 0 }}"
                                                                                                                                data-type="edit"
                                                                                                                                data-contactType="{{ $contact['type'] }}"
                                                                                                                                data-customer_id="{{ $contact['customer_id'] }}"
                                                                                                                                data-company_id="{{ $contact['company_id'] }}"
                                                                                                                         >
                                                                                                                     </p>--}}
                                                                                                                    @if(count($contact['customers']) > 0)
                                                                                                                        @foreach($contact['customers'] as $industryKey => $contactInformationIns)
                                                                                                                            @if($industryKey == 0)
                                                                                                                                <p>First : <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A'}}</strong>, Last : <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>, Title : <strong>{{ $contactInformationIns->title != ''? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong></p>
                                                                                                                                <p>Direct Phone : <strong>{{ $contactInformationIns->phone }}</strong></p>
                                                                                                                                <p>Cell Phone : <strong>{{ $contactInformationIns->cell }}</strong></p>
                                                                                                                                <p>Email : <strong>{{ $contactInformationIns->email }}</strong></p>
                                                                                                                                @if(count($contact['customers']) > 1)
                                                                                                                                    <p>
                                                                                                                                        <a href="javascript:void(0)" class="show_more" id="customerMore{{ $key}}" data-id="{{ $key }}">Show More</a>
                                                                                                                                        <a href="javascript:void(0)" class="show_less" id="customerLess{{ $key }}" data-id="{{ $key }}">Show Less</a>
                                                                                                                                    </p>
                                                                                                                                @endif
                                                                                                                            @else
                                                                                                                                <div class="customer{{ $key}}" style="display: none;">
                                                                                                                                    <p>First : <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A'}}</strong>, Last : <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>, Title : <strong>{{ $contactInformationIns->title != ''? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong></p>
                                                                                                                                    <p>Direct Phone : <strong>{{ $contactInformationIns->phone }}</strong></p>
                                                                                                                                    <p>Cell Phone : <strong>{{ $contactInformationIns->cell }}</strong></p>
                                                                                                                                    <p>Email : <strong>{{ $contactInformationIns->email }}</strong></p>
                                                                                                                                </div>
                                                                                                                            @endif
                                                                                                                        @endforeach
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                                <div class="head-top">
                                                                                    &nbsp;
                                                                                    <div class="pull-right">
                                                                                        <button class="btn btn-success expandall-icon2"><i class="fa fa-plus fa-2x"></i> </button>
                                                                                        <button class="btn btn-warning collapseall-icon2"><i class="fa fa-minus fa-2x"></i> </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="documentsLienLaw" class="tab-pane fade">
                                                                        <div class="border-table">
                                                                            <!-- <h1 class="text-center">Project Documents</h1>
                                                                            <hr class="divider"> -->
                                                                            <form action="#" method="post"
                                                                                  class="form-horizontal project-form project_documents_lien">
                                                                               {{-- <div class="form-group">
                                                                                    <label class="col-md-2">Create
                                                                                        Documents</label>
                                                                                    <div class="col-md-6">
                                                                                        <select id="DocumentTypeLien"
                                                                                                class="form-control"
                                                                                                name="DocumentType">
                                                                                            <option value="">Select a
                                                                                                Document Type
                                                                                            </option>
                                                                                            <option value="JobInfo">Job
                                                                                                Info Sheet
                                                                                            </option>
                                                                                            <option value="ClaimData">
                                                                                                Claim Form and Project
                                                                                                Data Sheet
                                                                                            </option>
                                                                                            <option value="CreditApplication">
                                                                                                Credit
                                                                                                Application
                                                                                            </option>
                                                                                            <option value="JointPaymentAuthorization">
                                                                                                Joint
                                                                                                Payment
                                                                                                Authorization
                                                                                            </option>
                                                                                            <optgroup
                                                                                                    label="Waiver of Lien Forms">
                                                                                                <option value="UnconditionalWaiverProgress">
                                                                                                    Unconditional Waiver
                                                                                                    and Release Upon
                                                                                                    Progress Payment
                                                                                                </option>
                                                                                                <option value="ConditionalWaiver">
                                                                                                    Conditional Waiver
                                                                                                    and Release
                                                                                                    Upon Progress
                                                                                                    Payment
                                                                                                </option>
                                                                                                <option value="ConditionalWaiverFinal">
                                                                                                    Conditional Waiver
                                                                                                    and
                                                                                                    Release Upon Final
                                                                                                    Payment
                                                                                                </option>
                                                                                                <option value="UnconditionalWaiverFinal">
                                                                                                    Unconditional Waiver
                                                                                                    and Release Upon
                                                                                                    Final Payment
                                                                                                </option>
                                                                                                <option value="PartialWaiver">
                                                                                                    Partial Waiver
                                                                                                    of Lien (For an
                                                                                                    Amount Only)
                                                                                                </option>
                                                                                                <option value="PartialWaiverDate">
                                                                                                    Partial
                                                                                                    Waiver of Lien (To
                                                                                                    Date Only)
                                                                                                </option>
                                                                                                <option value="WaiverOfLien">
                                                                                                    Final Waiver of
                                                                                                    Lien (Standard)
                                                                                                </option>
                                                                                            </optgroup>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <button class="blue-btn"
                                                                                                id="createDocumentLien"
                                                                                                type="button" disabled>
                                                                                            Create
                                                                                            document
                                                                                        </button>
                                                                                    </div>
                                                                                </div>--}}
                                                                                <div class="table-responsive">
                                                                                    <table class="table">
                                                                                        <div class="form-group">
                                                                                            <label>Existing
                                                                                                Documents</label>
                                                                                        </div>
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th class="text-center">Document
                                                                                                Date
                                                                                            </th>
                                                                                            <th class="text-center">Document
                                                                                                Type
                                                                                            </th>
                                                                                            <th class="text-center">View
                                                                                            </th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <tr>
                                                                                            <td>N/A</td>
                                                                                            <td>Lien Law Summary</td>
                                                                                            <td>
                                                                                                <button id="lianLawSummery"
                                                                                                        type="button"
                                                                                                        class="form-control btn blue-btn btn-success lianLawSummery">
                                                                                                    View
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        @if(!empty($project_documents))
                                                                                            @foreach($project_documents as $document)
                                                                                                @if($document['data'])
                                                                                                    <tr>
                                                                                                        <td>{{ date("F d, Y h:i:s A", strtotime(isset($document['data']) ? $document['data']->created_at : '')) }}</td>
                                                                                                        {{--@if($document['name']) --}}
                                                                                                        <td>{{ $document['name'] }}</td>
                                                                                                        {{--@endif--}}
                                                                                                        @if($document['name'] == 'Claim form and project data sheet')
                                                                                                            <td>
                                                                                                                <a href="{{ route('get.lien.documentClaimView',[$project_id,$flag]) }}"
                                                                                                                   class="form-control btn blue-btn btn-success">View</a>
                                                                                                            </td>
                                                                                                        @elseif($document['name'] == 'Credit Application')
                                                                                                            <td>
                                                                                                                <a href="{{ route('get.lien.documentCreditView',[$project_id,$flag]) }}"
                                                                                                                   class="form-control btn blue-btn btn-success">View</a>
                                                                                                            </td>
                                                                                                        @elseif($document['name'] == 'joint payment authorization')
                                                                                                            <td>
                                                                                                                <a href="{{ route('get.lien.documentJointView',[$project_id,$flag]) }}"
                                                                                                                   class="form-control btn blue-btn btn-success">View</a>
                                                                                                            </td>
                                                                                                        @elseif($document['name'] == 'Waver progress')
                                                                                                            <td>
                                                                                                                <a href="{{ route('get.lien.documentWaverView',[$project_id,$flag]) }}"
                                                                                                                   class="form-control btn blue-btn btn-success">View</a>
                                                                                                            </td>
                                                                                                        @elseif($document['name'] == 'job info sheet')
                                                                                                            <td>
                                                                                                                <div class="col-md-12">
                                                                                                                    <div class="col-md-6">
                                                                                                                        <a href="{{ route('get.lien.job.info.sheet',[$project_id]) }}"
                                                                                                                           class="form-control btn blue-btn btn-success viewBtn"
                                                                                                                           target="_blank">View</a>
                                                                                                                    </div>
                                                                                                                    <div class="col-md-6">
                                                                                                                        <a href="{{ route('get.lien.jobInfoExport',[$project_id]) }}"
                                                                                                                           class="form-control btn btn-info"
                                                                                                                           target="_blank">Export
                                                                                                                            as
                                                                                                                            PDF</a>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                        @endif
                                                                                                    </tr>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="detailed" class="tab-pane fade in active">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12">
                                                            <div class="main-tab-sec">
                                                                <div class="main-tab-menu">
                                                                    <ul class="nav nav-tabs">
                                                                        <!-- <li class="active">
                                                                            <a data-toggle="tab" href="#project_details_details">Project</a>
                                                                        </li> -->
                                                                        <li class="active">
                                                                            <a data-toggle="tab" href="#lienLawChartDetailed">Dashboard</a>
                                                                        </li>
                                                                        <li >
                                                                            <a data-toggle="tab" href="#contact">Contact</a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-toggle="tab" href="#contracts">Contracts</a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-toggle="tab" href="#project-dates">Project
                                                                                Dates</a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-toggle="tab" href="#documents">Documents</a>
                                                                        </li>
                                                                        <li >
                                                                            <a data-toggle="tab" href="#home">Deadlines</a>
                                                                        </li>
                                                                        <li>
                                                                            <a data-toggle="tab" href="#tasks">Tasks</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="tab-content tab-final">

                                                                    <div id="lienLawChartDetailed"
                                                                         class="tab-pane fade in active">
                                                                        <div class="box">
                                                                            <div class="head-top">
                                                                                {{ $project->state->name }}
                                                                                <div class="pull-right">
                                                                                    <button class="btn btn-success expandall-icon">
                                                                                        <i class="fa fa-plus fa-2x"></i>
                                                                                    </button>
                                                                                    <button class="btn btn-warning collapseall-icon">
                                                                                        <i class="fa fa-minus fa-2x"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="head-top2">{{ $project->project_type->project_type }}</div>
                                                                            <div class="panel-group" id="accordion_detailed">
                                                                                @if(count($remedyNames) > 0)
                                                                                    @foreach($remedyNames as $remedyKey => $name)
                                                                                        <div class="panel panel-default">
                                                                                            <div class="panel-heading">
                                                                                                <h4 class="panel-title">
                                                                                                    <a data-toggle="collapse"
                                                                                                       data-parent="#accordion_detailed"
                                                                                                       href="#detailed_{{ $remedyKey }}">{{ $name }}</a>
                                                                                                </h4>
                                                                                            </div>
                                                                                            @if(count($liens) > 0)
                                                                                                @foreach($liens as $key => $lien)
                                                                                                    @if( $name == $lien['remedy'] )
                                                                                                        <div id="detailed_{{ $key }}"
                                                                                                             class="panel-collapse collapse">
                                                                                                            <div class="panel-body">
                                                                                                                <div class="panel-main">
                                                                                                                    <p style="font-size: medium;">
                                                                                                                        <strong>Description:</strong> {{ $lien->description }}
                                                                                                                    </p>
                                                                                                                    <p style="font-size: medium;">
                                                                                                                        <strong>Tiers:</strong> {{ $lien->tier_limit }}
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                                <div class="panel panel-default">
                                                                                    <div class="panel-heading">
                                                                                        <h4 class="panel-title">
                                                                                            <a data-toggle="collapse"
                                                                                               data-parent="#accordion_detailed"
                                                                                               href="#jobDescription_detailed">Job
                                                                                                Description (
                                                                                                <small><strong>{{ $project->project_name }}</strong></small>
                                                                                                )</a>
                                                                                        </h4>
                                                                                    </div>
                                                                                    <div id="jobDescription_detailed"
                                                                                         class="panel-collapse collapse">
                                                                                        <div class="panel-body">
                                                                                            <div class="panel-main">
                                                                                                <div class="col-md-12">
                                                                                                    <div align="left">
                                                                                                        <p>Job Name :
                                                                                                            <strong>{{ $project->project_name }}</strong>
                                                                                                        </p>
                                                                                                        <p>Job Address :
                                                                                                            <strong>{{ $project->address != '' ? $project->address : 'N/A' }}</strong>
                                                                                                        </p>
                                                                                                        <p>Job City :
                                                                                                            <strong>{{ $project->city != '' ? $project->city : 'N/A' }}</strong>
                                                                                                        </p>
                                                                                                        <p>Job State :
                                                                                                            <strong>{{ $project->state->name }}</strong>
                                                                                                        </p>
                                                                                                        <p>Job Zip :
                                                                                                            <strong>{{ $project->zip != '' ? $project->zip : 'N/A' }}</strong>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                @if(!is_null($project->customer_contract))
                                                                                    <div class="panel panel-default">
                                                                                        <div class="panel-heading">
                                                                                            <h4 class="panel-title">
                                                                                                <a data-toggle="collapse"
                                                                                                   data-parent="#accordion_detailed"
                                                                                                   href="#customer_detailed">Customer
                                                                                                    -
                                                                                                    <small><strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?$project->customer_contract->company->company : 'N/A' }}</strong></small>
                                                                                                </a>
                                                                                            </h4>
                                                                                        </div>
                                                                                        <div id="customer_detailed"
                                                                                             class="panel-collapse collapse in">
                                                                                            <div class="panel-body">
                                                                                                <div class="panel-main">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="col-md-5">
                                                                                                            <div align="left">
                                                                                                                <p>
                                                                                                                    Company
                                                                                                                    Name:
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?$project->customer_contract->company->company : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                                <p>
                                                                                                                    Address
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->address) ?$project->customer_contract->company->company : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                                <p>City
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->city) ?$project->customer_contract->company->city : 'N/A' }}</strong>,
                                                                                                                    State
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->state) ?$project->customer_contract->company->state->name : 'N/A'  }}</strong>,
                                                                                                                    Zip
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->zip) ?$project->customer_contract->company->zip : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                                <p>
                                                                                                                    Telephone
                                                                                                                    :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->phone) ?$project->customer_contract->company->phone : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                                <p>Fax :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->fax) ?$project->customer_contract->company->fax : 'N/A'  }}</strong>
                                                                                                                </p>
                                                                                                                <p>Web :
                                                                                                                    <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->website) ?$project->customer_contract->company->website : 'N/A' }}</strong>
                                                                                                                </p>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-2">
                                                                                                            &nbsp;
                                                                                                        </div>
                                                                                                        <div class="col-md-5">
                                                                                                            <div align="right">
                                                                                                                @if(isset($project->customer_contract->contactInformation))
                                                                                                                    @if(count($project->customer_contract->contactInformation) > 0)
                                                                                                                        @foreach($project->customer_contract->contactInformation as $customerKey => $contactInformation)
                                                                                                                            @if($customerKey == 0)
                                                                                                                                <p>
                                                                                                                                    First
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A'}}</strong>,
                                                                                                                                    Last
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}</strong>,
                                                                                                                                    Title
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->title != ''? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}</strong>
                                                                                                                                </p>
                                                                                                                                <p>
                                                                                                                                    Direct
                                                                                                                                    Phone
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->direct_phone }}</strong>
                                                                                                                                </p>
                                                                                                                                <p>
                                                                                                                                    Cell
                                                                                                                                    Phone
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->cell }}</strong>
                                                                                                                                </p>
                                                                                                                                <p>
                                                                                                                                    Email
                                                                                                                                    :
                                                                                                                                    <strong>{{ $contactInformation->email }}</strong>
                                                                                                                                </p>
                                                                                                                                @if(count($project->customer_contract->contactInformation) > 1)
                                                                                                                                    <p>
                                                                                                                                        <a href="javascript:void(0)"
                                                                                                                                           class="show_more"
                                                                                                                                           id="customerMore{{ $project->customer_contract->id }}"
                                                                                                                                           data-id="{{ $project->customer_contract->id }}">Show
                                                                                                                                            More</a>
                                                                                                                                        <a href="javascript:void(0)"
                                                                                                                                           class="show_less"
                                                                                                                                           id="customerLess{{ $project->customer_contract->id }}"
                                                                                                                                           data-id="{{ $project->customer_contract->id }}">Show
                                                                                                                                            Less</a>
                                                                                                                                    </p>
                                                                                                                                @endif
                                                                                                                            @else
                                                                                                                                <div class="customer{{ $project->customer_contract->id }}"
                                                                                                                                     style="display: none;">
                                                                                                                                    <p>
                                                                                                                                        First
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A'}}</strong>,
                                                                                                                                        Last
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}</strong>,
                                                                                                                                        Title
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->title != ''? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}</strong>
                                                                                                                                    </p>
                                                                                                                                    <p>
                                                                                                                                        Direct
                                                                                                                                        Phone
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->direct_phone }}</strong>
                                                                                                                                    </p>
                                                                                                                                    <p>
                                                                                                                                        Cell
                                                                                                                                        Phone
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->cell }}</strong>
                                                                                                                                    </p>
                                                                                                                                    <p>
                                                                                                                                        Email
                                                                                                                                        :
                                                                                                                                        <strong>{{ $contactInformation->email }}</strong>
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            @endif
                                                                                                                        @endforeach
                                                                                                                    @endif
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                                @if(count($projectContacts) > 0)
                                                                                    @foreach($projectContacts as $key => $contact)
                                                                                        @php
                                                                                            $customerDetails = \App\Models\CompanyContact::find($contact['customer_id']);
                                                                                            $mapDetails = $customerDetails->mapContactCompany;
                                                                                        @endphp
                                                                                        <div class="panel panel-default">
                                                                                            <div class="panel-heading">
                                                                                                <h4 class="panel-title">
                                                                                                    <a data-toggle="collapse" data-parent="#accordion_detailed" href="#industry_detailed{{ $key }}">{{ $contact['type'] }} - <small><strong>{{ $contact['company']->company }}</strong></small></a>
                                                                                                </h4>
                                                                                            </div>
                                                                                            <div id="industry_detailed{{ $key }}" class="panel-collapse collapse">
                                                                                                <div class="panel-body">
                                                                                                    <div class="panel-main">
                                                                                                        <div class="col-md-12">
                                                                                                            <div class="col-md-5">
                                                                                                                <div align="left">
                                                                                                                    <p>Company Name: <strong>{{ $contact['company']->company }}</strong></p>
                                                                                                                    <p>Address : <strong>{{ $contact['company']->address != '' ? $contact['company']->address : 'N/A' }}</strong></p>
                                                                                                                    <p>City : <strong>{{ $contact['company']->city != '' ? $contact['company']->city : 'N/A' }}</strong>, State : <strong>{{ $contact['company']->state->name }}</strong>, Zip : <strong>{{ $contact['company']->zip != '' ? $contact['company']->zip : 'N/A' }}</strong></p>
                                                                                                                    <p>Telephone : <strong>{{ $contact['company']->phone != '' ? $contact['company']->phone : 'N/A' }}</strong></p>
                                                                                                                    <p>Fax : <strong>{{ $contact['company']->fax != '' ? $contact['company']->fax : 'N/A' }}</strong></p>
                                                                                                                    <p>Web : <strong>{{ $contact['company']->website != '' ? $contact['company']->website : 'N/A' }}</strong></p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-md-2">
                                                                                                                &nbsp;
                                                                                                            </div>
                                                                                                            <div class="col-md-5">
                                                                                                                <div align="right">
                                                                                                                    @if(count($contact['customers']) > 0)
                                                                                                                        @foreach($contact['customers'] as $industryKey => $contactInformationIns)
                                                                                                                            @if($industryKey == 0)
                                                                                                                                <p>First : <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A'}}</strong>, Last : <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>, Title : <strong>{{ $contactInformationIns->title != ''? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong></p>
                                                                                                                                <p>Direct Phone : <strong>{{ $contactInformationIns->phone }}</strong></p>
                                                                                                                                <p>Cell Phone : <strong>{{ $contactInformationIns->cell }}</strong></p>
                                                                                                                                <p>Email : <strong>{{ $contactInformationIns->email }}</strong></p>
                                                                                                                                @if(count($contact['customers']) > 1)
                                                                                                                                    <p>
                                                                                                                                        <a href="javascript:void(0)" class="show_more" id="customerMore{{ $key}}" data-id="{{ $key }}">Show More</a>
                                                                                                                                        <a href="javascript:void(0)" class="show_less" id="customerLess{{ $key }}" data-id="{{ $key }}">Show Less</a>
                                                                                                                                    </p>
                                                                                                                                @endif
                                                                                                                            @else
                                                                                                                                <div class="customer{{ $key}}" style="display: none;">
                                                                                                                                    <p>First : <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A'}}</strong>, Last : <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>, Title : <strong>{{ $contactInformationIns->title != ''? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong></p>
                                                                                                                                    <p>Direct Phone : <strong>{{ $contactInformationIns->phone }}</strong></p>
                                                                                                                                    <p>Cell Phone : <strong>{{ $contactInformationIns->cell }}</strong></p>
                                                                                                                                    <p>Email : <strong>{{ $contactInformationIns->email }}</strong></p>
                                                                                                                                </div>
                                                                                                                            @endif
                                                                                                                        @endforeach
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif
                                                                                <div class="head-top">
                                                                                    &nbsp;
                                                                                    <div class="pull-right">
                                                                                        <button class="btn btn-success expandall-icon2"><i class="fa fa-plus fa-2x"></i> </button>
                                                                                        <button class="btn btn-warning collapseall-icon2"><i class="fa fa-minus fa-2x"></i> </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <div id="contact" class="tab-pane fade in">

                                                                        <div class="tab-content">
                                                                            <div id="allRemedies"
                                                                                 class="tab-pane fade in active">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-striped">
                                                                                        <tr>
                                                                                            <th>First Name</th>
                                                                                            <th>Last Name</th>
                                                                                            <th>Company</th>
                                                                                            <th>Email Address</th>
                                                                                            <th>Phone Number</th>
                                                                                            <th>Address</th>
                                                                                            <th>City</th>
                                                                                            <th>State</th>
                                                                                            <th>ZIP Code</th>
                                                                                        </tr>
                                                                                        @if(isset($projectOwner))
                                                                                            <tr>
                                                                                                <td style="text-align: left">{{ $projectOwnerDetails->first_name }}</td>
                                                                                                <td style="text-align: left">{{ $projectOwnerDetails->last_name }}</td>
                                                                                                <td style="text-align: left">{{ $projectOwnerDetails->company }}</td>
                                                                                                <td style="text-align: left">{{ $projectOwner->email }}</td>
                                                                                                <td style="text-align: left">{{ $projectOwnerDetails->phone }}</td>
                                                                                                <td style="text-align: left">{{ $projectOwnerDetails->address }}</td>
                                                                                                <td style="text-align: left">{{ $projectOwnerDetails->city }}</td>
                                                                                                @if(isset($projectOwnerDetailsState))
                                                                                                    <td style="text-align: left">{{ $projectOwnerDetailsState->name }}</td>
                                                                                                @else
                                                                                                    <td style="text-align: left"></td>
                                                                                                @endif
                                                                                                <td style="text-align: left">{{ $projectOwnerDetails->zip }}</td>
                                                                                            </tr>
                                                                                        @else
                                                                                            <tr>
                                                                                                <td colspan="5"> No
                                                                                                    Contact found...
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    {{--<div id="project_details_details" class="tab-pane fade in active">
                                                                        <div class="project-heading">
                                                                            <a href="javascript:void(0)">Project Name</a>
                                                                            <strong>{{ $project->project_name }}</strong>
                                                                        </div>
                                                                        <div class="row tab-final">

                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Project Location by State :
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @foreach($states as $state)
                                                                                                        @if($state->id == $project->state_id)
                                                                                                            <strong> {{$state->name}}</strong>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Your Role :
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @foreach($roles as $role)
                                                                                                        @if($role->id == $project->role_id)
                                                                                                            <strong>{{$role->project_roles}}</strong>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row tab-final">
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Project Type :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @foreach($types as $type)
                                                                                                        @if($type->id == $project->project_type_id)
                                                                                                            <strong>{{$type->project_type}}</strong>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Who Hired You :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @if(isset($project) && $project != '')
                                                                                                        <strong>{{ $project->tier->customer->name }}</strong>
                                                                                                    @else
                                                                                                        Select a Select your customer
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row tab-final">
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Customer Contract :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ !is_null($project->customer_contract) && !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?$project->customer_contract->company->company : 'N/A'  }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Industry Contract :
                                                                                                    </a>
                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    @if($project->industryContacts != '')
                                                                                                        @foreach($project->industryContacts as $contact)
                                                                                                            {{ $contact->contacts ? $contact->contacts->company.', ' : '' }}
                                                                                                        @endforeach
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row tab-final">
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Address :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ $project->address }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        City :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ $project->city }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row tab-final">
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Country :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ $project->country }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="border-bottom">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    <a href="javascript:void(0)">
                                                                                                        Your Company's Work :
                                                                                                    </a>

                                                                                                </div>
                                                                                                <div class="col-md-6 col-sm-6">
                                                                                                    {{ $project->company_work }}
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>--}}
                                                                    <div id="home" class="tab-pane fade in">
                                                                        <div class="tab-area1">
                                                                            <ul class="nav nav-tabs">
                                                                                @if(count($remedyNames) > 0)
                                                                                    <li class="active"><a
                                                                                                data-toggle="tab"
                                                                                                href="#allRemedies">All
                                                                                            Remedies</a></li>
                                                                                    @foreach($remedyNames as $key => $name)
                                                                                        <li class=""><a
                                                                                                    data-toggle="tab"
                                                                                                    href="#{{ $key }}">{{ $name }}</a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                        <div class="tab-content">
                                                                            <div id="allRemedies"
                                                                                 class="tab-pane fade in active">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-striped">
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            <th>Legal Steps</th>
                                                                                            <th>Days Remaining</th>
                                                                                            <th>Preliminary Deadline
                                                                                            </th>
                                                                                            <th>Date Legal Step
                                                                                                Completed
                                                                                            </th>
                                                                                            <th>Email Alert</th>
                                                                                        </tr>
                                                                                        @if(count($deadlines) > 0)
                                                                                            @foreach($deadlines as $key => $deadline)
                                                                                                <tr>
                                                                                                    <td>{{ $key + 1 }}</td>
                                                                                                    <td>{{ $deadline->short_description }}
                                                                                                        <span
                                                                                                                class="tag label label-info">{{ $deadlines[$key]->getRemedy->remedy }}</span>
                                                                                                    </td>
                                                                                                    <td>{{ $daysRemain[$key]['dates'] }}</td>
                                                                                                    <td>
                                                                                                        {{ $daysRemain[$key]['preliminaryDates'] }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <input type="text"
                                                                                                               class="form-control date"
                                                                                                               name="date-legal[]"
                                                                                                               disabled="disabled"
                                                                                                               data-provide="datepicker"
                                                                                                               value="{{ $deadlines[$key]['legal_completion_date'] }}">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <select name="email-alert[]"
                                                                                                                disabled="disabled">
                                                                                                            <option value="1" {{ $deadlines[$key]['email_alert'] == 1 ? 'selected' : ''}}>
                                                                                                                On
                                                                                                            </option>
                                                                                                            <option value="0" {{ $deadlines[$key]['email_alert'] == 0 ? 'selected' : ''}}>
                                                                                                                Off
                                                                                                            </option>
                                                                                                        </select>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @else
                                                                                            <tr>
                                                                                                <td colspan="5"> No
                                                                                                    Deadline found...
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            @if(count($remedyNames) > 0)
                                                                                @foreach($remedyNames as $remedyKey => $name)
                                                                                    <div id="{{ $remedyKey }}"
                                                                                         class="tab-pane fade">
                                                                                        <table class="table table-striped">
                                                                                            <tr>
                                                                                                <th>Legal Steps</th>
                                                                                                <th>Days Remaining</th>
                                                                                                <th>Preliminary
                                                                                                    Deadline
                                                                                                </th>
                                                                                                <th>Date Legal Step
                                                                                                    Completed
                                                                                                </th>
                                                                                                <th>Email Alert</th>
                                                                                            </tr>
                                                                                            @if(count($deadlines) > 0)
                                                                                                @foreach($deadlines as $key => $deadline)
                                                                                                    @if( $remedyKey == $deadlines[$key]['remedy_id'] )
                                                                                                        <tr>
                                                                                                            <td>{{ $deadline->short_description }}</td>
                                                                                                            <td>{{ $daysRemain[$key]['dates'] }}</td>
                                                                                                            <td>
                                                                                                                {{ $daysRemain[$key]['preliminaryDates'] }}
                                                                                                            </td>
                                                                                                            <td>
                                                                                                                <input type="text"
                                                                                                                       class="form-control date"
                                                                                                                       disabled="disabled"
                                                                                                                       name="date-legal[]"
                                                                                                                       data-provide="datepicker"
                                                                                                                       value="{{ $deadlines[$key]['legal_completion_date'] }}">
                                                                                                            </td>
                                                                                                            <td>
                                                                                                                <select name="email-alert[]"
                                                                                                                        disabled="disabled">
                                                                                                                    <option value="1" {{ $deadlines[$key]['email_alert'] == 1 ? 'selected' : ''}}>
                                                                                                                        On
                                                                                                                    </option>
                                                                                                                    <option value="0" {{ $deadlines[$key]['email_alert'] == 0 ? 'selected' : ''}}>
                                                                                                                        Off
                                                                                                                    </option>
                                                                                                                </select>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </table>
                                                                                    </div>
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div id="contracts" class="tab-pane fade in">
                                                                        <!--   <h1> Project contracts</h1> -->
                                                                        <div class="row">
                                                                            <div class="col-md-12 col-sm-12">
                                                                                <div class="border-table">
                                                                                    <div class="table-responsive amount-table">
                                                                                        <table class="table">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <td>Base Contract Amount
                                                                                                    $
                                                                                                </td>
                                                                                                <th><input type="number"
                                                                                                           class="form-control"
                                                                                                           name="base_amount"
                                                                                                           id="base_amount"
                                                                                                           value="{{ (isset($contract) && $contract != '' && $contract->base_amount != '')?$contract->base_amount:'0' }}"
                                                                                                           disabled>
                                                                                                </th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            <tr>
                                                                                                <td>+ Value of Extras of
                                                                                                    Changes $
                                                                                                </td>
                                                                                                <th><input type="number"
                                                                                                           class="form-control"
                                                                                                           name="extra_amount"
                                                                                                           id="extra_amount"
                                                                                                           value="{{ (isset($contract) && $contract != '' && $contract->extra_amount != '')?$contract->extra_amount:'0' }}"
                                                                                                           disabled>
                                                                                                </th>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>= Revised Contract
                                                                                                    Subtotal $
                                                                                                </td>
                                                                                                <th><input type="text"
                                                                                                           class="form-control"
                                                                                                           id="contact_total"
                                                                                                           disabled="disabled"
                                                                                                           disabled>
                                                                                                </th>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>- Payments/Credits
                                                                                                    to Date $
                                                                                                </td>
                                                                                                <th><input type="number"
                                                                                                           class="form-control"
                                                                                                           name="payment"
                                                                                                           id="payment"
                                                                                                           value="{{ (isset($contract) && $contract != '' && $contract->credits != '')?$contract->credits:'0' }}"
                                                                                                           disabled>
                                                                                                </th>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>= Total Claim Amount
                                                                                                    $
                                                                                                </td>
                                                                                                <th><input type="text"
                                                                                                           class="form-control"
                                                                                                           id="claim_amount"
                                                                                                           name="claim_amount"
                                                                                                           disabled="disabled"
                                                                                                           disabled>
                                                                                                </th>
                                                                                            </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="border-table">
                                                                                    <div class="table-responsive waiver-table">
                                                                                        <table class="table">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <td>Waiver Amount</td>
                                                                                                <th><input type="number"
                                                                                                           name="waiver_amount"
                                                                                                           value="{{ (isset($contract) && $contract != '' && $contract->waiver != '')?$contract->waiver:'0' }}"
                                                                                                           disabled
                                                                                                           class="form-control">
                                                                                                </th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            <tr>
                                                                                                <th>Receivable Status
                                                                                                </th>
                                                                                                <td>
                                                                                                    <strong>
                                                                                                        {{ (isset($contract) && $contract != '' && $contract->receivable_status)? $contract->receivable_status : '' }}
                                                                                                    </strong>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                            </tr>
                                                                                            </tbody>
                                                                                            <tfoot>
                                                                                            <tr>
                                                                                                <th>Deadline Calculation
                                                                                                    Status
                                                                                                </th>
                                                                                                <td>
                                                                                                    <?php (isset($contract) && $contract != '' && $contract->receivable_status) ? $var = $contract->calculation_status : $var = '4' ?>
                                                                                                    @if($var == '0')
                                                                                                        <strong>In
                                                                                                            Process</strong>
                                                                                                    @elseif($var == '1')
                                                                                                        Completed
                                                                                                    @endif
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tfoot>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4 col-sm-4">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="project-dates" class="tab-pane fade">
                                                                        <div class="border-table">
                                                                            <!-- <h1> Project Dates</h1> -->
                                                                            <form action="#" method="post"
                                                                                  class="form-horizontal project-form project_dates">
                                                                                @foreach($dates as $date)
                                                                                    <div class="form-group">
                                                                                        <label class="col-md-6 control-label">{{ $date->date_name }}</label>
                                                                                        <div class="col-md-6">
                                                                                            <input type="text"
                                                                                                   class="form-control date"
                                                                                                   name="remedyDates[{{ $date->id }}]"
                                                                                                   value="{{ isset($projectDates[$date->id])?$projectDates[$date->id]:'' }}"
                                                                                                   data-provide="datepicker"
                                                                                                   disabled>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                                {{ csrf_field() }}
                                                                                <input type="hidden" name="project_id"
                                                                                       value="{{ $project_id }}"
                                                                                       class="project_id">
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                    <div id="documents" class="tab-pane fade">
                                                                        <div class="border-table">
                                                                            <!-- <h1 class="text-center">Project Documents</h1>
                                                                            <hr class="divider"> -->
                                                                            <form action="#" method="post"
                                                                                  class="form-horizontal project-form project_documents">
                                                                                {{--<div class="form-group">
                                                                                    <label class="col-md-2">Create
                                                                                        Documents</label>
                                                                                    <div class="col-md-6">
                                                                                        <select id="DocumentType"
                                                                                                class="form-control"
                                                                                                name="DocumentType">
                                                                                            <option value="">Select a
                                                                                                Document Type
                                                                                            </option>
                                                                                            <option value="JobInfo">Job
                                                                                                Info Sheet
                                                                                            </option>
                                                                                            <option value="ClaimData">
                                                                                                Claim Form and Project
                                                                                                Data Sheet
                                                                                            </option>
                                                                                            <option value="CreditApplication">
                                                                                                Credit
                                                                                                Application
                                                                                            </option>
                                                                                            <option value="JointPaymentAuthorization">
                                                                                                Joint
                                                                                                Payment
                                                                                                Authorization
                                                                                            </option>
                                                                                            <optgroup
                                                                                                    label="Waiver of Lien Forms">
                                                                                                <option value="UnconditionalWaiverProgress">
                                                                                                    Unconditional Waiver
                                                                                                    and Release Upon
                                                                                                    Progress Payment
                                                                                                </option>
                                                                                                <option value="ConditionalWaiver">
                                                                                                    Conditional Waiver
                                                                                                    and Release
                                                                                                    Upon Progress
                                                                                                    Payment
                                                                                                </option>
                                                                                                <option value="ConditionalWaiverFinal">
                                                                                                    Conditional Waiver
                                                                                                    and
                                                                                                    Release Upon Final
                                                                                                    Payment
                                                                                                </option>
                                                                                                <option value="UnconditionalWaiverFinal">
                                                                                                    Unconditional Waiver
                                                                                                    and Release Upon
                                                                                                    Final Payment
                                                                                                </option>
                                                                                                <option value="PartialWaiver">
                                                                                                    Partial Waiver
                                                                                                    of Lien (For an
                                                                                                    Amount Only)
                                                                                                </option>
                                                                                                <option value="PartialWaiverDate">
                                                                                                    Partial
                                                                                                    Waiver of Lien (To
                                                                                                    Date Only)
                                                                                                </option>
                                                                                                <option value="WaiverOfLien">
                                                                                                    Final Waiver of
                                                                                                    Lien (Standard)
                                                                                                </option>
                                                                                            </optgroup>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <button class="blue-btn"
                                                                                                id="createDocument"
                                                                                                type="button" disabled>
                                                                                            Create
                                                                                            document
                                                                                        </button>
                                                                                    </div>
                                                                                </div>--}}
                                                                                {{--
                                                                                <div class="form-group">--}}
                                                                                {{--<label>Existing Documents</label>--}}
                                                                                {{--
                                                                             </div>
                                                                             --}}
                                                                                <table class="table">
                                                                                    <div class="form-group">
                                                                                        <label>Existing
                                                                                            Documents</label>
                                                                                    </div>
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">Document
                                                                                            Date
                                                                                        </th>
                                                                                        <th class="text-center">Document
                                                                                            Type
                                                                                        </th>
                                                                                        <th class="text-center">View
                                                                                        </th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td>N/A</td>
                                                                                        <td>Lien Law Summary</td>
                                                                                        <td>
                                                                                            <button id="lianLawSummery"
                                                                                                    type="button"
                                                                                                    class="form-control btn blue-btn btn-success lianLawSummery">
                                                                                                View
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>
                                                                                    @if(!empty($project_documents))
                                                                                        @foreach($project_documents as $document)
                                                                                            @if($document['data'])
                                                                                                <tr>
                                                                                                    <td>{{ date("F d, Y h:i:s A", strtotime(isset($document['data']) ? $document['data']->created_at : '')) }}</td>
                                                                                                    {{--@if($document['name']) --}}
                                                                                                    <td>{{ $document['name'] }}</td>
                                                                                                    {{--@endif--}}
                                                                                                    @if($document['name'] == 'Claim form and project data sheet')
                                                                                                        <td>
                                                                                                            <a href="{{ route('get.documentClaimView',[$project_id,$flag]) }}"
                                                                                                               class="form-control btn blue-btn btn-success">View</a>
                                                                                                        </td>
                                                                                                    @elseif($document['name'] == 'Credit Application')
                                                                                                        <td>
                                                                                                            <a href="{{ route('get.documentCreditView',[$project_id,$flag]) }}"
                                                                                                               class="form-control btn blue-btn btn-success">View</a>
                                                                                                        </td>
                                                                                                    @elseif($document['name'] == 'joint payment authorization')
                                                                                                        <td>
                                                                                                            <a href="{{ route('get.documentJointView',[$project_id,$flag]) }}"
                                                                                                               class="form-control btn blue-btn btn-success">View</a>
                                                                                                        </td>
                                                                                                    @elseif($document['name'] == 'Waver progress')
                                                                                                        <td>
                                                                                                            <a href="{{ route('get.documentWaverView',[$project_id,$flag]) }}"
                                                                                                               class="form-control btn blue-btn btn-success">View</a>
                                                                                                        </td>
                                                                                                    @elseif($document['name'] == 'job info sheet')
                                                                                                        <td>
                                                                                                            <div class="col-md-12">
                                                                                                                <div class="col-md-6">
                                                                                                                    <a href="{{ route('get.lien.job.info.sheet',[$project_id]) }}"
                                                                                                                       class="form-control btn blue-btn btn-success viewBtn"
                                                                                                                       target="_blank">View</a>
                                                                                                                </div>
                                                                                                                <div class="col-md-6">
                                                                                                                    <a href="{{ route('get.lien.jobInfoExport',[$project_id]) }}"
                                                                                                                       class="form-control btn btn-info"
                                                                                                                       target="_blank">Export
                                                                                                                        as
                                                                                                                        PDF</a>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    @endif
                                                                                                </tr>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                    </tbody>
                                                                                </table>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                    <div id="tasks" class="tab-pane fade">
                                                                        <div class="border-table">
                                                                        <!--  <h1 class="text-center">Project Tasks</h1>
     <hr class="divider">
     @if(count($tasks) > 0) -->
                                                                            <div class="row border-class">
                                                                                <div class="col-md-12">
                                                                                    <div class="table-responsive">
                                                                                        <table class="table table-striped">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th class="text-center">
                                                                                                    #
                                                                                                </th>
                                                                                                <th class="text-center">
                                                                                                    Action
                                                                                                </th>
                                                                                                <th class="text-center">
                                                                                                    Comments
                                                                                                </th>
                                                                                                <th class="text-center">
                                                                                                    Deadline
                                                                                                </th>
                                                                                                <th class="text-center">
                                                                                                    Date Completed
                                                                                                </th>
                                                                                                <th class="text-center">
                                                                                                    Email Alert
                                                                                                </th>
                                                                                                <th class="text-center">
                                                                                                    Action
                                                                                                </th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            @foreach($tasks as $key => $task)
                                                                                                @php
                                                                                                    $date1 = new DateTime($task->due_date);
                                                                                                    $date2 = new DateTime(date('Y-m-d'));
                                                                                                    $diff = $date2->diff($date1)->format("%R%a days");
                                                                                                    $exactDiff = $date2->diff($date1)->format("%a days");
                                                                                                @endphp
                                                                                                <tr>
                                                                                                    <td>{{ $key + 1 }}</td>
                                                                                                    <td>{{ $task->task_name }}</td>
                                                                                                    <td>{{ $task->comment }}</td>
                                                                                                    <td>
                                                                                                        {{  \DateTime::createFromFormat('Y-m-d', $task->due_date)->format('m/d/Y') }}
                                                                                                        <br/> <span
                                                                                                                style="color: red;">
                          {{ $diff > 0?'You have '.$exactDiff.' remaining.':'This deadline has passed' }}
                          </span>
                                                                                                    </td>
                                                                                                    <td>{{ $task->complete_date != ''?\DateTime::createFromFormat('Y-m-d', $task->complete_date)->format('d/m/Y'):'' }}</td>
                                                                                                    <td>{{ $task->email_alert == 0?'Off':'On' }}</td>
                                                                                                    <td>
                                                                                                        <button class="btn btn-xs btn-warning editButton"
                                                                                                                type="button"
                                                                                                                data-id="{{ $task->id }}"
                                                                                                                data-action="{{ $task->task_name }}"
                                                                                                                data-due_date="{{ \DateTime::createFromFormat('Y-m-d', $task->due_date)->format('d/m/Y') }}"
                                                                                                                data-complete_date="{{ $task->complete_date != ''?\DateTime::createFromFormat('Y-m-d', $task->complete_date)->format('d/m/Y'):'' }}"
                                                                                                                data-email="{{ $task->email_alert }}"
                                                                                                                data-file="{{ $task->file_link }}"
                                                                                                                data-comment="{{ $task->comment }}"
                                                                                                                data-toggle="modal">
                                                                                                            <i class="fa fa-pencil"></i>
                                                                                                        </button>
                                                                                                        <button class="btn btn-xs btn-danger deleteTask"
                                                                                                                data-id="{{ $task->id }}"
                                                                                                                type="button">
                                                                                                            <i class="fa fa-trash"></i>
                                                                                                        </button>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @else
                                                                                <div class="alert alert-warning">
                                                                                    <h4>You currently have no tasks set
                                                                                        up for this
                                                                                        project.</h4>
                                                                                </div>
                                                                            @endif
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <form action="{{ route('project.add.task') }}"
                                                                                          class="form-horizontal border-class"
                                                                                          method="post">
                                                                                        <h4 class="text-center">Create
                                                                                            New Task</h4>
                                                                                        <hr class="divider">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label col-md-1">Action</label>
                                                                                            <div class="col-md-3">
                                                                                                <select name="action"
                                                                                                        class="form-control">
                                                                                                    <option value="Call Customer">
                                                                                                        Call
                                                                                                        Customer
                                                                                                    </option>
                                                                                                    <option value="Follow Up Payment">
                                                                                                        Follow
                                                                                                        Up Payment
                                                                                                    </option>
                                                                                                    <option value="Prepare Waivers for Draw">
                                                                                                        Prepare Waivers
                                                                                                        for Draw
                                                                                                    </option>
                                                                                                    <option value="Prepare Credit Application">
                                                                                                        Prepare Credit
                                                                                                        Application
                                                                                                    </option>
                                                                                                    <option value="Prepare  Rider to Contract">
                                                                                                        Prepare Rider to
                                                                                                        Contract
                                                                                                    </option>
                                                                                                    <option value="Forward Claim To NLB">
                                                                                                        Forward Claim To
                                                                                                        NLB
                                                                                                    </option>
                                                                                                    <option value="Other">
                                                                                                        Other
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <label class="control-label col-md-2">Due
                                                                                                Date</label>
                                                                                            <div class="col-md-2">
                                                                                                <input type="text"
                                                                                                       class="form-control date"
                                                                                                       name="due_date"
                                                                                                       data-provide="datepicker">
                                                                                            </div>
                                                                                            <label class="control-label col-md-2">Email
                                                                                                Alert</label>
                                                                                            <div class="col-md-2">
                                                                                                <select name="email_alert"
                                                                                                        class="form-control">
                                                                                                    <option value="0">
                                                                                                        Off
                                                                                                    </option>
                                                                                                    <option value="1">
                                                                                                        On
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <input type="hidden"
                                                                                               name="project_id"
                                                                                               value="{{ $project_id }}">
                                                                                        <div class="form-group">
                                                                                            <label class="control-label col-md-2">Comments</label>
                                                                                            <div class="col-md-6">
                                                                            <textarea class="form-control"
                                                                                      name="comment"></textarea>
                                                                                            </div>
                                                                                            <button type="submit"
                                                                                                    class="btn btn-lg blue-btn col-md-2">
                                                                                                Add
                                                                                                Task
                                                                                            </button>
                                                                                        </div>
                                                                                        {{ csrf_field() }}
                                                                                        @if ($errors->any())
                                                                                            <div class="alert alert-danger">
                                                                                                <ul>
                                                                                                    @foreach ($errors->all() as $error)
                                                                                                        <li>{{ $error }}</li>
                                                                                                    @endforeach
                                                                                                </ul>
                                                                                            </div>
                                                                                        @endif
                                                                                    </form>
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
    <!-- Modal -->
    <div id="editTask" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Task</h4>
                </div>
                <div class="modal-body">
                    <form id="editTaskForm" class="form-horizontal"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Action</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="action">
                                            <option value="Call Customer">Call Customer</option>
                                            <option value="Follow Up Payment">Follow Up Payment</option>
                                            <option value="Prepare Waivers for Draw">Prepare Waivers for Draw</option>
                                            <option value="Prepare Credit Application">Prepare Credit Application
                                            </option>
                                            <option value="Prepare  Rider to Contract">Prepare Rider to Contract
                                            </option>
                                            <option value="Forward Claim To NLB">Forward Claim To NLB</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Due-Date</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control date" id="due_date"
                                               data-provide="datepicker">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Complete-Date</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control date" id="complete_date"
                                               data-provide="datepicker">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Email Alert</label>
                                    <div class="col-md-8">
                                        <select id="email_alert" class="form-control">
                                            <option value="0">Off</option>
                                            <option value="1">On</option>
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" id="task_id">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Comments</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="comment"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fax" class="col-sm-4 control-label">File</label>

                                    <div class="col-sm-8">
                                        <input type="file" class="form-control error" name="file" id="file"
                                               placeholder="File">
                                        <div id="file_field"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary editTaskButton">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            var queryString = window.location.search.replace("?", "");
            var parameterListRaw = queryString == "" ? [] : queryString.split("&");
            var parameterList = {};
            for (var i = 0; i < parameterListRaw.length; i++) {
                var parameter = parameterListRaw[i].split("=");
                if(parameter[0] == 'tab') {
                    $('a[href="#' + parameter[1] + '"]').trigger('click');
                }
            }

            base_amount = $('#base_amount').val();
            extra_amount = $('#extra_amount').val();
            payment = $('#payment').val();
            total = parseFloat(base_amount) + parseFloat(extra_amount);
            claim_total = parseFloat(total) - parseFloat(payment);
            $('#contact_total').val(total);
            $('#claim_amount').val(claim_total);


            $('#activate-step-5').on('click', function (e) {
                window.location.href = "{{ route('project.task.view').'?project_id='.$project_id }}";
            });
            $('.lianLawSummery').on('click', function () {
                url = "{{ route('get.lien.lineBoundSummery',[$project->state_id,$project->project_type_id]) }}";
                window.open(url, '_blank');
            });
            $('#DocumentType').on('change', function () {
                var selected = $(this).val();
                if (selected != '') {
                    $('#createDocument').removeAttr('disabled');
                } else {
                    $('#createDocument').attr('disabled', 'disabled');
                }
            });
            $('#DocumentTypeLien').on('change', function () {
                var selected = $(this).val();
                if (selected != '') {
                    $('#createDocumentLien').removeAttr('disabled');
                } else {
                    $('#createDocumentLien').attr('disabled', 'disabled');
                }
            });
            $('#createDocument').on('click', function () {
                var document = $('#DocumentType').val();
                var url = '';
                if (document == 'ClaimData') {
                    url = '{{ route('get.documentClaimData',[$project_id]) }}';
                } else if (document == 'CreditApplication') {
                    url = '{{ route('get.creditApplication',[$project_id]) }}';
                } else if (document == 'JointPaymentAuthorization') {
                    url = '{{ route('get.jointPaymentAuthorization',[$project_id]) }}';
                } else if (document == 'UnconditionalWaiverProgress') {
                    url = '{{ route('get.documentUnconditionalWaiverRelease',[$project_id]) }}';
                } else if (document == 'ConditionalWaiver') {
                    url = '{{ route('get.documentConditionalWaiver',[$project_id]) }}';
                } else if (document == 'ConditionalWaiverFinal') {
                    url = '{{ route('get.documentConditionalWaiverFinal',[$project_id]) }}';
                } else if (document == 'UnconditionalWaiverFinal') {
                    url = '{{ route('get.documentUnconditionalWaiverFinal',[$project_id]) }}';
                } else if (document == 'PartialWaiver') {
                    url = '{{ route('get.documentPartialWaiver',[$project_id]) }}';
                } else if (document == 'PartialWaiverDate') {
                    url = '{{ route('get.documentPartialWaiverDate',[$project_id]) }}';
                } else if (document == 'WaiverOfLien') {
                    url = '{{ route('get.documentStandardWaiverFinal',[$project_id]) }}';
                } else if (document == 'JobInfo') {
                    url = '{{ route('get.job.info.sheet',[ $project_id ]) }}';
                } else {
                    url = '{{ route('lien.dashboard') }}';
                }
                window.open(url, '_blank');
            });
            $('#createDocumentLien').on('click', function () {
                var document = $('#DocumentTypeLien').val();
                var url = '';
                if (document == 'ClaimData') {
                    url = '{{ route('get.documentClaimData',[$project_id]) }}';
                } else if (document == 'CreditApplication') {
                    url = '{{ route('get.creditApplication',[$project_id]) }}';
                } else if (document == 'JointPaymentAuthorization') {
                    url = '{{ route('get.jointPaymentAuthorization',[$project_id]) }}';
                } else if (document == 'UnconditionalWaiverProgress') {
                    url = '{{ route('get.documentUnconditionalWaiverRelease',[$project_id]) }}';
                } else if (document == 'ConditionalWaiver') {
                    url = '{{ route('get.documentConditionalWaiver',[$project_id]) }}';
                } else if (document == 'ConditionalWaiverFinal') {
                    url = '{{ route('get.documentConditionalWaiverFinal',[$project_id]) }}';
                } else if (document == 'UnconditionalWaiverFinal') {
                    url = '{{ route('get.documentUnconditionalWaiverFinal',[$project_id]) }}';
                } else if (document == 'PartialWaiver') {
                    url = '{{ route('get.documentPartialWaiver',[$project_id]) }}';
                } else if (document == 'PartialWaiverDate') {
                    url = '{{ route('get.documentPartialWaiverDate',[$project_id]) }}';
                } else if (document == 'WaiverOfLien') {
                    url = '{{ route('get.documentStandardWaiverFinal',[$project_id]) }}';
                } else if (document == 'JobInfo') {
                    url = '{{ route('get.job.info.sheet',[ $project_id ]) }}';
                } else {
                    url = '{{ route('lien.dashboard') }}';
                }
                window.open(url, '_blank');
            });
            $('.editButton').on('click', function () {
                $("#file_field").empty();
                $("#file").val('');
                var id = $(this).data('id');
                $('#task_id').val(id);
                var action = $(this).data('action');
                $('#action').val(action);
                var due_date = $(this).data('due_date');
                $('#due_date').val(due_date);
                var email = $(this).data('email');
                $('#email_alert').val(email);
                var comment = $(this).data('comment');
                $('#comment').val(comment);
                var complete_date = $(this).data('complete_date');
                $('#complete_date').val(complete_date);
                var file = $(this).data('file');
                if(file) {
                    $("#file_field").append('<a href="/upload/'+ file +'"  target="_blank">' + file + '</a>');
                }
                $('#editTask').modal('show');
            });
            $('.editTaskButton').on('click', function () {
                var action = $('#action').val();
                if (action == '') {
                    $('#action').addClass('input-error');
                    return false;
                } else {
                    $('#action').removeClass('input-error');
                }
                var id = $('#task_id').val();
                var due_date = $('#due_date').val();
                if (due_date == '') {
                    $('#due_date').addClass('input-error');
                    return false;
                } else {
                    $('#due_date').removeClass('input-error');
                }
                var complete_date = $('#complete_date').val();
                var email = $('#email_alert').val();
                var comment = $('#comment').val();
                // if(comment == ''){
                //     $('#comment').addClass('input-error');
                //     return false;
                // } else {
                //     $('#comment').removeClass('input-error');
                // }


                var formData = new FormData($('#editTaskForm')[0]);
                formData.append("file", $('#file')[0].files[0]);
                formData.append("action", action);
                formData.append("task_id", id);
                formData.append("due_date", due_date);
                formData.append("complete_date", complete_date);
                formData.append("email", email);
                formData.append("comment", comment);
                formData.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    type: "POST",
                    url: "{{ route('lien.project.update.task') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (data) {
                        if (data.status) {
                            $('#editTask').modal('hide');
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Task Updated successfully',
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
            $('.deleteTask').on('click', function () {
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
                        url: "{{ route('project.delete.task') }}",
                        data: {
                            task_id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Task deleted successfully',
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
            //expand/collapse all
            $('.expandall-icon').click(function () {
                $(this).addClass('expand-inactive');
                $('.expandall-icon2').addClass('expand-inactive');
                $('.collapseall-icon').removeClass('collapse-inactive');
                $('.collapseall-icon2').removeClass('collapse-inactive');
                $('.collapse').addClass('in');
                $('.collapse').css('height','auto');
            });
            $('.collapseall-icon').click(function () {
                $(this).addClass('collapse-inactive');
                $('.collapseall-icon2').addClass('collapse-inactive');
                $('.expandall-icon').removeClass('expand-inactive');
                $('.expandall-icon2').removeClass('expand-inactive');
                $('.collapse').removeClass('in');
                $('.collapse').css('height','0px');
            }).addClass('collapse-inactive');
            $('.expandall-icon2').click(function () {
                $(this).addClass('expand-inactive');
                $('.expandall-icon').addClass('expand-inactive');
                $('.collapseall-icon2').removeClass('collapse-inactive');
                $('.collapseall-icon').removeClass('collapse-inactive');
                $('.collapse').addClass('in');
                $('.collapse').css('height','auto');
            });
            $('.collapseall-icon2').click(function () {
                $(this).addClass('collapse-inactive');
                $('.collapseall-icon').addClass('collapse-inactive');
                $('.expandall-icon2').removeClass('expand-inactive');
                $('.expandall-icon').removeClass('expand-inactive');
                $('.collapse').removeClass('in');
                $('.collapse').css('height','0px');
            }).addClass('collapse-inactive');

            $('.show_more').click(function () {
                var id = $(this).data('id');
                $(this).addClass('show-inactive');
                $('#customerLess'+id).removeClass('show-inactive');
                $('.customer'+id).show();
            });

            $('.show_less').click(function () {
                var id = $(this).data('id');
                $(this).addClass('show-inactive');
                $('#customerMore'+id).removeClass('show-inactive');
                $('.customer'+id).hide();
            }).addClass('show-inactive');
        });
    </script>
@endsection
