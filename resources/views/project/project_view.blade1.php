<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title','Job request list')
<!-- Main Content -->

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Projects
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">List of all projects</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        @if(session()->has('date-error'))
                        <div class="alert alert-danger">
                            {!! session('date-error') !!}
                        </div>
                        @endif
                        <table class="table table-hover">
                            {{--<tr>
                                    <th>#</th>
                                    <th>Project Name
                                        <div class="sortDiv">
                                            <a class="sort" href="{{route('admin.project.view',['project_name' => 'asc'])}}">
                            <i class="fa fa-fw fa-sort-asc"></i>
                            </a>
                            <a class="sort" href="{{route('admin.project.view',['project_name' => 'desc'])}}">
                                <i class="fa fa-fw fa-sort-desc"></i>
                            </a>
                    </div>
                    </th>
                    <th>Company Name <a href="{{route('admin.project.view',['company_name' => 'asc'])}}"><i class="fa fa-fw fa-sort-asc"></i></a> <a href="{{route('admin.project.view',['company_name' => 'desc'])}}"><i class="fa fa-fw fa-sort-desc"></i></a></th>
                    <th>Project User <a href="{{route('admin.project.view',['project_user' => 'asc'])}}"><i class="fa fa-fw fa-sort-asc"></i></a> <a href="{{route('admin.project.view',['project_user' => 'desc'])}}"><i class="fa fa-fw fa-sort-desc"></i></a></th>
                    <th>Project State <a href="{{route('admin.project.view',['project_state' => 'asc'])}}"><i class="fa fa-fw fa-sort-asc"></i></a> <a href="{{route('admin.project.view',['project_state' => 'desc'])}}"><i class="fa fa-fw fa-sort-desc"></i></a></th>
                    <th>Project Type <a href="{{route('admin.project.view',['project_type' => 'asc'])}}"><i class="fa fa-fw fa-sort-asc"></i></a> <a href="{{route('admin.project.view',['project_type' => 'desc'])}}"><i class="fa fa-fw fa-sort-desc"></i></a></th>
                    <th>Project Role <a href="{{route('admin.project.view',['project_role' => 'asc'])}}"><i class="fa fa-fw fa-sort-asc"></i></a> <a href="{{route('admin.project.view',['project_role' => 'desc'])}}"><i class="fa fa-fw fa-sort-desc"></i></a></th>
                    <th>Project Customer <a href="{{route('admin.project.view',['project_customer' => 'asc'])}}"><i class="fa fa-fw fa-sort-asc"></i></a> <a href="{{route('admin.project.view',['project_customer' => 'desc'])}}"><i class="fa fa-fw fa-sort-desc"></i></a></th>
                    <th>Action</th>
                    </tr>--}}

                    <tr>
                        <th>#</th>
                        <th>
                            Project Name
                            <a>
                                <i class="fa fa-sort" aria-hidden="true" data-col="project_name" data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ?'DESC':'ASC' }}"></i>
                            </a>
                        </th>
                        <th>
                            Customer Company Name
                            <a>
                                <i class="fa fa-sort" aria-hidden="true" data-col="customer_contract_id" data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_contract_id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ?'DESC':'ASC' }}"></i>
                            </a>
                        </th>
                        <th>
                            Customer Name
                            <a>
                                <i class="fa fa-sort" aria-hidden="true" data-col="customer_contract_id" data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_contract_id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ?'DESC':'ASC' }}"></i>
                            </a>
                        </th>
                        <th>
                            Customer Phone number
                        </th>
                        <th>
                            Entry Date
                            <a>
                                <i class="fa fa-sort" aria-hidden="true" data-col="created_at" data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'created_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ?'DESC':'ASC' }}"></i>
                            </a>
                        </th>
                        <th>
                            Next Action Date
                            <a>
                                <i class="fa fa-sort" aria-hidden="true" data-col="updated_at" data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'updated_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ?'DESC':'ASC' }}"></i>
                            </a>
                        </th>
                        <th>
                            State
                            <a>
                                <i class="fa fa-sort" aria-hidden="true" data-col="state_id" data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'state_id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ?'DESC':'ASC' }}"></i>
                            </a>
                        </th>
                        <th>
                            Type
                            <a>
                                <i class="fa fa-sort" aria-hidden="true" data-col="project_type_id" data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_type_id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ?'DESC':'ASC' }}"></i>
                            </a>
                        </th>
                        <th>
                            Status
                            <a>
                                <i class="fa fa-sort" aria-hidden="true" data-col="status" data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'status' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ?'DESC':'ASC' }}"></i>
                            </a>
                        </th>
                        <th>
                            Lien Providers
                        </th>
                        <th>
                            User Action
                        </th>
                    </tr>
                    @if(count($projects) > 0)
                    @foreach($projects as $key => $project)
                    <tr>
                        {{--<td>{{ $key+1 }}</td>
                        <td>{{ $project->project_name }}</td>
                        <td>{{ !is_null($project->user) && !is_null($project->user->details) ? $project->user->details->company : '' }}</td>
                        <td>{{ $project->user->email }}</td>
                        <td>{{ $project->state->name }}</td>
                        <td>{{ $project->project_type->project_type}}</td>
                        <td>{{ $project->role->project_roles }}</td>
                        <td>{{ $project->tier->customer->name }}</td>--}}
                        @php
                        $company = 'N/A';
                        $phone_number = 'N/A';
                        if(!is_null($project->customer_contract) && !is_null($project->customer_contract->getContacts)) {
                        if($project->customer_contract->getContacts->type == '0') {
                        $phone_number = !is_null($project->customer_contract->getContacts->phone) ? $project->customer_contract->getContacts->phone : 'N/A';
                        $company = !is_null($project->customer_contract) && !is_null($project->customer_contract->company) ? $project->customer_contract->company->company :'N/A';
                        }
                        }
                        @endphp
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{ $project->project_name }}</td>
                        <td>{{ isset($company) ? $company :'N/A' }}</td>
                        <td>{{ !is_null($project->customer_contract) && !is_null($project->customer_contract->getContacts) ? $project->customer_contract->getContacts->first_name.' '.$project->customer_contract->getContacts->last_name : 'N/A' }}</td>
                        <td>{{ isset($phone_number) ? $phone_number :'N/A' }}</td>
                        <td>
                            <span>
                                <i>
                                    {{ date("m/d/Y", strtotime($project->created_at)) }}
                                </i>
                            </span>
                        </td>
                        <td>
                            <span>
                                <i>
                                    {{ date("m/d/Y", strtotime($project->updated_at)) }}
                                </i>
                            </span>
                        </td>
                        <td>{{ $project->state->name }}</td>
                        <td>{{ $project->project_type->project_type }}</td>
                        <td>
                            @if($project->status == 1)
                            Active
                            @else
                            Inactive
                            @endif
                        </td>
                        <td>
                            @php
                            $lienProvider = [];
                            $lienProviderArray = '';
                            if(!is_null($project->user) && !is_null($project->user->details) && $project->user->details != '' && $project->user->details->lien_status == '1') {
                            $userProvider = $project->user->lienProvider;
                            if(!is_null($userProvider) && $userProvider != '') {
                            foreach ($userProvider as $pro) {
                            foreach ($pro->getLien as $proL) {
                            $lienProvider[] = '
                    <tr>
                        <td>'.$proL->company.'</td>
                        <td>'.$proL->firstName.'</td>
                        <td> '.$proL->lastName.'</td>
                        <td>'.$proL->email.'</td>
                    <tr>';
                        }
                        }
                        }
                        }
                        if(count($lienProvider) > 0) {
                        $lienProviderArray = implode(' | ',$lienProvider);
                        }
                        @endphp
                        @if(!is_null($project->jobInfo) && $project->jobInfo->is_viewable == '1' )
                        <button class="btn btn-warning btn-sm viewProvider" data-provider="{!! $lienProviderArray !!}" type="button" title="View Lien Provider">
                            <i class="fa fa-eye"></i>
                        </button>
                        @else
                        <div class="text-info">NLB</div>
                        @endif
                        {{ !is_null($project->jobInfo) && $project->jobInfo->is_viewable == '1'  ? $project->jobInfo->name : ''}}
                        </td>
                        <td>
                            <a href="{{ route('admin.project.details',[$project->id]) }}" class="btn btn-info btn-xs" title="Edit & View job request"><i class="fa fa-info"></i> </a>
                            <button class="btn btn-warning btn-xs lock" data-id="{{ $project->id }}" data-status="{{ $project->status }}" type="button"><i class="fa fa-{{ $project->status == '1'?'unlock':'lock' }}"></i></button>
                            <button class="btn btn-danger btn-xs delete-btn" type="button" data-id="{{ $project->id }}" title="Delete"><i class="fa fa-trash"></i></button>
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
                        {{ $projects->appends($_GET)->links() }}
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

        function replaceUrlParam(url, paramName, paramValue) {
            if (paramValue == null) {
                paramValue = '';
            }
            var pattern = new RegExp('\\b(' + paramName + '=).*?(&|$)');
            if (url.search(pattern) >= 0) {
                return url.replace(pattern, '$1' + paramValue + '$2');
            }
            url = url.replace(/\?$/, '');
            return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
        }

        $('.fa-sort').on('click', function() {
            var col = $(this).data('col');
            var type = $(this).data('type');
            var location = window.location.href;
            var location = replaceUrlParam(location, 'sortBy', type);
            var location = replaceUrlParam(location, 'sortWith', col);
            window.location.href = location;
        });

        $('.lock').on('click', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            var statusName = (status == "0") ? "Activated" : "Deactivated";
            $.ajax({
                type: "POST",
                url: "{{ route('project.status') }}",
                data: {
                    id: id,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.status == true) {
                        swal({
                            position: 'center',
                            type: 'success',
                            title: 'Project ' + statusName + ' successfully',
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        swal(
                            'Oops...',
                            data.message,
                            'error'
                        )
                    }

                }
            });
        });
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
                    url: "{{ route('admin.project.delete') }}",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status) {
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Project deleted successfully',
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

    $(document).delegate('.viewProvider', 'click', function() {
        var providers = $(this).data('provider');
        if (providers != '') {
            var providersArray = providers.split('|');
            var providerString = '<table class="table table-bordered">' +
                '<thead><tr><th>Company</th><th>First Name</th><th>Last Name</th><th>Email</th></tr></thead>';
            $.each(providersArray, function(index, value) {
                providerString += value;
            });
            providerString += '</table>';
            swal({
                html: providerString,
                title: 'Assigned Lien Provider',
                text: providersArray,
                //text: 'NLB',
                timer: 10000
            });
        } else {
            swal({
                title: 'Assigned Lien Provider',
                text: 'NLB',
                timer: 5000
            });
        }
    });
</script>
@endsection