@extends('basicUser.layout.main')
@section('title', 'Project Overview')
@section('content')
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
    <div class="project-overview-wrapper">
        <div class="project-form project_details create-project-form create-project-form--project-overview">
            <div class="create-project-form-header">
                <h2>Project Overview</h2>
            </div>
            <div class="row dateBreak">
                @foreach ($project as $p)
                    <label>Project Name:</label>
                    <h3 class="project-overview-h3">{{ $p->project_name }}</h3>
                    <label>Last Updated</label>
                    <h3 class="project-overview-h3">{{ date('m/d/Y', strtotime($p->updated_at)) }}</h3>
                    <label>State</label>
                    <h3 class="project-overview-h3">{{ $state[0]->name }}</h3>
                    <label>Project Type</label>
                    <h3 class="project-overview-h3">{{ $type->project_type }}</h3>
                @endforeach
            </div>
            <a href="{{ route('project.task.view') . '?project_id=' . $id . '&edit=true' }}"
                class="project-overview-button project-overview-button--blue project-overview-button--mobile">TASKS</a>
            <a href="{{ route('member.create.project') . '?project_id=' . $id . '&edit=true' }}"
                class="project-overview-button project-overview-button--green project-overview-button--mobile">EDIT</a>
            <a data-id="{{ $id }}"
                class="deletePro project-overview-button project-overview-button--red project-overview-button--mobile"
                href="#">DELETE</a>
        </div>
        <button onclick="back()" title="Back To Projects" class="project-overview-esacpe-link">Back To Projects</button>
    </div>
@endsection
@section('script')
    <script>
        function back() {
            window.history.back()
        }
        $(document).ready(function() {
            function back() {
                window.history.back()
            }
            $('.deletePro').on('click', function(e) {
                e.preventDefault()
                var project_id = $(this).data('id');
                var user_id = '{{ Auth::user()->id }}';
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
                        url: "{{ route('project.delete') }}",
                        data: {
                            project_id: project_id,
                            user_id: user_id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Project deleted successfully',
                                }).then(function() {
                                    window.location =
                                        "{{ route('member.dashboard') }}";
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
        })
    </script>
@endsection
