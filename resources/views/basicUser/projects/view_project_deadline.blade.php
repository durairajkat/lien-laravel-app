@extends('basicUser.projects.create')

@section('body')
    <span id="stepNumDetailed" data-step="4"></span>
    <form action="#" method="post"
        class="form-horizontal project-form project_details create-project-form create-project-form--large">
        <div class="create-project-form-header">
            <h2>Review Preliminary Deadlines</h2>
        </div>
        <div class="contract--wrapper contract--wrapper--wide">
            <div class="container col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            @if (count($remedyNames) > 0)
                                <li class="active"><a data-toggle="tab" href="#allRemedies">All Remedies</a></li>
                                @foreach ($remedyNames as $key => $name)
                                    <li class=""><a data-toggle="tab" href="#{{ $key }}">{{ $name }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div id="allRemedies" class="tab-pane fade in active">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>#</th>
                                            <th>Legal Steps</th>
                                            <th>Days Remaining</th>
                                            <th>Preliminary Deadline</th>
                                        </tr>
                                        @if (count($deadlines) > 0)
                                            @foreach ($deadlines as $key => $deadline)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td align="left">{{ $deadline->short_description }} <span
                                                            class="tag label label-info">{{ $deadlines[$key]->getRemedy->remedy }}</span>
                                                    </td>
                                                    <td>{{ $daysRemain[$key]['dates'] }}</td>
                                                    <td>
                                                        {{ $daysRemain[$key]['preliminaryDates'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5"> No Deadline found...</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            @if (count($remedyNames) > 0)
                                @foreach ($remedyNames as $remedyKey => $name)
                                    <div id="{{ $remedyKey }}" class="tab-pane fade">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Legal Steps</th>
                                                <th>Days Remaining</th>
                                                <th>Preliminary Deadline</th>
                                            </tr>
                                            @if (count($deadlines) > 0)
                                                @foreach ($deadlines as $key => $deadline)
                                                    @if ($remedyKey == $deadlines[$key]['remedy_id'])
                                                        <tr>
                                                            <td align="left">{{ $deadline->short_description }}</td>
                                                            <td>{{ $daysRemain[$key]['dates'] }}</td>
                                                            <td>
                                                                {{ $daysRemain[$key]['preliminaryDates'] }}
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
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <a href="" type="button" id="activate-step-5" class="project-create-continue create-button--contract">
            Save & Continue
        </a>
    </form>

@endsection

@section('script')
    <script src="{{ env('ASSET_URL') }}/js/createProject.js"></script>
    <script>
        $(document).ready(function() {
            $('#activate-step-5').on('click', function() {
                window.location.href =
                    "{{ route('project.document.view') }}?project_id={{ $project_id }}&view={{ $tab_view }}";

            });
        });
    </script>
@endsection
