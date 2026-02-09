<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Project Details')

@section('style')
    <style>
        .border-table {
            background: #fff;
            border: 1px solid #958283;
            border-radius: 10px;
            padding: 10px;
        }

        .border-table {
            margin-bottom: 20px;
        }

        .shadow {
            box-shadow: 1px 2px #535353;
        }

        .text-center {
            text-align: center;
            background: #1084ff;
            color: #fff;
            font-size: 24px;
            margin: 0;
            padding: 7px 19px;
            margin-bottom: 15px;
        }

    </style>
@endsection
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Project Details
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="border-table">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-md-offset-0">
                                            <div class="border-table">
                                                <h1 class="text-center"> Project Details</h1>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-md-offset-3">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Project Name :
                                                            </a>
                                                            <strong>{{ $project->project_name }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Project Location by State :
                                                            </a>
                                                            <strong>{{ $project->state->name }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Your Role :
                                                            </a>

                                                            @foreach ($roles as $role)
                                                                @if ($role->id == $project->role_id)
                                                                    <strong>{{ $role->project_roles }}</strong>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Project Type :
                                                            </a>

                                                            @foreach ($types as $type)
                                                                @if ($type->id == $project->project_type_id)
                                                                    <strong>{{ $type->project_type }}</strong>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Who Hired You :
                                                            </a>

                                                            @if (isset($project) && $project != '')
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
                            </div>
                            <div class="border-table">
                                <h1 class="text-center"> Project contracts</h1>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="border-table">
                                            <div class="table-responsive amount-table">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <td>Base Contract Amount $</td>
                                                            <th><input type="number" class="form-control" name="base_amount"
                                                                    id="base_amount"
                                                                    value="{{ isset($contract) && $contract != '' && $contract->base_amount != '' ? $contract->base_amount : '0' }}"
                                                                    disabled></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>+ Value of Extras of Changes $</td>
                                                            <th><input type="number" class="form-control"
                                                                    name="extra_amount" id="extra_amount"
                                                                    value="{{ isset($contract) && $contract != '' && $contract->extra_amount != '' ? $contract->extra_amount : '0' }}"
                                                                    disabled></th>
                                                        </tr>
                                                        <tr>
                                                            <td>= Revised Contract Subtotal $</td>
                                                            <th><input type="text" class="form-control" id="contact_total"
                                                                    disabled="disabled"></th>
                                                        </tr>
                                                        <tr>
                                                            <td>- Payments/Credits to Date $</td>
                                                            <th><input type="number" class="form-control" name="payment"
                                                                    id="payment"
                                                                    value="{{ isset($contract) && $contract != '' && $contract->credits != '' ? $contract->credits : '0' }}"
                                                                    disabled></th>
                                                        </tr>
                                                        <tr>
                                                            <td>= Total Claim Amount $</td>
                                                            <th><input type="text" class="form-control" id="claim_amount"
                                                                    name="claim_amount" disabled="disabled"></th>
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
                                                            <th>
                                                                <input type="number" name="waiver_amount"
                                                                    value="{{ isset($contract) && $contract != '' && $contract->waiver != '' ? $contract->waiver : '0' }}"
                                                                    disabled class="form-control">
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th>Receivable Status</th>
                                                            <td>
                                                                <strong>
                                                                    {{ isset($contract) && $contract != '' && $contract->receivable_status ? $contract->receivable_status : 'Not Available' }}
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Deadline Calculation Status</th>
                                                            <td>

                                                                <?php isset($contract) && $contract != '' && $contract->receivable_status ? ($var = $contract->calculation_status) : ($var = '4'); ?>
                                                                @if ($var == '0')
                                                                    <strong>In Process</strong>
                                                                @elseif($var == '1')
                                                                    Completed
                                                                @else
                                                                    Not Available
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
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-md-offset-0">
                                            <div class="border-table">
                                                <h1 class="text-center"> Project Dates</h1>
                                                <div class="row">
                                                    @foreach ($dates as $date)
                                                        <div class="col-md-10 col-md-offset-1">
                                                            <h4>{{ $date->date_name }} :
                                                                <strong>{{ isset($projectDates[$date->id]) ? date('m-d-Y', strtotime($projectDates[$date->id])) : 'Not Available' }}</strong>
                                                            </h4>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="border-table">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-md-offset-0">
                                                <h1 class="text-center"> Project Deadline</h1>
                                                <div class="col-md-12">
                                                    <div class="panel panel-info">

                                                        <div class="panel-body">
                                                            <ul class="nav nav-tabs">
                                                                @if (count($remedyNames) > 0)
                                                                    <li class="active"><a data-toggle="tab"
                                                                            href="#allRemedies">All
                                                                            Remedies</a></li>
                                                                    @foreach ($remedyNames as $key => $name)
                                                                        <li class=""><a data-toggle="tab"
                                                                                href="#{{ $key }}">{{ $name }}</a>
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
                                                                                <th>Date Legal Step Completed</th>
                                                                                <th>Email Alert</th>
                                                                            </tr>
                                                                            @if (count($deadlines) > 0)
                                                                                @foreach ($deadlines as $key => $deadline)
                                                                                    <tr>
                                                                                        <td>{{ $key + 1 }}</td>
                                                                                        <td>{{ $deadline->short_description }}
                                                                                            <span
                                                                                                class="tag label label-info">{{ $deadlines[$key]->getRemedy->remedy }}</span>
                                                                                        </td>
                                                                                        <td>{{ $daysRemain[$key]['dates'] }}
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ date('m-d-Y', strtotime($daysRemain[$key]['preliminaryDates'])) }}
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ $deadlines[$key]['legal_completion_date'] != '' ? date('m-d-Y', strtotime($deadlines[$key]['legal_completion_date'])) : 'Not Available' }}
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ $deadlines[$key]['email_alert'] == 1 ? 'On' : 'Off' }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            @else
                                                                                <tr>
                                                                                    <td colspan="5">
                                                                                        No Deadline found...
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                @if (count($remedyNames) > 0)
                                                                    @foreach ($remedyNames as $remedyKey => $name)
                                                                        <div id="{{ $remedyKey }}"
                                                                            class="tab-pane fade">
                                                                            <table class="table table-striped">
                                                                                <tr>
                                                                                    <th>Legal Steps</th>
                                                                                    <th>Days Remaining</th>
                                                                                    <th>Preliminary Deadline</th>
                                                                                    <th>Date Legal Step Completed</th>
                                                                                    <th>Email Alert</th>
                                                                                </tr>
                                                                                @if (count($deadlines) > 0)
                                                                                    @foreach ($deadlines as $key => $deadline)
                                                                                        @if ($remedyKey == $deadlines[$key]['remedy_id'])
                                                                                            <tr>
                                                                                                <td>{{ $deadline->short_description }}
                                                                                                </td>
                                                                                                <td>{{ $daysRemain[$key]['dates'] }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ date('m-d-Y', strtotime($daysRemain[$key]['preliminaryDates'])) }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ $deadlines[$key]['legal_completion_date'] != '' ? date('m-d-Y', strtotime($deadlines[$key]['legal_completion_date'])) : 'Not Available' }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ $deadlines[$key]['email_alert'] == 1 ? 'On' : 'Off' }}
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!--     <div class="col-md-12 well text-center"> -->
                                <div class="col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-md-offset-0">
                                            <div class="border-table">
                                                <h1 class="text-center">Project Documents</h1>
                                                <hr class="divider">
                                                <form action="#" method="post"
                                                    class="form-horizontal project-form project_documents">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Document Date</th>
                                                                <th>Document Type</th>
                                                                <th>View</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>N/A</td>
                                                                <td>Lien Law Summary</td>
                                                                <td>
                                                                    <button id="lianLawSummery" type="button"
                                                                        class="form-control btn blue-btn btn-success">
                                                                        View
                                                                    </button>
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
                                                                                    <a href="{{ route('get.documentClaimView', [$project_id, $flag]) }}"
                                                                                        class="form-control btn blue-btn btn-success">View</a>
                                                                                </td>
                                                                            @elseif($document['name'] == 'Credit
                                                                                Application')
                                                                                <td>
                                                                                    <a href="{{ route('get.documentCreditView', [$project_id, $flag]) }}"
                                                                                        class="form-control btn blue-btn btn-success">View</a>
                                                                                </td>
                                                                            @elseif($document['name'] == 'joint payment
                                                                                authorization')
                                                                                <td>
                                                                                    <a href="{{ route('get.documentJointView', [$project_id, $flag]) }}"
                                                                                        class="form-control btn blue-btn btn-success">View</a>
                                                                                </td>
                                                                            @elseif($document['name'] == 'Waver
                                                                                progress')
                                                                                <td>
                                                                                    <a href="{{ route('get.documentWaverView', [$project_id, $flag]) }}"
                                                                                        class="form-control btn blue-btn btn-success">View</a>
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
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-md-13 col-sm-13  col-md-offset-0">
                                        <div class="border-table">
                                            <h1 class="text-center">Project Tasks</h1>
                                            <hr class="divider">
                                            @if (count($tasks) > 0)
                                                <div class="row border-class">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Action</th>
                                                                        <th>Comments</th>
                                                                        <th>Deadline</th>
                                                                        <th>Date Completed</th>
                                                                        <th>Email Alert</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($tasks as $key => $task)
                                                                        @php
                                                                            $date1 = new DateTime($task->due_date);
                                                                            $date2 = new DateTime(date('Y-m-d'));
                                                                            $diff = $date2->diff($date1)->format('%R%a days');
                                                                            $exactDiff = $date2->diff($date1)->format('%a days');
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $key + 1 }}</td>
                                                                            <td>{{ $task->task_name }}</td>
                                                                            <td>{{ $task->comment }}</td>
                                                                            <td>
                                                                                {{ \DateTime::createFromFormat('Y-m-d', $task->due_date)->format('m-d-Y') }}
                                                                                <br />
                                                                                <span style="color: red;">
                                                                                    {{ $diff > 0 ? $exactDiff . ' remaining.' : 'This deadline has passed' }}
                                                                                </span>
                                                                            </td>
                                                                            <td>{{ $task->complete_date != '' ? \DateTime::createFromFormat('Y-m-d', $task->complete_date)->format('m-d-Y') : '' }}
                                                                            </td>
                                                                            <td>{{ $task->email_alert == 0 ? 'Off' : 'On' }}
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
                                                    <h4>Currently no tasks set up for this project.</h4>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var base_amount = 0;
            var extra_amount = 0;
            var payment = 0;
            var total = 0;
            var claim_total = 0;
            totalAmount();

            $('#base_amount,#extra_amount,#payment').on('change', function() {
                totalAmount();
            });

            function totalAmount() {
                base_amount = $('#base_amount').val();
                extra_amount = $('#extra_amount').val();
                payment = $('#payment').val();
                total = parseFloat(base_amount) + parseFloat(extra_amount);
                claim_total = parseFloat(total) - parseFloat(payment);
                $('#contact_total').val(total);
                $('#claim_amount').val(claim_total);
            }
        });
    </script>
@endsection
