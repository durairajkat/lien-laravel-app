<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Job request Edit')

@section('style')
    <style>
        .form-box {
            padding-top: 40px;
            font-family: 'Roboto', sans-serif !important;
        }

        .form-top {
            overflow: hidden;
            padding: 0 25px 15px 25px;
            background: #26A69A;
            -moz-border-radius: 4px 4px 0 0;
            -webkit-border-radius: 4px 4px 0 0;
            border-radius: 4px 4px 0 0;
            text-align: left;
            color: #fff;
            transition: opacity .3s ease-in-out;
        }

        .form-top h3 {
            color: #fff;
        }

        .form-bottom {
            padding: 25px 25px 30px 25px;
            background: #eee;
            -moz-border-radius: 0 0 4px 4px;
            -webkit-border-radius: 0 0 4px 4px;
            border-radius: 0 0 4px 4px;
            text-align: left;
            transition: all .4s ease-in-out;
        }

        .form-bottom:hover {
            -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
        }

        form .form-bottom button.btn {
            min-width: 105px;
        }

        form .form-bottom .input-error {
            border-color: #d03e3e;
            color: #d03e3e;
        }

        form.registration-form fieldset {
            display: none;
        }

    </style>
@endsection
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Project Management
                <small>Job request edit</small>
            </h1>
        </section>
        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit job request form</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form" class="registration-form" action="{{ route('job.request.edit.action') }}"
                                method="POST">
                                <fieldset>
                                    <div class="form-top">
                                        <div class="form-top-left">
                                            <h3><span><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>Edit
                                                state, role and type for the job request form...</h3>
                                        </div>
                                    </div>
                                    <div class="form-bottom">
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <div class="form-group">
                                            <select class="form-control" name="state" id="state">
                                                <option value="">Select a state</option>
                                                @foreach ($states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ $state->id == $job->state_id ? 'selected' : '' }}>
                                                        {{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <select class="form-control" name="role" id="role">
                                                <option value="">Select a Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ $role->id == $job->role_id ? 'selected' : '' }}>
                                                        {{ $role->role->project_roles . ' (' . $role->tier_code . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <select class="form-control" name="type" id="type">
                                                <option value="">Select a project type</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}"
                                                        {{ $type->id == $job->type_id ? 'selected' : '' }}>
                                                        {{ $type->project_type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="property_type" id="property_type">
                                                <option value="">Select a property type</option>
                                                @foreach ($property_types as $type)
                                                    <option value="{{ $type->id }}"
                                                        {{ $type->id == $job->property_type_id ? 'selected' : '' }}>
                                                        {{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <div id="project_error"></div>
                                        </div>
                                        <button type="button" class="btn btn-combination btn-success btn-md">Next
                                        </button>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-top">
                                        <div class="form-top-left">
                                            <h3><span><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>
                                                Edit Labels</h3>
                                        </div>
                                    </div>
                                    <div class="form-bottom">
                                        @foreach ($job->CombinationLabel as $key => $label)
                                            <div class="col-md-12" style="padding-top: 12px;">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">
                                                        @if ($key == 0)
                                                            Create Labels:
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="label[]"
                                                            value="{{ $label->label }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        @if ($key == 0)
                                                            <button type="button" class="btn btn-success" id="addLabel">
                                                                <i class="fa fa-plus fa-fw"></i>Add label
                                                            </button>
                                                        @else
                                                            <a href="javascript:void(0)"
                                                                class="remove_field btn btn-info"><i
                                                                    class="fa fa-times"></i>Remove label</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div id="items"></div>
                                        <div style="padding-top: 15px;">&nbsp;</div>
                                        <button type="button" class="btn btn-info btn-previous">Previous</button>
                                        <button type="button" class="btn btn-success btn-label">Next</button>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-top">
                                        <div class="form-top-left">
                                            <h3><span><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>
                                                Edit Deadline</h3>
                                        </div>
                                    </div>
                                    <div class="form-bottom">
                                        @foreach ($job->deadline as $key1 => $deadline)
                                            <div class="col-md-12" style="padding-top: 14px;">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" placeholder="Name"
                                                                name="deadline_name[]" id="deadline_name"
                                                                value="{{ $deadline->name }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select class="form-control" id="deadline_days"
                                                                name="deadline_days[]">
                                                                <option value="0"
                                                                    {{ $deadline->months == '0' ? 'selected' : '' }}>
                                                                    Days
                                                                </option>
                                                                @for ($i = 1; $i <= 31; $i++)
                                                                    <option value="{{ $i }}"
                                                                        {{ $deadline->months == $i ? 'selected' : '' }}>
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select class="form-control" id="deadline_months"
                                                                name="deadline_months[]">
                                                                <option value="0"
                                                                    {{ $deadline->months == '0' ? 'selected' : '' }}>
                                                                    Months
                                                                </option>
                                                                @for ($i = 1; $i <= 12; $i++)
                                                                    <option value="{{ $i }}"
                                                                        {{ $deadline->months == $i ? 'selected' : '' }}>
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <label class="col-md-1"><span
                                                                class="pull-right">Years</span></label>
                                                        <div class="col-md-1">
                                                            <input type="text" class="form-control" id="deadline_days"
                                                                value="{{ $deadline->years }}" name="deadline_years[]"
                                                                placeholder="Years">
                                                        </div>
                                                        <label class="col-md-1"><span class="pull-right">From</span></label>
                                                        <div class="col-md-2">
                                                            <select class="form-control add_labels" name="label_select[]"
                                                                id="label_select">
                                                                <option value="{{ $deadline->deadlineLabel->label }}">
                                                                    {{ $deadline->deadlineLabel->label }}</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-1">
                                                            @if ($key1 == 0)
                                                                <button class="btn btn-success" id="addDeadline"
                                                                    type="button"><i class="fa fa-plus fa-fw"></i>
                                                                </button>
                                                            @else
                                                                <a href="javascript:void(0)"
                                                                    class="remove_field_deadline btn btn-info btn-md"><i
                                                                        class="fa fa-times fa-fw"></i></a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div id="deadlineExtension"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div style="padding-top: 15px;">&nbsp;</div>
                                        <button type="button" class="btn btn-previous btn-info">Previous</button>
                                        <button type="submit" class="btn btn-submit btn-success">Submit</button>
                                    </div>
                                </fieldset>
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
                <div class="box-footer">

                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.registration-form fieldset:first-child').fadeIn('slow');

            $('.registration-form select,input[type="text"],input[type="number"]').on('focus', function() {
                $(this).removeClass('input-error');
            });

            // First form next button
            $('.registration-form .btn-combination').on('click', function() {
                var parent_fieldset = $(this).parents('fieldset');
                var next_step = true;

                parent_fieldset.find('select').each(function() {
                    if ($(this).val() == "") {
                        $(this).addClass('input-error');
                        next_step = false;
                    } else {
                        $(this).removeClass('input-error');
                    }
                });

                if (next_step) {
                    var state = $('#state').val();
                    var role = $('#role').val();
                    var type = $('#type').val();
                    var property_type = $('#property_type').val();
                    $.ajax({
                        url: '{{ route('job.request.check.combination') }}',
                        type: 'post',
                        data: {
                            state: state,
                            role: role,
                            type: type,
                            property_type: property_type,
                            time: 'edit',
                            id: '{{ $job->id }}',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.status) {
                                var url = '{{ route('job.request.edit', [':id']) }}';
                                url = url.replace(':id', data.data);
                                $('#project_error').html(
                                    '<span style="color:red;">This combination is already present. If you want to edit this job request please <a href="' +
                                    url + '">click here</a></span>');
                                next_step = false;
                            } else {
                                parent_fieldset.fadeOut(400, function() {
                                    $(this).next().fadeIn();
                                });
                            }
                        }
                    });
                }


            });
            var values = [];
            // Second form next button
            $('.registration-form .btn-label').on('click', function() {
                values = $("input[name='label[]']").map(function() {
                    return $(this).val();
                }).get();
                $.each(values, function(key, value) {
                    $('.add_labels').append($("<option></option>").attr("value", value).text(
                    value));
                });
                var parent_fieldset = $(this).parents('fieldset');
                var next_step = true;

                parent_fieldset.find('input[type="text"]').each(function() {
                    if ($(this).val() == "") {
                        $(this).addClass('input-error');
                        next_step = false;
                    } else {
                        $(this).removeClass('input-error');
                    }
                });

                if (next_step) {
                    parent_fieldset.fadeOut(400, function() {
                        $(this).next().fadeIn();
                    });
                }

            });

            // previous step
            $('.registration-form .btn-previous').on('click', function() {
                $(this).parents('fieldset').fadeOut(400, function() {
                    $(this).prev().fadeIn();
                });
            });

            // submit
            $('.registration-form').on('submit', function(e) {

                $(this).find('input[type="text"],select,input[type="number"]').each(function() {
                    if ($(this).val() == "") {
                        e.preventDefault();
                        $(this).addClass('input-error');
                    } else {
                        $(this).removeClass('input-error');
                    }
                });
            });
            var max_fields = 20; //maximum input boxes allowed
            var wrapper = $("#items"); //Fields wrapper
            var add_button = $("#addLabel"); //Add button ID
            var x = 1; //initlal text box count
            add_button.click(function(e) { //on add input button click
                e.preventDefault();
                if (x < max_fields) { //max input box allowed
                    x++; //text box increment
                    $(wrapper).append(
                        '<div class="col-md-12" style="padding-top: 5px;"><div class="form-group"><label class="col-md-2 control-label">&nbsp;</label>' +
                        '<div class="col-md-8"><input type="text" class="form-control" name="label[]"></div>' +
                        '<div class="col-md-2"><a href="javascript:void(0)" class="remove_field btn btn-info"><i class="fa fa-times"></i>Remove label</a></div></div></div>'
                        );
                }
            });
            var deadline_wrapper = $('#deadlineExtension');
            var add_deadline = $('#addDeadline');
            var y = 1;

            add_deadline.click(function(e) {
                e.preventDefault();
                if (y < max_fields) {
                    y++;
                    $(deadline_wrapper).append(
                        ' <div class="col-md-12" style="padding-top: 10px;">' +
                        '<div class="col-md-2">' +
                        ' <input type="text" class="form-control" name="deadline_name[]" id="deadline_name" placeholder="Name">' +
                        '</div>' +
                        '<div class="col-md-2">' +
                        '  <select class="form-control" id="deadline_days" name="deadline_days[]">' +
                        '<option value="0">Days</option>' +
                        @for ($i = 1; $i <= 31; $i++)' +
                            '<option value="{{ $i }}">{{ $i }}</option>' +
                            '@endfor' +
                        '</select>' +
                        '</div>' +
                        '<div class="col-md-2">' +
                        '<select class="form-control" id="deadline_months" name="deadline_months[]">' +
                        '<option value="0">Months</option>' +
                        '<option value="1">January</option>' +
                        '<option value="2">February</option>' +
                        '<option value="3">March</option>' +
                        '<option value="4">April</option>' +
                        '<option value="5">May</option>' +
                        '<option value="6">June</option>' +
                        '<option value="7">July</option>' +
                        '<option value="8">August</option>' +
                        '<option value="9">September</option>' +
                        '<option value="10">October</option>' +
                        '<option value="11">November</option>' +
                        '<option value="12">December</option>' +
                        '</select>' +
                        '</div>' +
                        '<label class="col-md-1"><span class="pull-right">Years</span></label>' +
                        '<div class="col-md-1">' +
                        '<input type="text" class="form-control" id="deadline_years" value="0" name="deadline_years[]" placeholder="Years">' +
                        '</div>' +
                        '<label class="col-md-1"><span class="pull-right">From</span></label>' +
                        '<div class="col-md-2">' +
                        '<select class="form-control add_labels" name="label_select[]" id="label_select">' +
                        '<option value="">Select label</option>' +
                        '</select>' +
                        '</div>' +
                        '<div class="col-md-1">' +
                        '<a href="javascript:void(0)" class="remove_field_deadline btn btn-info btn-md"><i class="fa fa-times fa-fw"></i></a>' +
                        '</div>' +
                        '</div>');
                    $.each(values, function(key, value) {
                        $('.add_labels').append($("<option></option>").attr("value", value).text(
                            value));
                    });
                }
            });

            $(document).on("click", ".remove_field", function(e) { //user click on remove field
                e.preventDefault();
                $(this).parent().parent('div').remove();
                x--;
            });
            $(document).on("click", '.remove_field_deadline', function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                y--;
            });
        });
    </script>
@endsection
