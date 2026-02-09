@extends('basicUser.projects.create')

@section('body')
    @include('basicUser.partials.multi-step-form')
    <div class="row">
        <span class="noProg"></span>
        <div class="form-horizontal project-form project_details create-project-form create-project-form--large"
            style="margin:0 auto;">
            <div class="create-project-form-header">
                <h2>View Project Tasks</h2>
            </div>
            <div class="row job-info-accordion--top-margin">
                @if (session()->has('deadline-error'))
                    <div class="alert alert-danger">
                        {!! session('deadline-error') !!}
                    </div>
                @endif
                @if (count($tasks) > 0)
                    <div class="row border-class">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-left">#</th>
                                            <th class="text-left">Action</th>
                                            <th class="text-left">Comments</th>
                                            <th class="text-left">Deadline</th>
                                            <th class="text-left">Date Completed</th>
                                            <th class="text-left">Email Alert</th>
                                            <th class="text-left">Action</th>
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
                                                    {{ \DateTime::createFromFormat('Y-m-d', $task->due_date)->format('m/d/Y') }}
                                                    <br />
                                                    <span style="color: red;">
                                                        {{ $diff > 0 ? 'You have ' . $exactDiff . ' remaining . ' : ' This deadline has passed' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $task->complete_date != '' ? \DateTime::createFromFormat('Y-m-d', $task->complete_date)->format('m/d/Y') : '' }}
                                                </td>
                                                <td>
                                                    {{ $task->email_alert == 0 ? 'Off' : 'On' }}
                                                </td>
                                                <td>
                                                    <button class="btn btn-xs btn-warning editButton" type="button"
                                                        data-id="{{ $task->id }}" data-action="{{ $task->task_name }}"
                                                        data-due_date="{{ \DateTime::createFromFormat('Y-m-d', $task->due_date)->format('d/m/Y') }}"
                                                        data-complete_date="{{ $task->complete_date != '' ? \DateTime::createFromFormat('Y-m-d', $task->complete_date)->format('d/m/Y') : '' }}"
                                                        data-email="{{ $task->email_alert }}"
                                                        data-other_comment="{{ $task->task_other }}"
                                                        data-comment="{{ $task->comment }}" data-toggle="modal">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-danger deleteTask"
                                                        data-id="{{ $task->id }}" type="button">
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
                        <h4 class="text-center">You currently have no tasks set up for this project.</h4>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12 project-tasks">
                        <form action="{{ route('project.add.task') }}" class="form-horizontal border-class"
                            method="post">
                            <h4 class="project-tasks-heading">Add A Task</h4>
                            <div class="contract-wrapper">
                                <label for="action">Action</label>
                                <select id="action" name="action" class="form-control">
                                    <option value="Call Customer">Call Customer</option>
                                    <option value="Follow Up Payment">Follow Up Payment</option>
                                    <option value="Prepare Waivers for Draw">Prepare Waivers for Draw</option>
                                    <option value="Prepare Credit Application">Prepare Credit Application</option>
                                    <option value="Prepare  Rider to Contract">Prepare Rider to Contract</option>
                                    <option value="Forward Claim To NLB">Forward Claim To NLB</option>
                                    @if (count($project_other) > 0)
                                        @foreach ($project_other as $po)
                                            <option value="{{$po}}">{{$po}}</option>
                                        @endforeach
                                    @endif
                                    <option value="Other">Add a New Task</option>
                                </select>
                                <div class="form-group" id="other_box" style="display: none">
                                    <label class=" col-md-12">Add a New Task</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" id="other_comment" name="other_comment"></textarea>
                                    </div>
                                </div>
                                <div class="project-tasks-input--half-wrap">
                                    <label for="due_date">Due Date</label>
                                    <input type="text" id="due_date" class="form-control date" name="due_date"
                                        data-provide="datepicker">
                                </div>
                                <div class="project-tasks-input--half-wrap">
                                    <label for="email_alert">Email Alert</label>
                                    <select name="email_alert" id="email_alert" class="form-control">
                                        <option value="0">Off</option>
                                        <option value="1">On</option>
                                    </select>
                                </div>
                                <label for="comment">Comments</label>
                                <textarea id="comment" class="form-control" name="comment"></textarea>
                            </div>
                            <input type="hidden" name="project_id" value="{{ $project_id }}">
                            <button type="submit" class="blue-primary-btn project-create-continue">Add Task</button>
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


                <div class="contract--wrapper">
                    <label>Waiver Amount</label>
                    <input class="form-control update_contract" id="wavier_amount" type="number" name="waiver_amount"
                        value="{{ isset($contract) && $contract != '' && $contract->waiver != '' ? $contract->waiver : '0' }}">
                </div>

                <div class="contract--wrapper">
                    <label>Receivable Status</label>
                    <select name="receivableStatus" id="receivable_status" class="form-control update_contract">
                        <option value="">
                            Select a receivable status
                        </option>
                        <option value="Preliminary Nupdate_contractotice"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Preliminary Notice' ? 'selected' : '' }}>
                            Preliminary Notice
                        </option>
                        <option value="Lien"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Lien' ? 'selected' : '' }}>
                            Lien
                        </option>
                        <option value="Bond"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Bond' ? 'selected' : '' }}>
                            Bond
                        </option>
                        <option value="Collection"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Collection' ? 'selected' : '' }}>
                            Collection
                        </option>
                        <option value="Litigation"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Litigation' ? 'selected' : '' }}>
                            Litigation
                        </option>
                        <option value="Bankruptcy"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Bankruptcy' ? 'selected' : '' }}>
                            Bankruptcy
                        </option>
                        <option value="Collected"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Collected' ? 'selected' : '' }}>
                            Collected
                        </option>
                        <option value="Paid"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Paid' ? 'selected' : '' }}>
                            Paid
                        </option>
                        <option value="Written Off"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Written Off' ? 'selected' : '' }}>
                            Written Off
                        </option>
                        <option value="Bankruptcy"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Bankruptcy' ? 'selected' : '' }}>
                            Bankruptcy
                        </option>
                        <option value="Settled"
                            {{ isset($contract) && $contract != '' && $contract->receivable_status == 'Settled' ? 'selected' : '' }}>
                            Settled
                        </option>
                    </select>
                </div>
                <div class="contract--wrapper">
                    <label>Deadline Calculation Status</label>
                    <select name="calculationStatus" id="calculation_status" class="form-control update_contract">
                        <option value="">Select a calculation status</option>
                        <option value="0"
                            {{ isset($contract) && $contract != '' && $contract->calculation_status == '0' ? 'selected' : '' }}>
                            In Process
                        </option>
                        <option value="1"
                            {{ isset($contract) && $contract != '' && $contract->calculation_status == '1' ? 'selected' : '' }}>
                            Complete
                        </option>
                    </select>
                </div>






                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('member.dashboard') }}"
                            class="blue-primary-btn project-create-continue project-create-continue--small">
                            Close
                        </a>
                        <a href="{{ route('create.deadlines', [$project_id]) . '?edit=true' }}"
                            class="blue-primary-btn project-create-continue project-create-continue--small">
                            View Deadlines
                        </a>
                    </div>
                </div>

                    <div class="flex items-center save-skip">
                        <a href="javascript:void(0)" id="skip-button-3" class="skip continue">
                            Skip
                        </a>
                        <a href="javascript:void(0)" id="continue" class="orange-btn continue">
                            Save & Continue
                        </a>
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
                                            <option value="Prepare Credit Application">Prepare Credit Application</option>
                                            <option value="Prepare  Rider to Contract">Prepare Rider to Contract</option>
                                            <option value="Forward Claim To NLB">Forward Claim To NLB</option>
                                            @if (count($project_other) > 0)
                                                @foreach ($project_other as $po)
                                                    <option value="{{$po}}">{{$po}}</option>
                                                @endforeach
                                            @endif
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
                            </form>
                        </div>
                    </div>
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
    @if (isset($_GET['create']))
        <script src="{{ env('ASSET_URL') }}/js/createProject.js"></script>
    @endif
    <script>
        $(document).ready(function() {

            let project_id = "{{ $project_id }}"

            $('.skip').on('click', function(event) {
                //event.stopPropagation();
                event.preventDefault();
                swal({
                    title: 'Are you sure you want to skip this?',
                    // text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    // buttonsStyling: false
                }).then(function() {
                    window.location.href = '/member/project/summary/view/' + project_id +
                        '?edit=true';
                })
            });

            $('#action').on('change', function() {
                console.log( this.value );
                if(this.value == 'Other') {
                    $('#other_box').show();
                } else {
                    $('#other_box').hide();
                }
            });

            $(document).on('click', '.continue', function(event) {
                event.preventDefault();

                window.location.href = "{{ route('project.summary.view', $project_id) . '?edit=true' }}";
            });

            $('.editButton').on('click', function() {
                var id = $(this).data('id');
                $('#editTask #task_id').val(id);
                var action = $(this).data('action');
                $('#editTask #action').val(action);
                var due_date = $(this).data('due_date');
                $('#editTask  #due_date').val(due_date);
                var email = $(this).data('email');
                $('#editTask  #email_alert').val(email);
                var comment = $(this).data('comment');
                $('#editTask  #comment').val(comment);
                var complete_date = $(this).data('complete_date');
                $('#editTask  #complete_date').val(complete_date);
                $('#editTask').modal('show');
            });
            $('.editTaskButton').on('click', function() {
                var action = $('#editTask #action').val();
                if (action == '') {
                    $('#editTask #action').addClass('input-error');
                    return false;
                } else {
                    $('#editTask #action').removeClass('input-error');
                }
                var id = $('#editTask #task_id').val();
                var due_date = $('#editTask #due_date').val();
                if (due_date == '') {
                    $('#editTask #due_date').addClass('input-error');
                    return false;
                } else {
                    $('#editTask #due_date').removeClass('input-error');
                }
                var complete_date = $('#editTask #complete_date').val();
                var email = $('#editTask #email_alert').val();
                var comment = $('#editTask #comment').val();
                // if(comment == ''){
                //     $('#comment').addClass('input-error');
                //     return false;
                // } else {
                //     $('#comment').removeClass('input-error');
                // }
                $('#editTask').modal('toggle');
                $.ajax({
                    type: "POST",
                    url: "{{ route('project.update.task') }}",
                    data: {
                        action: action,
                        task_id: id,
                        due_date: due_date,
                        complete_date: complete_date,
                        email: email,
                        comment: comment,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status) {
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Task Updated successfully',
                            }).then(function() {
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
            $('.deleteTask').on('click', function() {
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
                }).then(function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('project.delete.task') }}",
                        data: {
                            task_id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Task deleted successfully',
                                }).then(function() {
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

            $('.update_contract').on('change', function() {
                var waiver_amount = $('#wavier_amount').val();
                var receivable_status = $('#receivable_status').val();
                var calculation_status = $('#calculation_status').val();

                // console.log(wavier_amount);
                // console.log(receivable_status);
                // console.log(calculation_status);
                // console.log(project_id);

                $.ajax({
                    type: "POST",
                    url: "{{ route('project.contract.update') }}",
                    data: {
                        project_id: project_id,
                        waiver_amount: waiver_amount,
                        receivable_status: receivable_status,
                        calculation_status: calculation_status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status) {
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Contract Updated successfully',
                            }).then(function() {
                                // window.location.reload();
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
    </script>
@endsection
