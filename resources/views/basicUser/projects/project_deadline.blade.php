@extends('basicUser.layout.main')

@section('title', 'Project Deadlines')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h4>Review Your Project Deadlines</h4>
                            </div>
                        </div>
                    </div>
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
                                            <th>Date Legal Step Completed</th>
                                            <th>Email Alert</th>
                                        </tr>
                                        @if (count($deadlines) > 0)
                                            @foreach ($deadlines as $key => $deadline)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $deadline->short_description }} <span
                                                            class="tag label label-info">{{ $deadlines[$key]->getRemedy->remedy }}</span>
                                                    </td>
                                                    <td>{{ $daysRemain[$key]['dates'] }}</td>
                                                    <td>
                                                        {{ $daysRemain[$key]['preliminaryDates'] }}
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control date" name="date-legal[]"
                                                            data-provide="datepicker"
                                                            value="{{ $deadlines[$key]['legal_completion_date'] }}">
                                                    </td>
                                                    @if ($check_alert == 1)
                                                        <td>
                                                            <select name="email-alert[]"
                                                                onchange="getComboA(this,{{ $deadline->id }})">
                                                                <option value="1"
                                                                    {{ $deadlines[$key]['email_alert'] == 1 ? 'selected' : '' }}>
                                                                    On
                                                                </option>
                                                                <option value="0"
                                                                    {{ $deadlines[$key]['email_alert'] == 0 ? 'selected' : '' }}>
                                                                    Off
                                                                </option>
                                                            </select>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <select name="email-alert[]"
                                                                onchange="getComboA(this,{{ $deadline->id }})">
                                                                <option value="1"
                                                                    {{ $deadline_data[$key]->status == 1 ? 'selected' : '' }}>
                                                                    On
                                                                </option>
                                                                <option value="0"
                                                                    {{ $deadline_data[$key]->status == 0 ? 'selected' : '' }}>
                                                                    Off
                                                                </option>
                                                            </select>
                                                        </td>
                                                    @endif

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
                                                <th>Date Legal Step Completed</th>
                                                <th>Email Alert</th>
                                            </tr>
                                            @if (count($deadlines) > 0)
                                                @foreach ($deadlines as $key => $deadline)
                                                    @if ($remedyKey == $deadlines[$key]['remedy_id'])
                                                        <tr>
                                                            <td>{{ $deadline->short_description }}</td>
                                                            <td>{{ $daysRemain[$key]['dates'] }}</td>
                                                            <td>
                                                                {{ $daysRemain[$key]['preliminaryDates'] }}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control date"
                                                                    name="date-legal[]" data-provide="datepicker"
                                                                    value="{{ $deadlines[$key]['legal_completion_date'] }}">
                                                            </td>
                                                            <td>
                                                                <select name="email-alert[]">
                                                                    <option value="1"
                                                                        {{ $deadlines[$key]['email_alert'] == 1 ? 'selected' : '' }}>
                                                                        On
                                                                    </option>
                                                                    <option value="0"
                                                                        {{ $deadlines[$key]['email_alert'] == 0 ? 'selected' : '' }}>
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
                </div>
            </div>
        </div>
        <div class="row">
            <form class="form-add-emails" action="{{ route('project.add.emails', $project_id) }}" method="post">
                {{ csrf_field() }}
                <div class="col-md-12">
                    <h4>Email Reminders</h4>
                    <div class="col-md-6">
                        <p>Preliminary Deadline Reminders will be sent to:</p>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li>{{ $user }}</li>
                            @if (count($project_emails) > 0)
                                @foreach ($project_emails as $key => $email)
                                    <li>
                                        {{ $project_emails[$key]->project_emails }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <table id="TextBoxContainer"></table>
                        <p>Add Another Email Recipient:</p>
                        <button id="btnAdd" type="button" class="btn btn-primary" data-toggle="tooltip"
                            data-original-title="Add more recipient">
                            <i class="glyphicon glyphicon-plus-sign"></i>
                        </button>
                    </div>
                    <div class="row text-center">
                        <p>
                            After adding email recipients click “Save Project”, or additional recipients will not be saved.
                        </p>
                        <input type="submit" class="btn btn-success" name="saveEmailRecipient" id="saveEmailRecipient"
                            value="Save Project">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('html, body').animate({
                scrollTop: $('#progressbar').offset().top
            }, 'slow');
        });
        $(function() {
            $("#btnAdd").on("click", function() {
                var div = $("<tr />");
                div.html(GetDynamicTextBox(""));
                $("#TextBoxContainer").append(div);
            });
            $("body").on("click", "#btnAdd", function() {
                if ($(".emails").val() != "") {
                    $('#saveEmailRecipient').removeAttr('disabled');
                }
            });
            $("body").on("click", ".remove", function() {
                $(this).closest("tr").remove();
                if (($(".emails").length) == 0) {
                    // $('#saveEmailRecipient').attr('disabled', 'disabled');
                }

            });
            $("body").on("blur", ".emails", function() {
                if ($(".emails").val() != "") {
                    $('#saveEmailRecipient').removeAttr('disabled');
                } else {
                    $('#saveEmailRecipient').attr('disabled', 'disabled');
                }
            });

        });

        function getComboA(selectObject, data) {

            var alert = selectObject.value;
            var project_id = <?php echo $project_id; ?>;
            var id = data;
            //
            // console.log(id);
            $.ajax({
                type: "POST",
                url: "{{ route('deadline.email.check') }}",
                data: {
                    alert: alert,
                    project_id: project_id,
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    if (data.status) {
                        console.log('success');
                        swal(
                            'Done',
                            data.message,
                            'success'
                        )

                    } else {
                        swal(
                            'ALERT',
                            data.message,
                            'error'
                        )
                    }
                }
            });
        }

        function GetDynamicTextBox(value) {
            return '<td style="padding: .3em;"><input name ="emailRecipient[]" type="email" value = "' + value +
                '" required class="form-control emails" /></td>' +
                '<td><button type="button" class="btn btn-danger remove"><i class="glyphicon glyphicon-remove-sign"></i></button></td>'
        }
    </script>
@endsection
