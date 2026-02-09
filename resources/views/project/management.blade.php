<!-- Extends main layout form layout folder -->
@extends('layout.main')

<!-- Addind Dynamic layout -->
@section('title', 'Project roles & types')

<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Project Management
                <small>Roles & Types</small>
            </h1>
        </section>
        <section class="content">
            @if (Session::has('success'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Upload Project Excel</h3>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive no-padding">
                                <form method="post" action="{{ route('project.upload.excel') }}"
                                    enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Select Excel</label>
                                        <div class="col-md-7">
                                            <input type="file" class="form-control" name="file" required />
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-success btn-lg">Upload</button>
                                        </div>
                                    </div>
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xs-6">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">List of all project roles</h3>
                                    <button class="btn btn-md addProjectButton" type="button"
                                        data-type="Add project role">Add new role
                                    </button>

                                    <div class="box-tools">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" id="role_search"
                                                value="{{ isset($_GET['projectRole']) && $_GET['projectRole'] != '' ? $_GET['projectRole'] : '' }}"
                                                class="form-control pull-right" placeholder="Search">

                                            <div class="input-group-btn">
                                                <button type="button" data-type="role" class="btn btn-default search"><i
                                                        class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                        @if (count($roles) > 0)
                                            @foreach ($roles as $key => $role)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $role->project_roles }}</td>
                                                    <td>
                                                        <button class="btn btn-info btn-xs addProjectButton" type="button"
                                                            data-type="Edit project role"
                                                            data-name="{{ $role->project_roles }}"
                                                            data-id="{{ $role->id }}" title="Edit role"><i
                                                                class="fa fa-pencil"></i></button>
                                                        <button class="btn btn-danger btn-xs delete" data-type="role"
                                                            data-id="{{ $role->id }}" type="button"
                                                            title="Delete role"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">No project rolls available.</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix">
                                    <ul class="pagination pagination-sm no-margin pull-right">
                                        {{ $roles->links() }}
                                    </ul>
                                </div>
                            </div>
                            <!-- /.box -->
                        </div>
                        <div class="col-xs-6">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">List of all project type</h3>
                                    <button class="btn btn-md addProjectButton" type="button"
                                        data-type="Add project type">Add new type
                                    </button>

                                    <div class="box-tools">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" id="type_search"
                                                value="{{ isset($_GET['projectType']) && $_GET['projectType'] != '' ? $_GET['projectType'] : '' }}"
                                                class="form-control pull-right" placeholder="Search">

                                            <div class="input-group-btn">
                                                <button type="button" data-type="type" class="btn btn-default search"><i
                                                        class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                        @if (count($types) > 0)
                                            @foreach ($types as $key => $type)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $type->project_type }}</td>
                                                    <td>
                                                        <button class="btn btn-info btn-xs addProjectButton" type="button"
                                                            data-type="Edit project type"
                                                            data-name="{{ $type->project_type }}"
                                                            data-id="{{ $type->id }}" title="Edit Type"><i
                                                                class="fa fa-pencil"></i></button>
                                                        <button class="btn btn-danger btn-xs delete"
                                                            data-id="{{ $type->id }}" data-type="type" type="button"
                                                            title="Delete type"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">No project type available.</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix">
                                    <ul class="pagination pagination-sm no-margin pull-right">
                                        {{ $types->links() }}
                                    </ul>
                                </div>
                            </div>
                            <!-- /.box -->
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection

@section('modal')
    <!-- Modal add Types -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="typeName"></span></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="state" class="col-sm-2 control-label">Name</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="name" placeholder="Name">
                                </div>
                            </div>
                        </div>
                        <div class="form-group error-tag-field" style="display: none; color: red;">
                            <label for="error" class="col-sm-2 control-label">Error</label>

                            <div class="col-sm-10">
                                <span id="error-field"></span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Reset</button>
                            <button type="button" class="btn btn-info pull-right" id="addButton"><i
                                    class="fa fa-spinner fa-spin loader" style="display: none;"></i>
                                Submit
                            </button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('#type_search').keyup(function(e){
                if(e.keyCode == 13)
                {
                    var string = $('#type_search').val();
                    var location = appendToQueryString('projectType', string);
                    window.location.href = location;
                }
            });

            $('#role_search').keyup(function(e){
                if(e.keyCode == 13)
                {
                    var string = $('#role_search').val();
                    var location = appendToQueryString('projectRole', string);
                    window.location.href = location;
                }
            });

            $('.addProjectButton').on('click', function() {
                $('#addModal').find('form').trigger('reset');
                $('.error').parent().parent('div').removeClass('has-error');
                $('.error').parent('div').children('.help-block').remove();
                $('.error-tag-field').hide();
                var type = $(this).data('type');
                $('.typeName').text(type);
                if (type == 'Add project role') {
                    $('#addButton').attr('data-type', 'addRole');
                    $('#addButton').attr('data-id', '0');
                } else if (type == 'Add project type') {
                    $('#addButton').attr('data-type', 'addType');
                    $('#addButton').attr('data-id', '0');
                } else if (type == 'Edit project role') {
                    $('#addButton').attr('data-type', 'editRole');
                    var name = $(this).data('name');
                    $('#name').val(name);
                    var id = $(this).data('id');
                    $('#addButton').attr('data-id', id);
                } else if (type == 'Edit project type') {
                    var id = $(this).data('id');
                    $('#addButton').attr('data-type', 'editType');
                    $('#addButton').attr('data-id', id);
                    var name = $(this).data('name');
                    $('#name').val(name);
                } else {
                    console.log('Not match');
                    return false;
                }
                $('#addModal').modal('show');
            });
            $('#addModal').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $('.error').parent().parent('div').removeClass('has-error');
                $('.error').parent('div').children('.help-block').remove();
                $('.error-tag-field').hide();
            });
            $('#addButton').on('click', function() {
                var type = $(this).data('type');
                var id = $(this).data('id');
                var name = $('#name').val();
                if (name == '') {
                    $('#name').parent().parent('div').addClass('has-error');
                    $('#name').parent('div').append('<span class="help-block">Please enter name</span>');
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('project.management.action') }}",
                    data: {
                        id: id,
                        type: type,
                        name: name,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        $('.loader').show();
                    },
                    complete: function() {
                        $('.loader').hide();
                    },
                    success: function(data) {
                        if (data.status == true) {
                            window.location.reload();
                        } else {
                            $('#error-field').text(data.message);
                            $('.error-tag-field').show();
                        }

                    }
                });
            });
            $('.error').on('keyup', function() {
                $(this).parent().parent('div').removeClass('has-error');
                $(this).parent('div').children('.help-block').remove();
                $('.error-tag-field').hide();
                $('.error-tier-field').hide();
            });
            $('.error').on('change', function() {
                $(this).parent().parent('div').removeClass('has-error');
                $(this).parent('div').children('.help-block').remove();
                $('.error-tag-field').hide();
                $('.error-tier-field').hide();
            });
            $('.search').on('click', function() {
                var type = $(this).data('type');
                if (type == 'role') {
                    var role = $('#role_search').val();
                    var location = appendToQueryString('projectRole', role);
                    window.location.href = location;
                } else if (type == 'tier') {
                    var tier = $('#tier_search').val();
                    var location = appendToQueryString('projectTier', tier);
                    window.location.href = location;
                } else {
                    var type = $('#type_search').val();
                    var location = appendToQueryString('projectType', type);
                    window.location.href = location;
                }
            });
            $('.delete').on('click', function() {
                var id = $(this).data('id');
                var type = $(this).data('type');
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
                        url: "{{ route('management.delete') }}",
                        data: {
                            id: id,
                            type: type,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: type + ' deleted successfully',
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
        });
    </script>
@endsection
