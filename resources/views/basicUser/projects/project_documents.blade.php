@extends('basicUser.projects.create')

@section('body')
    <div class="container">
        <br>
        <div class="row setup-content" id="step-4">
            <div class="col-xs-10">
                <div class="col-md-12 well text-center">
                    @if (Session::has('doc-success'))
                        <p class="alert alert-success">{{ Session::get('doc-success') }}</p>
                    @endif
                    <h1 class="text-center">Project Documents</h1>
                    <hr class="divider">
                    <form action="#" method="post" class="form-horizontal project-form project_documents">
                        <div class="form-group">
                            <label class="col-md-2">Create Documents</label>
                            <div class="col-md-6">
                                <select id="DocumentType" class="form-control" name="DocumentType">
                                    <option value="">Select a Document Type</option>
                                    <option value="JobInfo">Job Info Sheet</option>
                                    <option value="ClaimData">Claim Form and Project Data Sheet</option>
                                    <option value="CreditApplication">Credit Application</option>
                                    <option value="JointPaymentAuthorization">Joint Payment
                                        Authorization
                                    </option>
                                    <optgroup label="Waiver of Lien Forms">
                                        <option value="UnconditionalWaiverProgress">Unconditional Waiver
                                            and Release Upon Progress Payment
                                        </option>
                                        <option value="ConditionalWaiver">Conditional Waiver and Release
                                            Upon Progress Payment
                                        </option>
                                        <option value="ConditionalWaiverFinal">Conditional Waiver and
                                            Release Upon Final Payment
                                        </option>
                                        <option value="UnconditionalWaiverFinal">Unconditional Waiver
                                            and Release Upon Final Payment
                                        </option>
                                        <option value="PartialWaiver">Partial Waiver of Lien (For an
                                            Amount Only)
                                        </option>
                                        <option value="PartialWaiverDate">Partial Waiver of Lien (To
                                            Date Only)
                                        </option>
                                        <option value="WaiverOfLien">Final Waiver of Lien (Standard)
                                        </option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button class="blue-btn" id="createDocument" type="button" disabled>Create
                                    document
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Existing Documents</label>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Document Date</th>
                                    <th class="text-center">Document Type</th>
                                    <th class="text-center">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>N/A</td>
                                    <td>Lien Law Summary</td>
                                    <td>
                                        <div class="col-md-12">
                                            <button id="lianLawSummery" type="button"
                                                class="form-control btn blue-btn btn-success">View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @if (!empty($project_documents))
                                    @foreach ($project_documents as $document)
                                        @if ($document['data'])
                                            <tr>

                                                <td>{{ date('F d, Y h:i:s A', strtotime(isset($document['data']) ? $document['data']->created_at : '')) }}
                                                </td>
                                                {{-- @if ($document['name']) --}}
                                                <td>{{ $document['name'] }}</td>
                                                {{-- @endif --}}

                                                @if ($document['name'] == 'Claim form and project data sheet')
                                                    <td>
                                                        <div class="col-md-12">
                                                            {{-- <a href="{{ route('get.documentClaimView',[$project_id,$flag]) }}"
                                                       class="form-control btn blue-btn btn-success" target="_blank">View</a> --}}
                                                            <a href="{{ route('admin.new.claim_step1', ['project_id' => $project_id]) }}"
                                                                class="form-control btn blue-btn btn-success"
                                                                target="_blank">View</a>
                                                        </div>
                                                    </td>
                                                @elseif($document['name'] == 'Credit Application')
                                                    <td>
                                                        <div class="col-md-12">
                                                            <a href="{{ route('get.documentCreditView', [$project_id, $flag]) }}"
                                                                class="form-control btn blue-btn btn-success"
                                                                target="_blank">View</a>
                                                        </div>
                                                    </td>
                                                @elseif($document['name'] == 'joint payment authorization')
                                                    <td>
                                                        <div class="col-md-12">
                                                            <a href="{{ route('get.documentJointView', [$project_id, $flag]) }}"
                                                                class="form-control btn blue-btn btn-success"
                                                                target="_blank">View</a>
                                                        </div>
                                                    </td>
                                                @elseif($document['name'] == 'Waver progress')
                                                    <td>
                                                        <div class="col-md-12">
                                                            <a href="{{ route('get.documentWaverView', [$project_id, $flag]) }}"
                                                                class="form-control btn blue-btn btn-success"
                                                                target="_blank">View</a>
                                                        </div>
                                                    </td>
                                                @elseif($document['name'] == 'job info sheet')
                                                    <td>
                                                        <div class="col-md-12">
                                                            <div class="col-md-6">
                                                                <a href="{{ route('get.job.info.sheet', [$project_id]) }}"
                                                                    class="form-control btn blue-btn btn-success"
                                                                    target="_blank">View</a>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <a href="{{ route('get.jobInfoExport', [$project_id]) }}"
                                                                    class="form-control btn btn-info" target="_blank">Export
                                                                    as PDF</a>
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
                    <button id="activate-step-5" type="button" class="blue-btn pull-right">
                        Save & Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#activate-step-5').on('click', function(e) {
                window.location.href =
                    "{{ route('project.task.view') }}?project_id={{ $project_id }}&view={{ $tab_view }}";

            });
            $('#lianLawSummery').on('click', function() {
                url =
                    "{{ route('get.lineBoundSummery', [$project->state_id, $project->project_type_id]) }}";
                window.open(url, '_blank');
            });
            $('#DocumentType').on('change', function() {
                var selected = $(this).val();
                if (selected != '') {
                    $('#createDocument').removeAttr('disabled');
                } else {
                    $('#createDocument').attr('disabled', 'disabled');
                }
            });
            $('#createDocument').on('click', function() {
                var document = $('#DocumentType').val();
                var url = '';
                if (document == 'ClaimData') {
                    url = '{{ route('admin.new.claim_step1', ['project_id' => $project_id]) }}';
                } else if (document == 'CreditApplication') {
                    url = '{{ route('get.creditApplication', [$project_id]) }}';
                } else if (document == 'JointPaymentAuthorization') {
                    url = '{{ route('get.jointPaymentAuthorization', [$project_id]) }}';
                } else if (document == 'UnconditionalWaiverProgress') {
                    url = '{{ route('get.documentUnconditionalWaiverRelease', [$project_id]) }}';
                } else if (document == 'ConditionalWaiver') {
                    url = '{{ route('get.documentConditionalWaiver', [$project_id]) }}';
                } else if (document == 'ConditionalWaiverFinal') {
                    url = '{{ route('get.documentConditionalWaiverFinal', [$project_id]) }}';
                } else if (document == 'UnconditionalWaiverFinal') {
                    url = '{{ route('get.documentUnconditionalWaiverFinal', [$project_id]) }}';
                } else if (document == 'PartialWaiver') {
                    url = '{{ route('get.documentPartialWaiver', [$project_id]) }}';
                } else if (document == 'PartialWaiverDate') {
                    url = '{{ route('get.documentPartialWaiverDate', [$project_id]) }}';
                } else if (document == 'WaiverOfLien') {
                    url = '{{ route('get.documentStandardWaiverFinal', [$project_id]) }}';
                } else if (document == 'JobInfo') {
                    url = '{{ route('get.job.info.sheet', [$project_id]) }}';
                } else {
                    url = '{{ route('member.dashboard') }}';
                }
                window.open(url, '_blank');
            });
        });
    </script>
@endsection
