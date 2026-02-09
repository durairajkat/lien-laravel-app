<!-- Extends main layout form layout folder -->
@extends('layout.main')

<!-- Addind Dynamic layout -->
@section('title', 'States')

<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Project Management
                <small>States</small>
            </h1>
            <ol class="breadcrumb">
                <button class="btn btn-md btn-success" type="button" data-toggle="modal" data-target="#addState">Add new
                    state
                </button>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of all states</h3>

                            <div class="box-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" id="state_search"
                                        value="{{ isset($_GET['stateSearch']) && $_GET['stateSearch'] != '' ? $_GET['stateSearch'] : '' }}"
                                        class="form-control pull-right" placeholder="Search">

                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default" id="search"><i
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
                                    <th>Code</th>
                                    <th>Short code</th>
                                    <th>Action</th>
                                </tr>
                                @if (count($states) > 0)
                                    @foreach ($states as $key => $state)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $state->name }}</td>
                                            <td>{{ $state->code }}</td>
                                            <td>{{ $state->short_code }}</td>
                                            <td>
                                                <button class="btn btn-info btn-xs editState" type="button"
                                                    data-name="{{ $state->name }}" data-code="{{ $state->code }}"
                                                    data-short_code="{{ $state->short_code }}"
                                                    data-id="{{ $state->id }}" title="Edit user"><i
                                                        class="fa fa-pencil"></i></button>
                                                <button class="btn btn-danger btn-xs deleteState"
                                                    data-id="{{ $state->id }}" type="button" title="Delete user"><i
                                                        class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No state available.For add agency click <a href="javascript:void(0)"
                                                data-toggle="modal" data-target="#addAgency">here</a></td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{ $states->links() }}
                            </ul>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
@endsection

@section('modal')
    <!-- Modal -->
    <div id="addState" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add State</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="state" class="col-sm-2 control-label">State</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="state" placeholder="State">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="code" class="col-sm-2 control-label">Code</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="code" placeholder="Code">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="short_code" class="col-sm-2 control-label">Short code</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control error" id="short_code" placeholder="Short code">
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
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="button" class="btn btn-info pull-right" id="addStateButton"><i
                                    class="fa fa-spinner fa-spin loader" style="display: none;"></i>
                                Add State
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
    <!-- Edit State -->
    <div id="editStateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add State</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="editState" class="col-sm-2 control-label">State</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="editState" placeholder="State">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="editCode" class="col-sm-2 control-label">Code</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="editCode" placeholder="Code">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="editShort_code" class="col-sm-2 control-label">Short code</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control error" id="editShort_code"
                                        placeholder="Short code">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="edit_id">
                        <div class="form-group edit-error-tag-field" style="display: none; color: red;">
                            <label for="error" class="col-sm-2 control-label">Error</label>

                            <div class="col-sm-10">
                                <span id="edit-error-field"></span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="button" class="btn btn-info pull-right" id="editStateButton"><i
                                    class="fa fa-spinner fa-spin loader" style="display: none;"></i>
                                Edit State
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
            $('#addStateButton').on('click', function() {
                $('.help-block').replaceWith('');
                var state = $('#state').val();
                if (state == '') {
                    $('#state').parent().parent('div').addClass('has-error');
                    $('#state').parent('div').append(
                        '<span class="help-block">Please enter state name</span>');
                    return false;
                }
                var code = $('#code').val();
                if (code == '') {
                    $('#code').parent().parent('div').addClass('has-error');
                    $('#code').parent('div').append(
                        '<span class="help-block">Please enter state code</span>');
                    return false;
                }
                if (!$.isNumeric(code)) {
                    $('#code').parent().parent('div').addClass('has-error');
                    $('#code').parent('div').append(
                        '<span class="help-block">Please enter state code should be numeric</span>');
                    return false;
                }
                var short_code = $('#short_code').val();
                if (short_code == '') {
                    $('#short_code').parent().parent('div').addClass('has-error');
                    $('#short_code').parent('div').append(
                        '<span class="help-block">Please enter state short code</span>');
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('add.state') }}",
                    data: {
                        state: state,
                        code: code,
                        short_code: short_code,
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
                $('.edit-error-tag-field').hide();
            });
            $('.deleteState').on('click', function() {
                var id = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('delete.state') }}",
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.status == true) {
                                window.location.reload();
                            } else {
                                swal(
                                    'Oops...',
                                    data.message,
                                    'error'
                                )
                            }

                        }
                    });
                })
            });
            $('.editState').on('click', function() {
                var state = $(this).data('name');
                $('#editState').val(state);
                var code = $(this).data('code');
                $('#editCode').val(code);
                var short_code = $(this).data('short_code');
                $('#editShort_code').val(short_code);
                var id = $(this).data('id');
                $('#edit_id').val(id);
                $('#editStateModal').modal('show');
            });
            $('#editStateButton').on('click', function() {
                var state = $('#editState').val();
                if (state == '') {
                    $('#editState').parent().parent('div').addClass('has-error');
                    $('#editState').parent('div').append(
                        '<span class="help-block">Please enter state name</span>');
                    return false;
                }
                var code = $('#editCode').val();
                if (code == '') {
                    $('#editCode').parent().parent('div').addClass('has-error');
                    $('#editCode').parent('div').append(
                        '<span class="help-block">Please enter state code</span>');
                    return false;
                }
                if (!$.isNumeric(code)) {
                    $('#editCode').parent().parent('div').addClass('has-error');
                    $('#editCode').parent('div').append(
                        '<span class="help-block">Please enter state code should be numeric</span>');
                    return false;
                }
                var short_code = $('#editShort_code').val();
                if (short_code == '') {
                    $('#editShort_code').parent().parent('div').addClass('has-error');
                    $('#editShort_code').parent('div').append(
                        '<span class="help-block">Please enter state short code</span>');
                    return false;
                }
                var id = $('#edit_id').val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('edit.state') }}",
                    data: {
                        id: id,
                        state: state,
                        code: code,
                        short_code: short_code,
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
                            $('#edit-error-field').text(data.message);
                            $('.edit-error-tag-field').show();
                        }

                    }
                });
            });
            $('#search').on('click', function() {
                var search = $('#state_search').val();
                var location = appendToQueryString('stateSearch', search);
                window.location.href = location;
            });
            $('#state_search').keyup(function(e){
                if(e.keyCode == 13)
                {
                    var string = $('#state_search').val();
                    var location = appendToQueryString('stateSearch', string);
                    window.location.href = location;
                }
            });
        });
    </script>
@endsection
