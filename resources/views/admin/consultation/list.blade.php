<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Consultation')
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ ucfirst($pageName) }}
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of all {{ strtolower($pageName) }}</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Claim Amount</th>
                                    <th>Description</th>
                                    {{-- <th>User</th> --}}
                                    <th>Action</th>
                                </tr>
                                @if (count($consultations) > 0)
                                    @foreach ($consultations as $key => $consultation)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $consultation->first_name . ' ' . $consultation->last_name }}</td>
                                            <td>{{ $consultation->email }}</td>
                                            <td>{{ $consultation->phone_number }}</td>
                                            <td>{{ $consultation->claim_amount }}</td>
                                            <td>{{ $consultation->description }}</td>
                                            {{-- <td>{{ $consultation->user->email }}</td> --}}
                                            <td>
                                                <a href="{{ route('reply.consultation', [$consultation->id]) }}"
                                                    class="btn btn-info btn-xs" title="Send Mail">
                                                    <i class="fa fa-send"></i>
                                                </a>
                                                <button class="btn btn-danger btn-xs delete" type="button"
                                                    data-id="{{ $consultation->id }}" title="Delete user">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No contact request available.</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{ $consultations->links() }}
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
            $('.delete').on('click', function() {
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
                        url: "{{ route('consultation.delete') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Consultations deleted successfully',
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
