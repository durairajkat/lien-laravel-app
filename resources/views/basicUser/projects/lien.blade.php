@extends('basicUser.projects.create')

@section('body')
    <div class="container">
        <br>
        <div class="row setup-content" id="step-3">
            <div class="col-md-12">
                <div>
                    <h1 class="text-center">Review Lien Slide Chart Remedies</h1>
                    <hr class="divider">
                    <div class="box">
                        <div class="head-top">
                            {{ $project->state->name }}
                            <div class="pull-right">
                                <button class="btn btn-success expandall-icon"><i class="fa fa-plus fa-2x"></i> </button>
                                <button class="btn btn-warning collapseall-icon"><i class="fa fa-minus fa-2x"></i> </button>
                            </div>
                        </div>
                        <div class="head-top2">{{ $project->project_type->project_type }}</div>
                        <div class="panel-group" id="accordion">
                            @if (count($remedyNames) > 0)
                                @foreach ($remedyNames as $remedyKey => $name)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion"
                                                    href="#{{ $remedyKey }}">{{ $name }}</a>
                                            </h4>
                                        </div>
                                        @if (count($liens) > 0)
                                            @foreach ($liens as $key => $lien)
                                                @if ($name == $lien['remedy'])
                                                    <div id="{{ $key }}" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <div class="panel-main">
                                                                <p style="font-size: medium;"><strong>Description:</strong>
                                                                    {{ $lien->description }}</p>
                                                                <p style="font-size: medium;"><strong>Tiers:</strong>
                                                                    {{ $lien->tier_limit }}</p>
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
                                        <a data-toggle="collapse" data-parent="#accordion" href="#jobDescription">Job
                                            Description (<small><strong>{{ $project->project_name }}</strong></small>)</a>
                                    </h4>
                                </div>
                                <div id="jobDescription" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="panel-main">
                                            <div class="col-md-12">
                                                <div align="left">
                                                    <p>Job Name : <strong>{{ $project->project_name }}</strong></p>
                                                    <p>Job Address :
                                                        <strong>{{ $project->address != '' ? $project->address : 'N/A' }}</strong>
                                                    </p>
                                                    <p>Job City :
                                                        <strong>{{ $project->city != '' ? $project->city : 'N/A' }}</strong>
                                                    </p>
                                                    <p>Job State : <strong>{{ $project->state->name }}</strong></p>
                                                    <p>Job Zip :
                                                        <strong>{{ $project->zip != '' ? $project->zip : 'N/A' }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (!is_null($project->customer_contract))
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#customer">Customer -
                                                <small><strong>{{ !is_null($project->customer_contract->company) ? $project->customer_contract->company->company : 'N/A' }}</strong></small></a>
                                        </h4>
                                    </div>
                                    <div id="customer" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="panel-main">
                                                <div class="col-md-12">
                                                    <div class="col-md-5">
                                                        <div align="left">
                                                            <p>Company Name:
                                                                <strong>{{ !is_null($project->customer_contract->company) ? $project->customer_contract->company->company : 'N/A' }}</strong>
                                                            </p>
                                                            <p>Address :
                                                                <strong>{{ $project->customer_contract->address != '' ? $project->customer_contract->address : 'N/A' }}</strong>
                                                            </p>
                                                            <p>City :
                                                                <strong>{{ $project->customer_contract->city != '' ? $project->customer_contract->city : 'N/A' }}</strong>,
                                                                State :
                                                                <strong>{{ !is_null($project->customer_contract) && !is_null($project->customer_contract->state) ? $project->customer_contract->state : 'N/A' }}</strong>,
                                                                Zip :
                                                                <strong>{{ !is_null($project->customer_contract) && $project->customer_contract->zip != '' ? $project->customer_contract->zip : 'N/A' }}</strong>
                                                            </p>
                                                            <p>Telephone :
                                                                <strong>{{ $project->customer_contract->phone != '' ? $project->customer_contract->phone : 'N/A' }}</strong>
                                                            </p>
                                                            <p>Fax :
                                                                <strong>{{ $project->customer_contract->fax != '' ? $project->customer_contract->fax : 'N/A' }}</strong>
                                                            </p>
                                                            <p>Web :
                                                                <strong>{{ $project->customer_contract->website != '' ? $project->customer_contract->website : 'N/A' }}</strong>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        &nbsp;
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div align="right">
                                                            <p>
                                                                <input type="button" value="Add/Edit Contact"
                                                                    class="btn btn-primary editContact"
                                                                    data-contact="customer"
                                                                    data-id="{{ $project->customer_contract ? $project->customer_contract->id : '' }}"
                                                                    data-map_id="{{ $project->customer_contract ? $project->customer_contract->id : '' }}"
                                                                    data-type="edit" data-contactType="N/A"
                                                                    data-customer_id="{{ $project->customer_contract ? $project->customer_contract->company_contact_id : '' }}"
                                                                    data-company_id="{{ $project->customer_contract ? $project->customer_contract->company_id : '' }}">
                                                            </p>

                                                            <p>First :
                                                                <strong>{{ !is_null($project->customer_contract->contacts) && $project->customer_contract->contacts->first_name != '' ? $project->customer_contract->contacts->first_name : 'N/A' }}</strong>,
                                                                Last :
                                                                <strong>{{ !is_null($project->customer_contract->contacts) && $project->customer_contract->contacts->last_name != '' ? $project->customer_contract->contacts->last_name : 'N/A' }}</strong>,
                                                                Title :
                                                                <strong>{{ !is_null($project->customer_contract->contacts) && $project->customer_contract->contacts->title != '' ? (!is_null($project->customer_contract->contacts) && $project->customer_contract->contacts->title == 'Other' ? $project->customer_contract->contacts->title_other : $project->customer_contract->contacts->title) : 'N/A' }}</strong>
                                                            </p>
                                                            <p>Direct Phone :
                                                                <strong>{{ !is_null($project->customer_contract->contacts) && !empty($project->customer_contract->contacts->phone) ? $project->customer_contract->contacts->phone : 'N/A' }}</strong>
                                                            </p>
                                                            <p>Cell Phone :
                                                                <strong>{{ !is_null($project->customer_contract->contacts) && !empty($project->customer_contract->contacts->cell) ? $project->customer_contract->contacts->cell : 'N/A' }}</strong>
                                                            </p>
                                                            <p>Email :
                                                                <strong>{{ !is_null($project->customer_contract->contacts) && !empty($project->customer_contract->contacts->email) ? $project->customer_contract->contacts->email : 'N/A' }}</strong>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (count($projectContacts) > 0)
                                @foreach ($projectContacts as $key => $contact)
                                    @php
                                        $customerDetails = \App\Models\CompanyContact::find($contact['customer_id']);
                                        $mapDetails = $customerDetails->mapContactCompany;
                                    @endphp
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion"
                                                    href="#industry{{ $key }}">{{ $contact['type'] }} -
                                                    <small><strong>{{ $contact['company']->company }}</strong></small></a>
                                            </h4>
                                        </div>
                                        <div id="industry{{ $key }}" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="panel-main">
                                                    <div class="col-md-12">
                                                        <div class="col-md-5">
                                                            <div align="left">
                                                                <p>Company Name:
                                                                    <strong>{{ $contact['company']->company }}</strong>
                                                                </p>
                                                                <p>Address :
                                                                    <strong>{{ $contact['company']->address != '' ? $contact['company']->address : 'N/A' }}</strong>
                                                                </p>
                                                                <p>City :
                                                                    <strong>{{ $contact['company']->city != '' ? $contact['company']->city : 'N/A' }}</strong>,
                                                                    State :
                                                                    <strong>{{ $contact['company']->state->name }}</strong>,
                                                                    Zip :
                                                                    <strong>{{ $contact['company']->zip != '' ? $contact['company']->zip : 'N/A' }}</strong>
                                                                </p>
                                                                <p>Telephone :
                                                                    <strong>{{ $contact['company']->phone != '' ? $contact['company']->phone : 'N/A' }}</strong>
                                                                </p>
                                                                <p>Fax :
                                                                    <strong>{{ $contact['company']->fax != '' ? $contact['company']->fax : 'N/A' }}</strong>
                                                                </p>
                                                                <p>Web :
                                                                    <strong>{{ $contact['company']->website != '' ? $contact['company']->website : 'N/A' }}</strong>
                                                                </p>
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
                                                                </p> --}}
                                                                @if (count($contact['customers']) > 0)
                                                                    @foreach ($contact['customers'] as $industryKey => $contactInformationIns)
                                                                        @if ($industryKey == 0)
                                                                            <p>
                                                                                <input type="button"
                                                                                    value="Add/Edit Contact"
                                                                                    class="btn btn-primary editContact"
                                                                                    data-contact="industry"
                                                                                    data-id="{{ $contact['company']->id }}"
                                                                                    data-map_id="{{ !is_null($contactInformationIns->mapContactCompany) ? $contactInformationIns->mapContactCompany->id : 0 }}"
                                                                                    data-type="edit"
                                                                                    data-contactType="{{ $contactInformationIns->customer_type }}"
                                                                                    data-customer_id="{{ $contactInformationIns->id }}"
                                                                                    data-company_id="{{ $contact['company_id'] }}">
                                                                            </p>
                                                                            <p>First :
                                                                                <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A' }}</strong>,
                                                                                Last :
                                                                                <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>,
                                                                                Title :
                                                                                <strong>{{ $contactInformationIns->title != '' ? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong>
                                                                            </p>
                                                                            <p>Direct Phone :
                                                                                <strong>{{ $contactInformationIns->phone }}</strong>
                                                                            </p>
                                                                            <p>Cell Phone :
                                                                                <strong>{{ $contactInformationIns->cell }}</strong>
                                                                            </p>
                                                                            <p>Email :
                                                                                <strong>{{ $contactInformationIns->email }}</strong>
                                                                            </p>
                                                                            @if (count($contact['customers']) > 1)
                                                                                <p>
                                                                                    <a href="javascript:void(0)"
                                                                                        class="show_more"
                                                                                        id="customerMore{{ $key }}"
                                                                                        data-id="{{ $key }}">Show
                                                                                        More</a>
                                                                                    <a href="javascript:void(0)"
                                                                                        class="show_less"
                                                                                        id="customerLess{{ $key }}"
                                                                                        data-id="{{ $key }}">Show
                                                                                        Less</a>
                                                                                </p>
                                                                            @endif
                                                                        @else
                                                                            <div class="customer{{ $key }}"
                                                                                style="display: none;">
                                                                                <p>
                                                                                    <input type="button"
                                                                                        value="Add/Edit Contact"
                                                                                        class="btn btn-primary editContact"
                                                                                        data-contact="industry"
                                                                                        data-id="{{ $contact['company']->id }}"
                                                                                        data-map_id="{{ !is_null($contactInformationIns->mapContactCompany) ? $contactInformationIns->mapContactCompany->id : 0 }}"
                                                                                        data-type="edit"
                                                                                        data-contactType="{{ $contactInformationIns->customer_type }}"
                                                                                        data-customer_id="{{ $contactInformationIns->id }}"
                                                                                        data-company_id="{{ $contact['company_id'] }}">
                                                                                </p>
                                                                                <p>First :
                                                                                    <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A' }}</strong>,
                                                                                    Last :
                                                                                    <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>,
                                                                                    Title :
                                                                                    <strong>{{ $contactInformationIns->title != '' ? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong>
                                                                                </p>
                                                                                <p>Direct Phone :
                                                                                    <strong>{{ $contactInformationIns->phone }}</strong>
                                                                                </p>
                                                                                <p>Cell Phone :
                                                                                    <strong>{{ $contactInformationIns->cell }}</strong>
                                                                                </p>
                                                                                <p>Email :
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
                                @endforeach
                            @endif
                            <div class="head-top">
                                &nbsp;
                                <div class="pull-right">
                                    <button class="btn btn-success expandall-icon2"><i class="fa fa-plus fa-2x"></i>
                                    </button>
                                    <button class="btn btn-warning collapseall-icon2"><i class="fa fa-minus fa-2x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-main">
                            <button type="button" id="activate-step-5" class="blue-btn pull-right">
                                Save & Continue
                            </button>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('basicUser.modals.contact_modal',['companies' => $companies,'first_names' => $first_names])
@endsection

@section('script')
    <script>
        var nextUrl = '{{ route('project.document.view') . '?project_id=' . $_GET['project_id'] }}&view=detailed';
        var jobInfo = '{{ route('get.job.info.sheet', [$project_id]) }}';
        var token = '{{ csrf_token() }}';
        var addFileUrl = "{{ route('add.job.info.file') }}";
        var baseUrl = "{{ env('ASSET_URL') }}";
        var customerContactRoute = "{{ route('customer.submit.contact') }}";
        var project_id = "{{ $project->id }}";
        var user_id = "{{ Auth::user()->id }}";
        var autoComplete = "{{ route('autocomplete.contact.company') }}";
        var autoCompleteCompany = "{{ route('autocomplete.company') }}";
        var autoCompleteContact = "{{ route('autocomplete.contact.firstname') }}";
        var editJobDescriptionRoute = "{{ route('edit.job.description') }}";
        var getContactData = "{{ route('get.contact.data') }}";
        var newContacts = "{{ route('create.new.contacts') }}";
        var url = '{{ env('ASSET_URL') }}';
    </script>
    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/lien.js"></script>
@endsection
