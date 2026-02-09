@extends('basicUser.projects.create')

@section('style')
    <style>
        .datepicker,
        .table-condensed {
            width: 220px;
            padding: 0px;
        }

        .multiple {
            margin-bottom: 5px;
        }

        .row .date-space {
            border-bottom: 1px solid rgba(0, 0, 0, .1);
            padding: 10px;
        }

        .row .date-space:last-of-type {
            border-bottom: none;
        }

    </style>
@endsection

@section('body')
    <div class="container">
        <br>
        <div class="row setup-content" id="step-3">
            <div class="col-xs-10">
                <div class="col-md-12 well text-center">
                    <h1 class="text-center">Project Dates</h1>
                    <hr class="divider">
                    <div class="container col-xs-10">
                        <form id="projectDates" action="#" method="post" class="form-horizontal project-form project_dates">
                            @foreach ($projectDates as $date)
                                <div class="row date-space">
                                    <div class="form-group">
                                        <label class="col-md-6 control-label">{{ $date['name'] }}</label>
                                        <div class="col-md-6 date-parent">
                                            @if (empty($date['dates']))
                                                @if ($date['recurring'] === 1)
                                                    <div class="row">
                                                        <div class="col-md-12 input-space">
                                                            <input type="text" class="form-control date multiple"
                                                                name="remedyDates[{{ $date['id'] }}]" value=""
                                                                data-provide="datepicker"
                                                                data-recurring="{{ $date['recurring'] }}" data-dateId="1"
                                                                data-existing="false">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col-md-12 input-space">
                                                            <input type="text" class="form-control date multiple"
                                                                name="remedyDates[{{ $date['id'] }}]" value=""
                                                                data-provide="datepicker"
                                                                data-recurring="{{ $date['recurring'] }}"
                                                                data-dateId="null">
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                @foreach ($date['dates'] as $value)
                                                    @if ($date['recurring'] === 1)
                                                        <div class="row">
                                                            <div class="col-md-12 input-space">
                                                                <input type="text" class="form-control date multiple"
                                                                    name="remedyDates[{{ $date['id'] }}]"
                                                                    value="{{ $value['value'] }}" data-provide="datepicker"
                                                                    data-recurring="{{ $date['recurring'] }}"
                                                                    data-dateId="{{ $value['id'] }}" data-existing="true">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row">
                                                            <div class="col-md-12 input-space">
                                                                <input type="text" class="form-control date multiple"
                                                                    name="remedyDates[{{ $date['id'] }}]"
                                                                    value="{{ $value['value'] }}" data-provide="datepicker"
                                                                    data-recurring="{{ $date['recurring'] }}"
                                                                    data-dateId="{{ $value['id'] }}" data-existing="true">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @if ($date['recurring'] === 1)
                                                    <div class="row">
                                                        <div class="col-md-12 input-space">
                                                            <input type="text" class="form-control date multiple"
                                                                name="remedyDates[{{ $date['id'] }}]" value=""
                                                                data-provide="datepicker"
                                                                data-recurring="{{ $date['recurring'] }}" data-dateId="1"
                                                                data-existing="false">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{ csrf_field() }}
                            <input type="hidden" name="project_id" value="{{ $project_id }}" class="project_id">
                            <input type="hidden" name="tab_view" value="{{ $tab_view }}" class="tab_view">
                            {{-- @if (!isset($_GET['view']))
                                <button id="activate-step-4" type="button" class="blue-btn pull-right">
                                    Continue & view project deadline
                                </button>
                            @elseif(isset($_GET['view']) && $_GET['view'] != 'detailed')
                                <button id="activate-step-4" type="button" class="blue-btn pull-right">
                                    Continue & view project deadline
                                </button>
                            @else --}}
                            <button id="activate-step-4" type="button" class="blue-btn pull-right">
                                Save & Continue
                            </button>
                            {{-- @endif --}}
                        </form>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let valueState = false;
            let data;

            $(document).on('click', '.multiple', function(e) {
                if ($(this).val().length === 0) {
                    valueState = false
                } else {
                    valueState = true
                }
            })

            $(document).on('change', 'input[data-existing="true"]', function(e) {
                let existing = $(this).serialize()
                let projectId = $('.project_id').val();
                let token = $("input[name='_token']").val()
                let dateId = $(this).attr('data-dateId')
                data = existing + '&date_id=' + dateId + '&_token=' + token + '&project_id=' + projectId +
                    '&tab_view=detailed'
                $.ajax({
                    type: "POST",
                    url: '{{ route('project.dates.update') }}',
                    data: data,
                    success: function(data) {}
                });
            });

            $(document).on('click', '#activate-step-4', function(e) {
                //var data = $('.project_dates').serialize();
                var type = $('.tab_view').val();
                var buttonName = $(this);
                var parent_fieldset = $(this).parent('form');
                var next_step = true;

                /*                $(".project_dates .date").each(function() {
                                   if ($(this).val() == "") {
                                        $(this).addClass('input-error');
                                        next_step = false;
                                    } else {
                                        $(this).removeClass('input-error');
                                    }
                                });*/

                var buttonName = $(this);
                if (next_step) {
                    let newDate = $('input[data-dateId="null"]').serialize()
                    let id = $('.project_id').val();
                    let token = $("input[name='_token']").val();
                    data = newDate + '&_token=' + token + '&project_id=' + id + '&tab_view=detailed'
                    if ($('input[data-dateId="null"]').length > 0) {
                        $.ajax({
                            type: "POST",
                            url: '{{ route('project.dates.submit') }}',
                            data: data,
                            success: function(data) {
                                if (data.status) {
                                    var url = '';
                                    url =
                                        "{{ route('project.deadline.view') }}?project_id={{ $project_id }}&view={{ $_GET['view'] }}";
                                    window.location.href = url;
                                } else {
                                    swal(
                                        'Error',
                                        data.message,
                                        'error'
                                    );
                                }

                            }
                        });
                    } else {
                        var url = '';
                        {{-- @if (!isset($_GET['view']))
                        url = '{{ route('project.deadline',[$project_id]) }}';
                    @elseif(isset($_GET['view']) && $_GET['view'] != 'detailed')
                        url = '{{ route('project.deadline',[$project_id]) }}';
                    @else --}}
                        url =
                            "{{ route('project.deadline.view') }}?project_id={{ $project_id }}&view={{ $_GET['view'] }}";
                        {{-- @endif --}}
                        window.location.href = url;
                    }

                }
            });

            $(document).on('change', '.multiple', function(e) {
                if ($(this).attr('data-recurring') === '1' && $(this).attr('value') === "") {
                    let name = $(this).attr('name')
                    let recurring = $(this).attr('data-recurring')
                    let input =
                        "<div class=\"row\"> <div class=\"col-md-12 input-space\"> <input type=\"text\" class=\"form-control date multiple\" name=\"" +
                        name + "\"value=\"\"data-provide=\"datepicker\"  data-recurring=\"" + recurring +
                        "\" data-dateId = \"1\" data-existing=\"false\"></div></div>"
                    if (!valueState) {
                        $(this).closest('.date-parent').append(input)
                    }
                    data = $(this).serialize()
                    let id = $('.project_id').val();
                    let token = $("input[name='_token']").val()
                    data = data + '&_token=' + token + '&project_id=' + id + '&tab_view=detailed'
                    let elem = $(this)
                    $.ajax({
                        type: "POST",
                        url: '{{ route('project.dates.submit') }}',
                        data: data,
                        success: function(response) {
                            let res = response;
                            elem.attr('data-dateId', res.id)
                            elem.attr('data-existing', true)
                            elem.attr('value', res.date)
                        }
                    });
                }

            })
            $('.clickExpress').on('click', function() {
                window.location.href = replaceUrlParam('view', 'express', window.location.href);
            });

            $('.clickDetailed').on('click', function() {
                window.location.href = replaceUrlParam('view', 'detailed', window.location.href);
            });

        });
    </script>
@endsection
