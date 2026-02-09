@extends('layout.main')
<!-- Extends main layout form layout folder -->
<!-- Addind Dynamic layout -->
@section('title', 'Job request list')
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Project Request
                <small>Job Request</small>
            </h1>
            <ol class="breadcrumb">
                <a href="{{ route('project.job.request.form') }}" class="btn btn-success btn-md">Create a job
                    request</a>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of all job requests</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>State</th>
                                    <th>Role</th>
                                    <th>Project Type</th>
                                    <th>Property Type</th>
                                    <th>Deadline
                                        <small>(name,days,label)</small>
                                    </th>
                                    <th>Action</th>
                                </tr>
                                @if (count($combinations) > 0)
                                    @foreach ($combinations as $key => $combination)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $combination->state->name }}</td>
                                            <td>{{ $combination->role->role->project_roles . ' (' . $combination->role->tier_code . ')' }}
                                            </td>
                                            <td>{{ $combination->type->project_type }}</td>
                                            <td>{{ $combination->property_type->name }}</td>
                                            <td>
                                                <div style="overflow: auto;width:100%;max-height:60px;">
                                                    @foreach ($combination->deadline as $deadline)
                                                        <p>{{ $deadline->name . ' : ' . $deadline->days . ' days,' . $deadline->months . ' months,' . $deadline->years . ' years, ' . ' from: ' . $deadline->deadlineLabel->label }}
                                                        </p>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('job.request.edit', [$combination->id]) }}"
                                                    class="btn btn-info btn-xs" title="Edit & View job request"><i
                                                        class="fa fa-pencil"></i> </a>
                                                <button class="btn btn-warning btn-xs clone-btn" type="button"
                                                    data-id="{{ $combination->id }}" title="Clone job request"><i
                                                        class="fa fa-copy"></i></button>
                                                <button class="btn btn-danger btn-xs delete-btn" type="button"
                                                    data-id="{{ $combination->id }}" title="Delete"><i
                                                        class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No job request available....</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{ $combinations->links() }}
                            </ul>
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
            $('.delete-btn').on('click', function() {
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
                        url: "{{ route('job.request.delete') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Job deleted successfully',
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
            $('.clone-btn').on('click', function() {
                var id = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: "You want to make a clone of this!",
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, clone it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('job.request.clone') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Job cloned successfully',
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
                })
            });
        });
    </script>
@endsection
