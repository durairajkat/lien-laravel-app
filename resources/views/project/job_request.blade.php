<!-- Extends main layout form layout folder -->
@extends('layout.main')

<!-- Addind Dynamic layout -->
@section('title', 'Job request form')

<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Project Management
                <small>Job request form</small>
            </h1>
        </section>
        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Create a job request form</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State</label>
                                    <select class="form-control" style="width: 100%;">
                                        <option value="">Select a state</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control" style="width: 100%;">
                                        <option value="">Select a Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->project_roles }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Project Type</label>
                                    <select class="form-control" style="width: 100%;">
                                        <option value="">Select a project type</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->project_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Project Type</label>
                                    <select class="form-control" style="width: 100%;">
                                        <option value="">Select a property type</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->project_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Create Labels: </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="label[]">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success" id="addLabel"><i
                                            class="fa fa-plus fa-fw"></i>Add label
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="items"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="padding-top: 14px;">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Create deadline: </label>
                                <div class="col-md-12">
                                    <label class="col-md-1"><span class="pull-right">Name</span></label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control">
                                    </div>
                                    <label class="col-md-2"><span class="pull-right">Number of days</span></label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control">
                                    </div>
                                    <label class="col-md-1"><span class="pull-right">From</span></label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success" id="addDeadline" type="button"><i
                                                class="fa fa-plus fa-fw"></i></button>
                                    </div>
                                </div>
                                <div id="deadlineExtension"></div>
                            </div>
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
    <script>
        $(document).ready(function() {
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
                        '<div class="col-md-6"><input type="text" class="form-control" name="label[]"></div>' +
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
                    $(deadline_wrapper).append('<div class="col-md-12" style="padding-top: 12px;">\n' +
                        '                                    <label class="col-md-1"><span class="pull-right">Name</span></label>\n' +
                        '                                    <div class="col-md-2">\n' +
                        '                                        <input type="text" class="form-control">\n' +
                        '                                    </div>\n' +
                        '                                    <label class="col-md-2"><span class="pull-right">Number of days</span></label>\n' +
                        '                                    <div class="col-md-2">\n' +
                        '                                        <input type="text" class="form-control">\n' +
                        '                                    </div>\n' +
                        '                                    <label class="col-md-1"><span class="pull-right">From</span></label>\n' +
                        '                                    <div class="col-md-2">\n' +
                        '                                        <input type="text" class="form-control">\n' +
                        '                                    </div>\n' +
                        '                                    <div class="col-md-2">\n' +
                        '                                        <a href="javascript:void(0)" class="remove_field_deadline btn btn-info"><i class="fa fa-times fa-fw"></i></a>\n' +
                        '                                    </div>\n' +
                        '                                </div>');
                }
            });

            wrapper.on("click", ".remove_field", function(e) { //user click on remove field
                e.preventDefault();
                $(this).parent().parent('div').remove();
                x--;
            });
            deadline_wrapper.on("click", '.remove_field_deadline', function(e) {
                e.preventDefault();
                $(this).parent().parent('div').remove();
                y--;
            })
        });
    </script>
@endsection
