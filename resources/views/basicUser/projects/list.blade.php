@extends('basicUser.layout.main')

@section('title', 'Projects')

@section('style')

    <style>
        .datepicker,
        .table-condensed {
            width: 215px;
            padding: 0px;
        }

        .blue-btn-ext {
            background: #1084ff;
            color: #fff;
        }

        .chzn-container-single-nosearch .chzn-search input {
            - position: absolute;
            - left: -9000px;
            +display: none;
        }

    </style>

@endsection

@section('content')
    @php
    use App\Models\CompanyContact;
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $mobile =
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) ||
        preg_match(
            '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4),
        );
    @endphp
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
    <div style="">
        <div>
            <div>
                <div class="table-block">
                    <div>
                        <div>
                            <div class="project-table-header">
                                <div>
                                    <h2>Reports</h2>
                                </div>
                                @php
                                    $totalUnpaid = 0;
                                    try {
                                        foreach ($unpaid as $key => $project) {
                                            $totalUnpaid += $project->unpaid_balance;
                                        }
                                    } catch (Exception $e) {
                                    }
                                @endphp

                                <div>
                                    <div class="flex flex-col">
                                        @if (!$mobile && !isset($_GET['view']))
                                            <div class="flex items-center total">
                                                <div class="flex flex-col items-center justify-center" style="width: 50%">
                                                    <span class="total-small">Total Unpaid Balance:</span>
                                                    <span
                                                        class="total-amount">${{ number_format($totalUnpaid, 2, '.', ',') }}</span>
                                                </div>
                                                <div class="middle-bar"></div>
                                                <div class="flex flex-col items-center justify-center" style="width: 50%">
                                                    <span class="total-small">Total Active Projects:</span>
                                                    <span
                                                        class="total-amount">{{ number_format($active_projects, 0, '.', ',') }}</span>
                                                </div>
                                            </div>

                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end items-center">
                                <div style="position: relative;">
                                    <span class="search dropdown-toggle" id="dropdownMenuButton1" role="alert" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <a href="#">
                                            <img src="{{ url('/images/search-icon.png') }}" alt="img" class="search-icon">
                                        </a>
                                    </span>
                                    <div class="dropdown-menu dropdown-search" aria-labelledby="dropdownMenuButton">

                                        <input type="text" id="projectSearch" placeholder="Search.."
                                            value="{{ isset($_GET['projectDetails']) && $_GET['projectDetails'] != '' ? $_GET['projectDetails'] : '' }}"
                                            class="form-control">
                                        <button type="submit" data-type="project" class="btn s-button search1"><i
                                                class="fa fa-search" aria-hidden="true"></i></button>

                                    </div>
                                </div>

                                @if (!$mobile && !isset($_GET['view']))
                                    @php
                                        $url = url('/') . '/member/project';
                                        if (isset($_GET['sortBy'])) {
                                            $url = $url . '?view=express&sortBy=' . $_GET['sortBy'] . '&sortWith=' . $_GET['sortWith'];
                                        } else {
                                            $url = $url . '?view=express';
                                        }
                                    @endphp
                                    <a href="{{ $url }}" title="Express View"
                                        class="header-button">View Express Project Details</a>
                                @elseif(!$mobile && isset($_GET['view']) && $_GET['view'] === 'express')
                                    @php
                                        $url = url('/') . '/member/project';
                                        if (isset($_GET['sortBy'])) {
                                            $url = $url . '?sortBy=' . $_GET['sortBy'] . '&sortWith=' . $_GET['sortWith'];
                                        } else {
                                            $url = $url;
                                        }
                                    @endphp
                                    <a href="{{ $url }}" title="View Expanded"
                                        class="header-button">View Expanded Project Details</a>
                                @endif
                                @if (!$mobile)
                                    <a href="{{ route('member.create.project') }}" title="Create A Project"
                                        class="header-button">Create A Project</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <div class="table-responsive white-table">

                            <table id="tooManyClasses" class="table-responsive activecase table-style">
                                <thead>
                                    <tr>
                                        @if (!$mobile)
                                            <th class="head-sort" data-col="id"
                                                data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                ID
                                                <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                    aria-hidden="true"></i>
                                            </th>
                                        @endif
                                        <th class="head-sort" data-col="project_name"
                                            data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                            Project Name
                                            <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                aria-hidden="true"></i>
                                        </th>
                                        @if (!$mobile)
                                            @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')
                                                <th class="head-sort" data-col="project_type_id"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_type_id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    Type
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_type_id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>
                                            @endif
                                            <th class="head-sort" data-col="state_id"
                                                data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'state_id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                State
                                                <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'state_id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                    aria-hidden="true"></i>
                                            </th>
                                            @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')
                                                <th class="head-sort" data-col="customer_company_name"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_company_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    Customer Name
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_company_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>
                                            @endif
                                            <th class="head-sort" data-col="customer_name"
                                                data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                Contact
                                                <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                    aria-hidden="true"></i>
                                            </th>
                                            @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')
                                                <th class="head-sort" data-col="customer_phone_number"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_phone_number' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    Customer Phone number
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_phone_number' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>
                                            @endif

                                            <th class="head-sort" data-col="unpaid_balance"
                                                data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'unpaid_balance' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                Unpaid Balance
                                                <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'unpaid_balance' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                    aria-hidden="true"></i>
                                            </th>
                                            @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')
                                                <th class="head-sort" data-col="status"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'status' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    Status
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'status' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>
                                                <th>
                                                    Lien Provider
                                                </th>
                                            @endif
                                            <th class="head-sort" data-col="updated_at"
                                                data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'updated_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                Updated On
                                                <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'updated_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                    aria-hidden="true"></i>
                                            </th>
                                            @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')

                                                <th class="head-sort" data-col="created_at"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'created_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    Entry Date
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'created_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>
                                            @endif
                                            <th>
                                                Documents
                                            </th>
                                            <th>
                                                User Action
                                            </th>

                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($projects) > 0)
                                        @foreach ($projects as $key => $project)
                                            @php
                                                $company = 'N/A';
                                                $phone_number = 'N/A';
                                                if (!is_null($project->customer_contract) && !is_null($project->customer_contract->getContacts)) {
                                                    if ($project->customer_contract->getContacts->type == '0') {
                                                        $phone_number = !is_null($project->customer_contract->getContacts->phone) ? $project->customer_contract->getContacts->phone : 'N/A';
                                                        $company = !is_null($project->customer_contract) && !is_null($project->customer_contract->company) ? $project->customer_contract->company->company : 'N/A';
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                @if (!$mobile)
                                                    <td>{{ $project->id }}</td>
                                                @endif
                                                @if ($mobile)
                                                    <td><a href="{{ route('member.view.projectMobile', [$project->id]) }}"
                                                            title="Open Project">{{ $project->project_name }}</a>
                                                    </td>
                                                @else
                                                    <td><a href="{{ route('member.create.project') . '?project_id=' . $project->id . '&edit=true' }}"
                                                            title="Open Project">{{ substr($project->project_name, 0, 20) }}</a>
                                                    </td>
                                                @endif
                                                @if (!$mobile)
                                                    @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')
                                                        <td>{{ $project->project_type->project_type }}</td>
                                                    @endif
                                                    <td>{{ $project->state->name }}</td>
                                                    @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')
                                                        <td>{{ isset($company) ? $company : 'N/A' }}</td>
                                                    @endif

                                                    <td>
                                                        @php
                                                            $contact = !is_null($project->customer_contract) && !is_null($project->customer_contract->getContacts) ? $project->customer_contract->getContacts->first_name . ' ' . $project->customer_contract->getContacts->last_name : 'N/A';
                                                        @endphp
                                                        @if ($contact != 'N/A')
                                                            <a
                                                                href="{{ route('project.summary.view', [$project->id]) . '?edit=true&contact=true' }}">
                                                                {{ $contact }}
                                                            </a>
                                                        @else
                                                            {{ $contact }}
                                                        @endif
                                                    </td>

                                                    @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')
                                                        <td>
                                                            {{-- {{ isset($phone_number) ? $phone_number :'N/A' }} --}}
                                                            @if ($phone_number != 'N/A')
                                                                <a
                                                                    href="tel:{{ $phone_number }}">{{ $phone_number }}</a>
                                                            @else
                                                                {{ $phone_number }}
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <a
                                                            href="{{ route('project.contract.view') . '?project_id=' . $project->id . '&edit=true' }}">
                                                            {{ $project->unpaid_balance }}
                                                        </a>
                                                    </td>
                                                    @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')
                                                        <td>
                                                            @if ($project->status == 1)
                                                                Active
                                                            @else
                                                                Inactive
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $lienProvider = [];
                                                                $lienProviderArray = '';
                                                                if (!is_null($project->user) && !is_null($project->user->details) && $project->user->details != '' && $project->user->details->lien_status == '1') {
                                                                    $userProvider = $project->user->lienProvider;
                                                                    if (!is_null($userProvider) && $userProvider != '') {
                                                                        foreach ($userProvider as $pro) {
                                                                            foreach ($pro->getLien as $proL) {
                                                                                $lienProvider[] = '<tr><td>' . $proL->company . '</td><td>' . $proL->firstName . '</td><td> ' . $proL->lastName . '</td><td>' . $proL->email . '</td><tr>';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                if (count($lienProvider) > 0) {
                                                                    $lienProviderArray = implode(' | ', $lienProvider);
                                                                }
                                                            @endphp
                                                            @if (!is_null($project->jobInfo) && $project->jobInfo->is_viewable == '1')
                                                                <button class="btn btn-warning btn-sm viewProvider"
                                                                    data-provider="{!! $lienProviderArray !!}" type="button"
                                                                    title="View Lien Provider">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            @else
                                                                <div class="text-info">NLB</div>
                                                            @endif
                                                            {{ !is_null($project->jobInfo) && $project->jobInfo->is_viewable == '1' ? $project->jobInfo->name : '' }}
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <span>

                                                            {{ date('m/d/Y', strtotime($project->updated_at)) }}

                                                        </span>
                                                    </td>
                                                    @if (!isset($_GET['view']) || $_GET['view'] === 'expanded')
                                                        <td>
                                                            <span>

                                                                {{ date('m/d/Y', strtotime($project->created_at)) }}

                                                            </span>
                                                        </td>


                                                    @endif
                                                    <td>
                                                        <a
                                                            href="{{ route('get.project.documents', [$project->id]) . '?edit=true' }}">Project
                                                            Documents</a>
                                                    </td>

                                                    <td>
                                                        <a
                                                            href="{{ route('project.task.view') . '?project_id=' . $project->id . '&edit=true' }}">TASKS</a>
                                                        |
                                                        <a
                                                            href="{{ route('member.create.project') . '?project_id=' . $project->id . '&edit=true' }}">EDIT</a>
                                                        | <a data-id="{{ $project->id }}" class="deleteProject"
                                                            href="#">DELETE</a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="14">
                                                No projects found
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="14">
                                            {{ $projects->appends($_GET)->links() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="14">
                                            <select name="paginate" id="paginate">
                                                <option value="" @if (isset($_GET['paginate']) && $_GET['paginate'] == ''){{ 'selected' }}@endif>---Select---</option>
                                                <option value="20" @if (isset($_GET['paginate']) && $_GET['paginate'] == '20'){{ 'selected' }}@endif>20</option>
                                                <option value="50" @if (isset($_GET['paginate']) && $_GET['paginate'] == '50'){{ 'selected' }}@endif>50</option>
                                                <option value="100" @if (isset($_GET['paginate']) && $_GET['paginate'] == '100'){{ 'selected' }}@endif>100</option>
                                                <option value="10000" @if (isset($_GET['paginate']) && $_GET['paginate'] == '10000'){{ 'selected' }}@endif>All</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @include('basicUser.projects.project_list_filter')
            </div>
        </div>

            <div class="card-grid">
        <div class="card-row">
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/files-card.png') }}" alt="Card" />
                    <span>File Your Claim</span>
                </a>
            </div>
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/money-card.png') }}" alt="Card" />
                    <span>Collect Receivables</span>
                </a>
            </div>
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/research-card.png') }}" alt="Card" />
                    <span>Manage Finances & Receivables</span>
                </a>
            </div>
        </div>
        <div class="card-row">
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/consulting-card.png') }}" alt="Card" />
                    <span>Consultation</span>
                </a>
            </div>
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/graph-card.png') }}" alt="Card" />
                    <span>Generate Reports</span>
                </a>
            </div>
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/books-card.png') }}" alt="Card" />
                    <span>Document Library</span>
                </a>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@section('script')
    <script>
        $('.chosen').chosen({
            width: "100%"
        });

        $('#paginate').change(function() {
            if ($(this).val() != '') {
                var location = window.location.href;
                var location = appendToQueryString('paginate', $(this).val());
                window.location.href = location;
            } else {
                var location = window.location.href;
                location = removeURLParameter(location, 'paginate');
                window.location.href = location;
            }
        });

        function removeURLParameter(url, parameter) {
            //prefer to use l.search if you have a location/link object
            var urlparts = url.split('?');
            if (urlparts.length >= 2) {

                var prefix = encodeURIComponent(parameter) + '=';
                var pars = urlparts[1].split(/[&;]/g);

                //reverse iteration as may be destructive
                for (var i = pars.length; i-- > 0;) {
                    //idiom for string.startsWith
                    if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                        pars.splice(i, 1);
                    }
                }

                url = urlparts[0] + '?' + pars.join('&');
                return url;
            } else {
                return url;
            }
        }

        $(document).ready(function() {
            $('.express').hide();
            $('.expand_field').hide();
            $('.expand').on('click', function() {
                $('.expand_field').show();
                $('.express').show();
                $('.expand').hide();
            });
            $('.express').on('click', function() {
                $('.expand_field').hide();
                $('.express').hide();
                $('.expand').show();
            });
            $('.deleteProject').on('click', function() {
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
            appendToQueryString = function(param, val) {
                var queryString = window.location.search.replace("?", "");
                var parameterListRaw = queryString == "" ? [] : queryString.split("&");
                var parameterList = {};
                for (var i = 0; i < parameterListRaw.length; i++) {
                    var parameter = parameterListRaw[i].split("=");
                    if (parameter[0] != 'page') {
                        parameterList[parameter[0]] = parameter[1];
                    }
                }
                parameterList[param] = val;
                var newQueryString = "?";
                for (var item in parameterList) {
                    if (parameterList.hasOwnProperty(item)) {
                        newQueryString += item + "=" + parameterList[item] + "&";
                    }
                }
                newQueryString = newQueryString.replace(/&$/, "");
                return location.origin + location.pathname + newQueryString;
            }

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
            $('.search1').on('click', function() {
                var type = $(this).data('type');
                if (type == 'project') {
                    var project = $('#projectSearch').val();
                    var location = appendToQueryString('projectDetails', project);
                    window.location.href = location;
                }

            });
            $('.head-sort').on('click', function() {
                var col = $(this).data('col');
                var type = $(this).data('type');
                var location = window.location.href;
                var location = replaceUrlParam(location, 'sortBy', type);
                var location = replaceUrlParam(location, 'sortWith', col);
                window.location.href = location;
            });
        });

        $('.autocomplete').autocomplete({
            source: function(request, response) {
                var term = request.term;
                $.ajax({
                    url: "{{ route('project.autocomplete') }}",
                    dataType: "json",
                    data: {
                        keyword: term
                    },
                    delay: 1000,
                    success: function(data) {
                        var array = $.map(data.data, function(item) {
                            return {
                                label: item.project_name,
                                value: item.project_name,
                                id: item.id
                            }
                        });
                        response(array)
                    }
                });
            },
            minLength: 1,
            max: 5,
            select: function(event, ui) {
                $('#id1').val(ui.item.id);
            }
        });

        $(document).delegate('.viewProvider', 'click', function() {
            var providers = $(this).data('provider');
            if (providers != '') {
                var providersArray = providers.split('|');
                var providerString = '<table class="table-responsive table-bordered">' +
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
