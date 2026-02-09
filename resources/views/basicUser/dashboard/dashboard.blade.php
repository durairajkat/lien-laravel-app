@extends('basicUser.layout.main')

@section('title', 'Member Dashboard')

@section('content')
    <?php
    $user = auth()->user();
    $userid = $user->id;
    ?>
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
    @php
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $mobile =
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) ||
        preg_match(
            '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4),
        );
    @endphp

    <div class="">
        <div class="">
            <div class="">
                <div class="table-block">
                    <div class="">
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <div class="table-top">
                            <div class="flex items-center">
                                @php
                                    $totalUnpaid = 0;
                                    foreach ($unpaid as $key => $project) {
                                        $totalUnpaid += $project->unpaid_balance;
                                    }
                                @endphp
                                @if (!$mobile)
                                    @if(session()->has('express'))
                                        <a href="{{ route('member.create.express.project') }}" title="Create A Project" class="orange-btn" style="background-color: #1083FF !important;">Add New Project</a>
                                    @else
                                        <a href="{{ route('member.create.project') }}" title="Create A Project" class="orange-btn" style="background-color: #1083FF !important;">Add New Project</a>
                                    @endif
                                @endif
                                {{-- <div>
                                <h2>Manage Projects</h2>
                                @if ($caseProject == 'active')
                                    <div class="activecasesTab">
                                        <a href="javascript:void(0)" class="allCases"><span>View All Cases</span></a>
                                    </div>
                                @else
                                    <div class="allcasesTab">
                                        <a href="javascript:void(0)" class="activeCases"><span>View Active Cases Only</span></a>
                                    </div>
                                @endif
                                </div> --}}
                            
                            </div>
                            <div style="color:#1083FF; font-size:32px; font-weight:700;text-transform: uppercase;">Current Projects</div>
                            <div class="table-middle">
                                <div class="options-wrapper">
                                    @if (!$mobile && ! session()->has('express'))
                                        {{-- <h3 class="project-table-header-claim">Total Unpaid Balance: ${{number_format($totalUnpaid, 2, '.', ',')}}</h3> --}}
                                        <div class="dropdown">
                                            <a class="edit-column dropdown-toggle" type="button"
                                                id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Edit Column Layout <i class="fa fa-angle-down" style="color:#000;"></i>
                                            </a>
                                            <ul class="dropdown-menu" id="dropdownMenu4">
                                                <li><label class="checkbox" for="edit1"><input id="edit1" name="column"
                                                            class="edit1" value="1" type="checkbox">ID</label></li>
                                                <li><label class="checkbox" for="edit2"><input id="edit2" name="column"
                                                            class="edit2" value="2" type="checkbox">Customer Name</label>
                                                </li>
                                                <li><label class="checkbox" for="edit3"><input id="edit3" name="column"
                                                            class="edit3" value="3" type="checkbox">Contact</label></li>
                                                <li><label class="checkbox" for="edit4"><input id="edit4" name="column"
                                                            class="edit4" value="4" type="checkbox">Phone number</label>
                                                </li>
                                                <li><label class="checkbox" for="edit5"><input id="edit5" name="column"
                                                            class="edit5" value="5" type="checkbox">Project Name</label>
                                                </li>
                                                <li><label class="checkbox" for="edit14"><input id="edit14" name="column"
                                                            class="edit14" value="14" type="checkbox">State</label></li>
                                                <li><label class="checkbox" for="edit16"><input id="edit16" name="column"
                                                            class="edit16" value="16" type="checkbox">Address</label></li>
                                                <li><label class="checkbox" for="edit6"><input id="edit6" name="column"
                                                            class="edit6" value="6" type="checkbox">Unpaid Balance</label>
                                                </li>
                                                <li><label class="checkbox" for="edit7"><input id="edit7" name="column"
                                                            class="edit7" value="7" type="checkbox">Status</label></li>
                                                <li><label class="checkbox" for="edit8"><input id="edit8" name="column"
                                                            class="edit8" value="8" type="checkbox">Lien Provider</label>
                                                </li>
                                                <li><label class="checkbox" for="edit9"><input id="edit9" name="column"
                                                            class="edit9" value="9" type="checkbox">Updated On</label></li>
                                                <li><label class="checkbox" for="edit10"><input id="edit10" name="column"
                                                            class="edit10" value="10" type="checkbox">Entry Date</label>
                                                </li>
                                                <li><label class="checkbox" for="edit12"><input id="edit12" name="column"
                                                            class="edit12" value="12" type="checkbox">Deadline</label></li>
                                                <li><label class="checkbox" for="edit15"><input id="edit15" name="column"
                                                            class="edit15" value="15" type="checkbox">Tasks</label></li>
                                                <li><label class="checkbox" for="edit13"><input id="edit13" name="column"
                                                            class="edit13" value="13" type="checkbox">Project
                                                        Documents</label></li>
                                                <li><label class="checkbox" for="edit14"><input id="edit14" name="column"
                                                                                                class="edit14" value="14" type="checkbox">User</label></li>
                                                <li><label class="checkbox" for="edit11"><input id="edit11" name="column"
                                                            class="edit11" value="11" type="checkbox">User Action</label>
                                                </li>
                                                <!--button class="submitcolumn">Save</button-->
                                            </ul>
                                        </div>
                                        {{-- <a href="{{$url}}" title="Express View" class="project-table-header-button">View Express Project Details</a> --}}
                                    @endif
                                    <div class="dropdown-menu dropdown-search" id="dropdownMenu5"
                                        aria-labelledby="dropdownMenuButton">

                                        <input type="text" id="projectSearch" placeholder="Search.."
                                            value="{{ isset($_GET['projectDetails']) && $_GET['projectDetails'] != '' ? $_GET['projectDetails'] : '' }}"
                                            class="form-control">

                                        <!-- <div class="dropdown-menu-filter"> -->
                                        <ul class="statefilter">
                                            <h4><label class="checkbox" for="filter">State</label></h4>
                                            <?php
                                        $s=0;
                                        foreach ($states_list as $state) {?>
                                            <li><label class="checkbox" for="state_<?php echo $s; ?>"><input name="state"
                                                        value="<?php echo $state->id; ?>" class="filter" type="checkbox"
                                                        id="state_<?php echo $s; ?>"><?php echo $state->name; ?></label>
                                            </li> <?php
                                            $s++;
                                        }?>
                                        </ul>

                                        <ul>
                                            <h4><label class="checkbox" for="filter">Customer</label></h4>
                                            <?php
                                        $c=0;
                                        foreach ($customerCodes as $customer) {?>
                                            <li><label class="checkbox" for="customer_<?php echo $c; ?>"><input
                                                        name="customer" value="<?php echo $customer->id; ?>" class="filter"
                                                        type="checkbox"
                                                        id="customer_<?php echo $c; ?>"><?php echo $customer->name; ?></label>
                                            </li> <?php
                                            $c++;
                                        }?>
                                        </ul>

                                        <ul>
                                            <h4><label class="checkbox" for="filter">Role</label></h4>
                                            <?php
                                        $r=0;
                                        foreach ($roles as $role) {?>
                                            <li><label class="checkbox" for="role_<?php echo $r; ?>"><input name="prole"
                                                        value="<?php echo $role->id; ?>" class="filter" type="checkbox"
                                                        id="role_<?php echo $r; ?>"><?php echo $role->project_roles; ?></label>
                                            </li> <?php
                                            $r++;
                                        }?>
                                        </ul>

                                        <ul>
                                            <h4><label class="checkbox" for="filter">Project Type</label></h4>
                                            <?php
                                        $t=0;
                                        foreach ($types as $type) {?>
                                            <li>
                                                <label class="checkbox" for="type_<?php echo $t; ?>">
                                                    <input name="ptype" value="<?php echo $type->id; ?>" class="filter"
                                                        type="checkbox"
                                                        id="filter_<?php echo $t; ?>"><?php echo $type->project_type; ?>
                                                </label>
                                            </li> <?php
                                            $t++;
                                        }?>
                                        </ul>

                                        <button type="submit" data-type="project" class="btn s-button search1">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                            <div class="table-responsive white-table">
                                <table id="tooManyClasses" class="table activecase table-style">
                                    <thead>
                                        <tr>
                                            @if (! session()->has('express'))
                                                <th class="head-sort column_1" data-col="id"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    ID
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'id' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>
                                            @endif
                                            @if (! session()->has('express'))
                                                <th class="head-sort column_2" data-col="customer_name"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    Customer Name
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>

                                                <th class="head-sort column_3" data-col="customer_company_name"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_company_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    Contact
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_company_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>
                                            @endif

                                            <th class="head-sort column_5" data-col="project_name"
                                                data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                Project Name
                                                <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_name' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                    aria-hidden="true"></i>
                                            </th>

                                            @if (! session()->has('express'))
                                                <th class="head-sort column_4" data-col="customer_phone_number"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_phone_number' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    Phone number
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'customer_phone_number' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>
												 @endif
                                                <th class="head-sort column_14" data-col="project_state"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_state' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    State
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_state' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>
												@if (! session()->has('express'))
                                                <th class="head-sort column_16" data-col="job_address"
                                                    data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'job_address' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                    Address
                                                    <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'job_address' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                        aria-hidden="true"></i>
                                                </th>


                                            <th class="head-sort column_6" data-col="unpaid_balance"
                                                data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'unpaid_balance' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                Unpaid Balance
                                                <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'unpaid_balance' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                    aria-hidden="true"></i>
                                            </th>
											@endif


                                            @if (!$mobile)
                                                @if (! session()->has('express'))
                                                    <th class="head-sort column_7" data-col="status"
                                                        data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'status' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                        Status
                                                        <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'status' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                            aria-hidden="true"></i>
                                                    </th>
                                                    <th class="head-sort column_8">
                                                        Lien Provider
                                                    </th>

                                                    <th class="head-sort column_9" data-col="updated_at"
                                                        data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'updated_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                        Updated On
                                                        <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'updated_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                            aria-hidden="true"></i>
                                                    </th>

                                                    <th class="head-sort column_10" data-col="created_at"
                                                        data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'created_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                        Entry Date
                                                        <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'created_at' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                            aria-hidden="true"></i>
                                                    </th>
                                                @endif
												 @if (! session()->has('express'))
                                                <th class="head-sort column_15" data-col="" data-type="">
                                                    Deadline
                                                </th>



                                                    <th class="head-sort column_15" data-col="" data-type="">
                                                        Tasks
                                                    </th>

                                                    <th class="head-sort column_13" data-col="project_documents"
                                                        data-type="{{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_documents' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'DESC' : 'ASC' }}">
                                                        Project Documents
                                                        <i class="fa-large fa {{ isset($_GET['sortWith']) && $_GET['sortWith'] == 'project_documents' && isset($_GET['sortBy']) && $_GET['sortBy'] == 'ASC' ? 'fa-sort-desc' : 'fa-sort-asc' }}"
                                                            aria-hidden="true"></i>
                                                    </th>

                                                        <th class="column_14">
                                                            User
                                                        </th>
                                                <th class="column_11">
                                                    User Action
                                                </th>
												@endif
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


                                                    @if (! session()->has('express'))
                                                        <td class="column_1">
                                                            {{ $project->id }}
                                                        </td>
                                                    @endif

                                                    @if (!$mobile)
                                                        @if (! session()->has('express'))
                                                            <td class="column_2">
                                                                @if ($company != 'N/A')
                                                                    <a
                                                                        href="{{ route('member.create.projectcontacts', [$project->id]) . '?edit=true' }}">
                                                                        {{ $company }}
                                                                    </a>
                                                                @else
                                                                    {{ $company }}
                                                                @endif
                                                            </td>
                                                            <td class="column_3">
                                                                @php
                                                                    $contact = !is_null($project->customer_contract) && !is_null($project->customer_contract->getContacts) ? $project->customer_contract->getContacts->first_name . ' ' . $project->customer_contract->getContacts->last_name : 'N/A';
                                                                @endphp
                                                                @if ($contact != 'N/A')
                                                                    <a
                                                                        href="{{ route('member.create.projectcontacts', [$project->id]) . '?edit=true' }}">
                                                                        {{ $contact }}
                                                                    </a>
                                                                @else
                                                                    {{ $contact }}
                                                                @endif
                                                            </td>
                                                        @endif

                                                        @if ($mobile)
                                                            <td class="column_5">
                                                                <a href="{{ route('member.view.projectMobile', [$project->id]) }}"
                                                                    title="Open Project">{{ substr($project->project_name, 0, 20) }}</a>
                                                            </td>
                                                        @else
                                                            <td class="column_5">
                                                                <a href="{{ route('member.create.edit.jobdescription', [$project->id]) . '?project_id=' . $project->id . '&edit=true' }}"
                                                                    title="Open Project">{{ substr($project->project_name, 0, 20) }}
                                                                </a>
                                                            </td>
                                                        @endif

                                                        @if (! session()->has('express'))
                                                            <td class="column_4">
                                                                @if ($phone_number != 'N/A')
                                                                    <a
                                                                        href="tel:{{ $phone_number }}">{{ $phone_number }}</a>
                                                                @else
                                                                    {{ $phone_number }}
                                                                @endif
                                                            </td>
															@endif

                                                            <td class="column_14">
                                                                {{ $project->state->name }}
                                                            </td>
														@if (! session()->has('express'))
                                                            <td class="column_16">
                                                                <a
                                                                    href="{{ route('member.create.edit.jobdescription', [$project->id]) . '?project_id=' . $project->id . '&edit=true' }}">

                                                                    {{ $project->address . ' ' . $project->city }}
                                                            </td>


                                                        <td class="column_6">
                                                            <a
                                                                href="{{ route('project.contract.view') . '?project_id=' . $project->id . '&edit=true' }}">
                                                                {{ $project->unpaid_balance }}
                                                            </a>
                                                        </td>
													@endif
                                                        @if (! session()->has('express'))
                                                            <td class="column_7">
                                                                @if ($project->status == 1)
                                                                    Active
                                                                @else
                                                                    Inactive
                                                                @endif
                                                            </td>
                                                            <td class="column_8">
                                                                @php
                                                                    $lienProvider = [];
                                                                    $lienProviderArray = [];
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
                                                                    if (count($project->lienProviderByState) > 1) {
                                                                         foreach ($project->lienProviderByState as $proL) {
                                                                             $lienProviderArray[] = '<tr><td>'.$proL->company.'</td><td>'.$proL->firstName.'</td><td> '.$proL->lastName.'</td><td>'.$proL->email.'</td><tr>';
                                                                         }
                                                                        $lienProviderArray = implode(' | ', $lienProviderArray);
                                                                    }
                                                                @endphp
                                                                @if (isset($project->lienProviderByState) && !empty($project->lienProviderByState))
                                                                    @if(isset($lienProviderArray) && !empty($lienProviderArray) )
                                                                        <button class="btn btn-warning btn-sm viewProvider"
                                                                            data-provider="{!! $lienProviderArray !!}"
                                                                            type="button" title="View Lien Provider">
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                    @else
                                                                        {{ (isset($project->lienProviderByState[0]->company) ? $project->lienProviderByState[0]->company : 'N/A') . ' ( ' . $project->lienProviderByState[0]->firstName . ' ' . $project->lienProviderByState[0]->lastName . ' )' }}
                                                                    @endif
                                                                @else
                                                                    <div class="text-info">NLB</div>
                                                                @endif
                                                                {{ !is_null($project->jobInfo) && $project->jobInfo->is_viewable == '1' ? $project->jobInfo->name : '' }}
                                                            </td>

                                                            <td class="column_9">
                                                                <span>
                                                                    {{ date('m/d/Y', strtotime($project->updated_at)) }}
                                                                </span>
                                                            </td>

                                                            <td class="column_10">
                                                                <span>
                                                                    {{ date('m/d/Y', strtotime($project->created_at)) }}
                                                                </span>
                                                            </td>
                                                        @endif
                                                        @php

                                                        @endphp
														 @if (! session()->has('express'))
                                                        <td class="column_12">
                                                            @if (count($project->project_date) > 0)
                                                                <a
                                                                    href="{{ route('create.deadlines', [$project->id]) . '?edit=true' }}">Deadlines</a>
                                                                 <a href="{{ route('create.deadlines', [$project->id]). '?edit=true'}}">{{ $project['preliminaryDates'] }} <br/> {{ $project['preliminaryDatesName'] }}</a>
                                                            @else
                                                                {{ 'N/A' }}
                                                            @endif
                                                        </td>


                                                            <td class="column_15">
                                                                <a
                                                                    href="{{ route('project.task.view') . '/' . $project->id . '?edit=true' }}">
                                                                    TASKS<br/>
                                                                    {{ $project['nextTaskAction'] }} <br/> {{ $project['nextTaskDate'] }}

                                                                </a>
                                                                {{-- <a href="{{ route('create.deadlines', [$project->id]). '?edit=true'}}">{{ $project->project_date[0]['date_value'] }}</a> --}}
                                                            </td>

                                                            <td class="column_13">
                                                                <a
                                                                    href="{{ route('get.project.documents', [$project->id]) . '?edit=true' }}">Project
                                                                    Documents</a>
                                                            </td>
                                                                <td class="column_14">
                                                                    {{ $project->username }}
                                                                </td>


                                                        <td class="column_11">
                                                            {{-- <a href="{{route('vine.job.view'). '?project_id='.$project->id .'&edit=true#dates'}}" style="text-decoration:none;">VIEW / EDIT</a> --}}
                                                            <a
                                                                href="{{ route('member.create.project') . '?project_id=' . $project->id . '&edit=true' }}">VIEW
                                                                / EDIT</a>
                                                            {{-- | <a href="{{route('project.task.view'). '?project_id='.$project->id .'&edit=true'}}">TASKS</a> --}}
                                                            | <a data-id="{{ $project->id }}" class="deleteProject"
                                                                href="#">DELETE</a>
                                                        </td>
														@endif
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
                                            <td colspan="14"
                                                <label>Projects Per Page:</label>
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
							 @if (! session()->has('express'))
                            <!--<div class="imageBanner" style="margin-bottom: 40px;">
                                <a href="https://lienmanager.com/consultation/" target="_blank">
                                    <img src="{{ asset('images/dashimage.jpeg') }}" style="width:100%" />
                                </a>
                            </div>-->

                            @include('basicUser.projects.project_list_filter')
							 @endif

                </div>
            </div>
        </div>
    </div>

    <!--<div class="card-grid">
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
                    <img src="{{ asset('images/consulting-card.png') }}" alt="Card" />
                    <span>Consultation</span>
                </a>
            </div>
        </div>

        {{-- <div class="card-row">
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/research-card.png') }}" alt="Card" />
                    <span>Manage Finances & Receivables</span>
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
        </div> --}}
    </div>-->
@endsection
@section('modal')
    <div id="sortModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="sortForm" class="form-horizontal">
                    <h4 class="modal-title"><span class="modal_title"></span>Sort By:</h4>
                    <label>
                        <input type="radio" name="sortWith" value="project_name">Project Name
                    </label>

                    <label>
                        <input type="radio" name="sortWith" value="updated_at">Next Action Date
                    </label>

                    <label>
                        <input type="radio" name="sortWith" value="state_id">State
                    </label>

                    <label>
                        <input type="radio" name="sortWith" value="customer_name">Customer Name
                    </label>

                    <h4 class="modal-title"><span class="modal_title"></span>Order By:</h4>

                    <label>
                        <input type="radio" name="sortBy" value="ASC">Ascending
                    </label>

                    <label>
                        <input type="radio" name="sortBy" value="DESC">Descending
                    </label>

                    <input type="submit" class="project-create-continue project-save-quit--view-button project-button-blue"
                        id="submitSort" value="Sort">
                    <a href="" class="project-save-quit project-save-quit--view-button project-red-cancel">Cancel</a>
                    <a href="{{ route('member.dashboard') }}"
                        class="project-save-quit project-save-quit--view-button float-right">Reset to Default</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <style type="text/css">
        /* #tooManyClasses tr th,#tooManyClasses tr td{display:none;} */
        /*.options-wrapper{position:relative;}*/
        #dropdownMenu5 {
            position: absolute;
            left: 0;
            top: 47px;
            width: 100%;
            z-index: 100;
            background: #fff;
            width: 100%;
            border: 1px solid #000;
            border-radius: 1px;
        }

        .dropdown-menu-filter ul,
        #dropdownMenu5 ul {
            width: 100%;
            padding-left: 20px;
            float: left;
            margin: 0;
        }

        #dropdownMenu4 label.checkbox {
            margin: 5px 0;
        }

        #dropdownMenu4 li {
            width: 100%;
            float: left;
            margin: 0 0 0 16px;
            color: #000;
            font-size: 12px;
            background: none;
        }

        #dropdownMenu5 ul li label {
            margin: 0;
            word-break: break-all;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        #dropdownMenu5 ul li label input[type=checkbox] {
            margin: 1px 0 0 -15px;
        }

        .dropdown-menu-filter ul li,
        #dropdownMenu5 ul li {
            float: left;
            text-align: left;
            background: none;
            font-size: 11px;
            color: #000;
        }

        ul.statefilter li {
            width: 16%;
            height: 25px;
        }

        #dropdownMenu5 ul h4 {
            text-align: left;
            color: #000;
            text-indent: -9px;
        }

        .dropdown-menu-filter ul li .checkbox,
        .dropdown-menu-filter ul li .radio,
        ,
        #dropdownMenu5 ul li {
            margin: 0;
        }

    </style>
    <script>
        $(document).ready(function() {
            $('.dropdown-menu').click(function(e) {
                e.stopPropagation();
            });
            var favorite = [];
            $.each($("input[name='column']:checked"), function() {
                favorite.push($(this).val());
            });
            $('#dropdownMenu4 li label input').click(function() {
                var val = $(this).val();
                if ($(this).prop("checked") == true) {

                    $("#tooManyClasses tr th.column_" + val + "").show();
                    $("#tooManyClasses tbody tr td.column_" + val + "").show();
                    favorite.push($(this).val());
                    // alert("Checkbox is checked.");
                } else if ($(this).prop("checked") == false) {
                    // alert("Checkbox is unchecked.");
                    $("#tooManyClasses tr th.column_" + val + "").hide();
                    $("#tooManyClasses tbody tr td.column_" + val + "").hide();
                    favorite.splice($.inArray(val, favorite), 1);
                }

                var str = favorite.join(",");

                savecoumn(str);

            });
        });






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







        function savecoumn(str) {
            var userid = <?php echo $userid; ?>;
            // alert("My favourite sports are: " + favorite.join(","));
            $.ajax({
                type: 'POST',
                url: "{{ route('user.columnAdd') }}",
                data: {
                    userid: userid,
                    columns: str,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    if (data.status) {
                        /*  swal({
                            position: 'center',
                            type: 'success',
                            title: 'Column updated successfully',
                        }).then(function () {
                        window.location.reload();
                    }); */
                    } else {
                        swal(
                            'Error',
                            data.message,
                            'error'
                        )
                    }
                }
            });
        }

        let url = "{{ route('member.dashboard') }}"
        $('#sort-projects').on('click', function() {
            $('#sortModal').modal('show')
        })

        $('.project-red-cancel').on('click', function() {
            $('#sortModal').modal('hide')
        })

        $('#sortForm').on('submit', function(e) {
            e.preventDefault();
            let sortWith = $("input[name='sortWith']:checked").val()
            let sortBy = $("input[name='sortBy']:checked").val()
            let urlFull = url + "?sortBy=" + sortBy + "&sortWith=" + sortWith
            window.location = urlFull
        })

        $('.allCases').on('click', function() {
            var location = appendToQueryString('case', 'all');
            window.location.href = location;
        });

        $('.activeCases').on('click', function() {
            var location = appendToQueryString('case', 'active');
            window.location.href = location;
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

            $('.search11').on('click', function() {
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('#dropdownMenu3').click(function() {
                $('.dropdown-menu-filter').slideToggle();
            });

            $(".submitcolumn").click(function() {
                // $("#dropdownMenu2").text('Save Column Layout');
                var favorite = [];
                $.each($("input[name='column']:checked"), function() {
                    favorite.push($(this).val());
                });

                var str = favorite.join(",");
                var userid = <?php echo $userid; ?>;
                // alert("My favourite sports are: " + favorite.join(","));
                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.columnAdd') }}",
                    data: {
                        userid: userid,
                        columns: str,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status) {
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Column updated successfully',
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
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".search1").click(function() {
                var type = $(this).data('type');
                // alert(type);
                if (type == 'project') {
                    var project = $('#projectSearch').val();
                }
                var stateValue = [];
                var typeValue = [];
                var roleValue = [];
                var customerValue = [];

                $.each($("input[name='state']:checked"), function() {
                    stateValue.push($(this).val());
                });

                $.each($("input[name='ptype']:checked"), function() {
                    typeValue.push($(this).val());
                });

                $.each($("input[name='prole']:checked"), function() {
                    roleValue.push($(this).val());
                });

                $.each($("input[name='customer']:checked"), function() {
                    customerValue.push($(this).val());
                });
                // var filters = filterValue.join(",");
                var pstate = stateValue.join(",");
                var ptype = typeValue.join(",");
                var prole = roleValue.join(",");
                var pcustomer = customerValue.join(",");
                //var location = appendToQueryString('projectDetails', project);
                var location = "https://www.nlb711.slysandbox.com/member?projectDetails=" + project +
                    "&state=" + pstate + "&ptype=" + ptype + "&prole=" + prole + "&customer=" + pcustomer +
                    "";
                window.location.href = location;
            });
        });
    </script>

    @if(! session()->has('express'))
        <?php $val = explode(',', $userscustom->custom);
        echo '<script>$("#tooManyClasses tbody tr td").hide();</script>';
        echo '<script>$("#tooManyClasses tr th").hide();</script>';
        for ($i = 0; $i < count($val); $i++) {
            $column = trim($val[$i]);
            echo '<script>
                        $("#tooManyClasses tr th.column_' .
                $column .
                '").show();
                        $("#tooManyClasses tbody tr td.column_' .
                $column .
                '").show();
                    </script>';

            echo '<script>
                        $(".dropdown-menu li label.checkbox").each(function(){
                            $("input.edit' .
                $column .
                '",this).prop("checked", true );
                        });
                    </script>';
        }
        ?>
    @endif
@endsection
