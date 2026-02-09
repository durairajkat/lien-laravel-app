<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Job Info List')
<!-- Main Content -->
@section('style')
    <style>
        .sortDiv {
            display: inline-block;
        }

        a.sort {
            display: block;
            height: 2px;
        }

    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <h1>
                Job Info List
            </h1>
        </section>

        @if (Session::has('search-error'))
            <section class="content-header">
                <div class="alert alert-danger">
                    {{ Session::get('search-error') }}
                </div>
            </section>
        @endif

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Filter job info form list</h3>
                        </div>
                        <!-- /.box-header -->
                        <form type="post" action="#" class="form-horizontal filterForm">
                            <div class="box-body no-padding">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Lien Provider</label>
                                                <div class="col-md-9">
                                                    <select id="lien_provider" name="lien_provider"
                                                        class="form-control chosenfield">
                                                        <option value="" disabled selected>Search with Lien Provider
                                                        </option>
                                                        @foreach ($lienList as $lien)
                                                            @if (isset($_GET['search']) && isset($_GET['lien_provider']) && $_GET['lien_provider'] == $lien['id'])
                                                                <option value="{{ $lien['id'] }}" selected>
                                                                    {{ $lien['data'] }}</option>
                                                            @else
                                                                <option value="{{ $lien['id'] }}">{{ $lien['data'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <!-- <input type="text" id="lien_provider" data-search="lien_provider" name="lien_provider" value="{{ isset($_GET['search']) && isset($_GET['lien_provider']) ? $_GET['lien_provider'] : '' }}" class="form-control autocomplete chosenfield"> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label class="col-md-6 control-label">
                                                        Date From :
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" id="date" name="assigned_date_from"
                                                            value="{{ isset($_GET['search']) && isset($_GET['assigned_date_from']) ? $_GET['assigned_date_from'] : '' }}"
                                                            class="date form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">

                                                    <label class="col-md-6 control-label">
                                                        Date To :
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" id="date" name="assigned_date_to"
                                                            value="{{ isset($_GET['search']) && isset($_GET['assigned_date_to']) ? $_GET['assigned_date_to'] : '' }}"
                                                            class="date form-control">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">User Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="user_name" id="user_name"  data-search="user_name" value="{{ isset($_GET['search']) && isset($_GET['user_name']) ? $_GET['user_name'] : '' }}" class="user_name form-control autocomplete">
                                                    </div>
                                                </div>
                                            </div> -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">
                                                    Project Name
                                                </label>
                                                <div class="col-md-9">
                                                    <select name="project_name" id="project_name"
                                                        class="form-control chosenfield">
                                                        <option value="" disabled selected>Search with Project Name</option>
                                                        @foreach ($projects as $list)
                                                            @if (isset($_GET['search']) && isset($_GET['project_name']) && $_GET['project_name'] == $list['id'])
                                                                <option value="{{ $list['id'] }}" selected>
                                                                    {{ $list['data'] }}</option>
                                                            @else
                                                                <option value="{{ $list['id'] }}">{{ $list['data'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <!-- <input type="text" name="project_name" id="project_name" data-search="project_name" value="{{ isset($_GET['search']) && isset($_GET['project_name']) ? $_GET['project_name'] : '' }}" class="form-control autocomplete"> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <label class="col-md-6 control-label">
                                                        Completed From :
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="date_completed_from"
                                                            id="date_completed_from" data-search="date_completed_from"
                                                            value="{{ isset($_GET['search']) && isset($_GET['date_completed_from']) ? $_GET['date_completed_from'] : '' }}"
                                                            class="date form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="col-md-6 control-label">
                                                        Completed To :
                                                    </label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="date_completed_to" id="date_completed_to"
                                                            data-search="date_completed_to"
                                                            value="{{ isset($_GET['search']) && isset($_GET['date_completed_to']) ? $_GET['date_completed_to'] : '' }}"
                                                            class="date form-control">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Customer Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="customer_name" id="customer_name" data-search="customer_name" value="{{ isset($_GET['search']) && isset($_GET['customer_name']) ? $_GET['customer_name'] : '' }}" class="form-control autocomplete">
                                                    </div>
                                                </div>
                                            </div> -->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">
                                                    Company name
                                                </label>
                                                <div class="col-md-9">
                                                    <select name="company_name" id="company_name"
                                                        class="form-control chosenfield">
                                                        <option value="" disabled selected>Search with Company Name</option>
                                                        @foreach ($companies as $list)
                                                            @if (isset($_GET['search']) && isset($_GET['company_name']) && $_GET['company_name'] == $list['id'])
                                                                <option value="{{ $list['id'] }}" selected>
                                                                    {{ $list['data'] }}</option>
                                                            @else
                                                                <option value="{{ $list['id'] }}">{{ $list['data'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <!-- <input type="text" name="company_name" id="company_name" data-search="company_name" value="{{ isset($_GET['search']) && isset($_GET['company_name']) ? $_GET['company_name'] : '' }}" class="form-control autocomplete"> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">
                                                    Status
                                                </label>
                                                <div class="col-md-9">
                                                    <select name="status" class="form-control chosenfield">
                                                        <option value="" disabled selected>----Select----</option>
                                                        <option value="1"
                                                            {{ isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' }}>
                                                            Active</option>
                                                        <option value="2"
                                                            {{ isset($_GET['status']) && $_GET['status'] == '2' ? 'selected' : '' }}>
                                                            Completed</option>
                                                        <option value="0"
                                                            {{ isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : '' }}>
                                                            Not complete</option>
                                                    </select>
                                                    <input type="hidden" name="search" value="1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <button type="button" class="btn btn-warning reset">Reset Filter</button>
                            <button type="button" class="btn btn-info pull-right apply_filter">Apply Filter</button>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of all Job Info</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>User
                                        <div class="sortDiv">
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'user' => 'asc']) }}">
                                                <i class="fa fa-fw fa-sort-asc"></i>
                                            </a>
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'user' => 'desc']) }}">
                                                <i class="fa fa-fw fa-sort-desc"></i>
                                            </a>
                                        </div>
                                    </th>
                                    <th>Company Name
                                        <div class="sortDiv">
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'company_name' => 'asc']) }}">
                                                <i class="fa fa-fw fa-sort-asc"></i>
                                            </a>
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'company_name' => 'desc']) }}">
                                                <i class="fa fa-fw fa-sort-desc"></i>
                                            </a>
                                        </div>
                                    </th>
                                    <th>Date Created
                                        <div class="sortDiv">
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'date_created' => 'asc']) }}">
                                                <i class="fa fa-fw fa-sort-asc"></i>
                                            </a>
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'date_created' => 'desc']) }}">
                                                <i class="fa fa-fw fa-sort-desc"></i>
                                            </a>
                                        </div>
                                    </th>
                                    <th>Project Name
                                        <div class="sortDiv">
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'project_name' => 'asc']) }}">
                                                <i class="fa fa-fw fa-sort-asc"></i>
                                            </a>
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'project_name' => 'desc']) }}">
                                                <i class="fa fa-fw fa-sort-desc"></i>
                                            </a>
                                        </div>
                                    </th>
                                    <th>Customer </th>
                                    <th>Lien Provider</th>
                                    <th>Is Viewable ? </th>
                                    <th>Last Edited Date
                                        <div class="sortDiv">
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'last_edited_date' => 'asc']) }}">
                                                <i class="fa fa-fw fa-sort-asc"></i>
                                            </a>
                                            <a class="sort"
                                                href="{{ route('admin.job.info', ['sort' => '1', 'last_edited_date' => 'desc']) }}">
                                                <i class="fa fa-fw fa-sort-desc"></i>
                                            </a>
                                        </div>
                                    </th>
                                    <th>Status</th>
                                    <th>Date Completed</th>
                                </tr>
                                @if (count($jobInfos) > 0)
                                    @foreach ($jobInfos as $key => $jobInfo)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ !is_null($jobInfo->getProject) && !is_null($jobInfo->getProject['user']) && !is_null($jobInfo->getProject['user']['details']) ? $jobInfo->getProject['user']['details']['first_name'] . ' ' . $jobInfo->getProject['user']['details']['last_name'] : $jobInfo->getProject['user']['email'] }}
                                            </td>
                                            @php
                                                $companyName = 'N/A';
                                                if (!is_null($jobInfo->getProject) && !is_null($jobInfo->getProject['user']) && !is_null($jobInfo->getProject['user']['details']) && !is_null($jobInfo->getProject['user']['details']['getCompany'])) {
                                                    $companyName = $jobInfo->getProject->user->details->getCompany->company;
                                                }
                                            @endphp
                                             <td>{{ $jobInfo->getProject['user']['details'] ? $jobInfo->getProject['user']['details']['company'] :  'N/A' }}</td>
                                            <td>{{ isset($companyName) ? $companyName : 'N/A' }}</td>
                                            <td>{{ date('F d, Y h:i:s A', strtotime($jobInfo->created_at)) }}</td>
                                            <td>{{ !is_null($jobInfo->getProject) ? $jobInfo->getProject->project_name : 'N/A' }}
                                            </td>
                                            <td>{{ !is_null($jobInfo->getProject) && !is_null($jobInfo->getProject->customer_contract) && !is_null($jobInfo->getProject->customer_contract->getContacts) ? $jobInfo->getProject->customer_contract->getContacts->first_name . ' ' . $jobInfo->getProject->customer_contract->getContacts->last_name : 'N/A' }}
                                            </td>
                                            <td>
                                                @php
                                                    $lienProvider = [];
                                                    $lienProviderArray = '';
                                                    $lienProviderHTML = '';
                                                    if (!is_null($jobInfo->getProject['user']) && !is_null($jobInfo->getProject['user']['details']) && $jobInfo->getProject['user']['details']['lien_status'] == '1') {
                                                        //$userProvider = $jobInfo->getProject->user->lienProvider;
                                                        $userProvider = \App\Models\MemberLienMap::where('user_id', $jobInfo->getProject['user']['id'])->get();
                                                        if (count($userProvider) > 0) {
                                                            foreach ($userProvider as $pro) {
                                                                $proL = $pro->findLien;

                                                                // foreach ($pro->getLien as $proL) {
                                                                //  $lienProvider[] = '<tr><td>'.$proL->company.'</td><td>'.$proL->firstName.'</td><td> '.$proL->lastName.'</td><td>'.$proL->email.'</td></tr>';
                                                                //}
                                                                $lienProvider[] = '<tr><td>' . (!is_null($proL->getUser) && !is_null($proL->getUser->details) && !is_null($proL->getUser->details->getCompany) ? $proL->getUser->details->getCompany->company : 'N/A') . '</td><td>' . $proL->firstName . '</td><td> ' . $proL->lastName . '</td><td>' . $proL->email . '</td></tr>';
                                                            }
                                                        }
                                                    }
                                                    if (count($lienProvider) > 0) {
                                                        $lienProviderArray = implode('', $lienProvider);
                                                        $lienProviderHTML =
                                                            '<table class="table table-bordered">
                                                                                                                                    <thead><tr><th>Company</th><th>First Name</th><th>Last Name</th><th>Email</th></tr></thead>
                                                                                                                                    <tbody>
                                                                                                                                      ' .
                                                            $lienProviderArray .
                                                            '
                                                                                                                                    </tbody>
                                                                                                                                  </table>';
                                                    }
                                                @endphp
                                                @if ($lienProviderHTML != '')
                                                    <button class="btn btn-warning btn-sm viewProvider"
                                                        data-provider="{{ $lienProviderHTML }}" type="button"
                                                        title="View Lien Provider">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                @else
                                                    NLB
                                                @endif
                                            </td>
                                            <td>
                                                <input type="checkbox" class="status" data-id="{{ $jobInfo->id }}"
                                                    data-status="{{ $jobInfo->is_viewable }}"
                                                    {{ $jobInfo->is_viewable == '0' ? '' : 'checked' }}
                                                    data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success"
                                                    data-offstyle="danger">
                                            </td>
                                            <td>{{ date('F d, Y h:i:s A', strtotime($jobInfo->updated_at)) }}</td>
                                            @if ($jobInfo->status == 0)
                                                <td>{{ 'Not Completed' }}</td>
                                            @elseif($jobInfo->status == 1 )
                                                <td>{{ 'Active' }}</td>
                                            @else
                                                <td>{{ 'Completed' }}</td>
                                            @endif
                                            <td>{{ $jobInfo->date_completed != '' ? date('F d, Y h:i:s A', strtotime($jobInfo->date_completed)) : 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="11">
                                            <div class="text-center">No Job info available.</div>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{ $jobInfos->appends($_GET)->links() }}
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
            $('.date').datepicker({
                autoclose: true,
                format: 'mm/dd/yyyy'
            });
            $('.reset').on('click', function() {
                var url = '{{ route('admin.job.info') }}';
                window.location.href = url;
            });
            $(document).delegate('.viewProvider', 'click', function() {
                var providers = $(this).data('provider');
                // console.log(providers);
                if (providers != '') {
                    // var providersArray = providers.split('|');
                    // var providerString = '<table class="table table-bordered">' +
                    //     '<thead><tr><th>Company</th><th>First Name</th><th>Last Name</th><th>Email</th></tr></thead><tbody>'+providers+'</tbody></table>';
                    // $.each(providersArray, function (index,value) {
                    //     providerString += value;
                    // });
                    // providerString += '</table>';
                    swal({
                        //html:providerString,aut
                        title: 'Assigned Lien Provider',
                        html: providers,
                        // text: 'NLB',
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
            $('.apply_filter').on('click', function() {
                var datastring = $('.filterForm').serialize();
                var url = '{{ route('admin.job.info') }}?' + datastring;
                window.location.href = url;
            });

            $('.chosenfield').chosen({
                no_results_text: "Oops, nothing found!"
            });
        });

        /**
         * autocomplete functions
         */
        // $('.autocomplete').autocomplete({
        //     source: function (request, response)  {
        //         var key = request.term;
        //         var search = this.element.data('search');
        //         $.ajax({
        //             url: "{{ route('autocomplete.jobinfo') }}",
        //             dataType: "json",
        //             data: {
        //                 search: search,
        //                 key: key,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function (data) {
        //
        //                 var array = $.map(data.data, function (item) {
        //                     return {
        //                         label: item.data,
        //                         value: item.data,
        //                         id: item.id,
        //                         data: item.data,
        //                         value: item.value
        //                     }
        //                 });
        //                 response(array)
        //             }
        //         });
        //     },
        //     minLength: 1,
        //     max: 10,
        //     select: function (event, ui) {
        //         $(this).val(ui.item.value);
        //     }
        // });

        $('.status').on('change', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            var statusName = (status != "1") ? "Activated" : "Deactivated";
            $.ajax({
                type: "POST",
                url: "{{ route('admin.change.viewable') }}",
                data: {
                    id: id,
                    status: status,
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
                        swal({
                            position: 'center',
                            type: 'success',
                            title: 'Status ' + statusName + ' successfully',
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
    </script>
@endsection
