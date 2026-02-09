<form method="get">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <table class="table bottomTable">
                <tr>
                    {{-- <td>
                        <strong>Project Status:</strong>
                        <select class="form-control" name="project_status">
                            <option value="all"
                                @if (isset($queryParams) && array_key_exists('projectStatus', $queryParams) && $queryParams['projectStatus'] == 'all')
                                    {{'selected'}}
                                @endif
                            >All Status</option>
                            <option value="in-process"
                                @if (isset($queryParams) && array_key_exists('projectStatus', $queryParams) && $queryParams['projectStatus'] == 'in-process')
                                    {{'selected'}}
                                @endif
                            >In Process</option>
                            <option value="complete"
                                @if (isset($queryParams) && array_key_exists('projectStatus', $queryParams) && $queryParams['projectStatus'] == 'complete')
                                    {{'selected'}}
                                @endif
                            >Complete</option>
                            <option value="deleted"
                                @if (isset($queryParams) && array_key_exists('projectStatus', $queryParams) && $queryParams['projectStatus'] == 'deleted')
                                    {{'selected'}}
                                @endif
                            >Deleted</option>
                        </select>
                    </td> --}}
                    <td>
                        <strong>Job Info Status:</strong>
                        <select class="form-control chosen" name="job_info_status">
                            <option value="all" @if (isset($queryParams) && array_key_exists('jobinfoStatus', $queryParams) && $queryParams['jobinfoStatus'] == 'all')
                                {{ 'selected' }}
                                @endif
                                >All Status</option>
                            <option value="0" @if (isset($queryParams) && array_key_exists('jobinfoStatus', $queryParams) && $queryParams['jobinfoStatus'] == '0')
                                {{ 'selected' }}
                                @endif
                                >Incomplete</option>
                            <option value="1" @if (isset($queryParams) && array_key_exists('jobinfoStatus', $queryParams) && $queryParams['jobinfoStatus'] == '1')
                                {{ 'selected' }}
                                @endif
                                >Active</option>
                            <option value="2" @if (isset($queryParams) && array_key_exists('jobinfoStatus', $queryParams) && $queryParams['jobinfoStatus'] == '2')
                                {{ 'selected' }}
                                @endif
                                >Complete</option>
                        </select>
                    </td>
                    <td>
                        <strong>Project Type:</strong>
                        <select class="form-control chosen" name="project_type">
                            <option value="all" @if (isset($queryParams) && array_key_exists('projectType', $queryParams) && $queryParams['projectType'] == 'all')
                                {{ 'selected' }}
                                @endif
                                > All Types </option>
                            @isset($projectTypes)
                                @forelse($projectTypes as $key => $projectType)
                                    <option value="{{ $key }}" @if (isset($queryParams) && array_key_exists('projectType', $queryParams) && $queryParams['projectType'] == $key)
                                        {{ 'selected' }}
                                @endif
                                >{{ $projectType }}</option>
                            @empty
                                @endforelse
                            @endisset
                        </select>
                    </td>
                    <td>
                        <strong>Project State:</strong>
                        <select class="form-control chosen" name="project_state" id="project_state">
                            <option value="all" @if (isset($queryParams) && array_key_exists('projectState', $queryParams) && $queryParams['projectState'] == 'all')
                                {{ 'selected' }}
                                @endif
                                > All States </option>
                            @isset($states)
                                @forelse($states as $key => $state)
                                    <option value="{{ $key }}" @if (isset($queryParams) && array_key_exists('projectState', $queryParams) && $queryParams['projectState'] == $key)
                                        {{ 'selected' }}
                                @endif
                                > {{ $state }} </option>
                            @empty
                                @endforelse
                            @endisset
                        </select>
                    </td>
                    <td>
                        <strong>Receivable Status :</strong>
                        <select class="form-control chosen" name="receivable_status">
                            <option value="all" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'all')
                                {{ 'selected' }}
                                @endif
                                >
                                All Status
                            </option>

                            <option value="Preliminary Notice" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Preliminary Notice')
                                {{ 'selected' }}
                                @endif
                                >
                                Preliminary Notice
                            </option>
                            <option value="Lien" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Lien')
                                {{ 'selected' }}
                                @endif
                                >
                                Lien
                            </option>
                            <option value="Bond" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Bond')
                                {{ 'selected' }}
                                @endif
                                >
                                Bond
                            </option>
                            <option value="Collection" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Collection')
                                {{ 'selected' }}
                                @endif
                                >
                                Collection
                            </option>
                            <option value="Litigation" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Litigation')
                                {{ 'selected' }}
                                @endif
                                >
                                Litigation
                            </option>
                            <option value="Collected" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Collected')
                                {{ 'selected' }}
                                @endif
                                >
                                Collected
                            </option>
                            <option value="Paid" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Paid')
                                {{ 'selected' }}
                                @endif
                                >
                                Paid
                            </option>
                            <option value="Written Off" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Written Off')
                                {{ 'selected' }}
                                @endif
                                >
                                Written Off
                            </option>
                            <option value="Bankruptcy" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Bankruptcy')
                                {{ 'selected' }}
                                @endif
                                >
                                Bankruptcy
                            </option>
                            <option value="Settled" @if (isset($queryParams) && array_key_exists('receivableStatus', $queryParams) && $queryParams['receivableStatus'] == 'Settled')
                                {{ 'selected' }}
                                @endif
                                >
                                Settled
                            </option>
                        </select>
                    </td>
                    <td>
                        <strong>Claim Amount:</strong>
                        <select class="form-control chosen" name="claim_amount">
                            <option value="all" @if (isset($queryParams) && array_key_exists('claimAmount', $queryParams) && $queryParams['claimAmount'] == 'all')
                                {{ 'selected' }}
                                @endif
                                >All Amounts</option>
                            <option value="0-10000" @if (isset($queryParams) && array_key_exists('claimAmount', $queryParams) && $queryParams['claimAmount'] == '0-10000')
                                {{ 'selected' }}
                                @endif
                                >0-10,000</option>
                            <option value="10000-50000" @if (isset($queryParams) && array_key_exists('claimAmount', $queryParams) && $queryParams['claimAmount'] == '10000-50000')
                                {{ 'selected' }}
                                @endif
                                >10,000-50,000</option>
                            <option value="50000-100000" @if (isset($queryParams) && array_key_exists('claimAmount', $queryParams) && $queryParams['claimAmount'] == '50000-100000')
                                {{ 'selected' }}
                                @endif
                                >50,000-100,000</option>
                            <option value="100000-500000" @if (isset($queryParams) && array_key_exists('claimAmount', $queryParams) && $queryParams['claimAmount'] == '100000-500000')
                                {{ 'selected' }}
                                @endif
                                >100,000-500,000</option>
                            <option value="500000+" @if (isset($queryParams) && array_key_exists('claimAmount', $queryParams) && $queryParams['claimAmount'] == '500000+')
                                {{ 'selected' }}
                                @endif
                                >500,000+</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>Customer:</strong>
                        <select class="form-control chosen" name="customers">
                            <option value="all" @if (isset($queryParams) && array_key_exists('customer', $queryParams) && $queryParams['customer'] == 'all')
                                {{ 'selected' }}
                                @endif
                                >All Customers</option>
                            @if (isset($customers))
                                @forelse($customers as $key => $customer)
                                    <option value="{{ $key }}" @if (isset($queryParams) && array_key_exists('customer', $queryParams) && $queryParams['customer'] == $key)
                                        {{ 'selected' }}
                                @endif
                                >{{ $customer }}</option>
                            @empty
                            @endforelse
                            @endif
                        </select>
                    </td>
                    <td>
                        <strong>Project Name:</strong>
                        <select class="form-control autocomplete chosen" name="project_name">
                            <option value="" @if (isset($queryParams) && array_key_exists('projectName', $queryParams) && $queryParams['projectName'] != '')
                                {{ 'selected' }}
                                @endif
                                >All Projects</option>
                            @if (isset($projectNames))
                                @forelse($projectNames as $key => $projectName)
                                    <option value="{{ $key }}" @if (isset($queryParams) && array_key_exists('projectName', $queryParams) && $queryParams['projectName'] == $key)
                                        {{ 'selected' }}
                                @endif
                                >{{ $projectName }}</option>
                            @empty
                            @endforelse
                            @endif
                        </select>
                        {{-- <select type="text" class="form-control autocomplete chosen" name="project_name" value="{{(isset($queryParams) && array_key_exists('projectName',$queryParams) && $queryParams['projectName'] != '' ) ? $queryParams['projectName'] : '' }}"> --}}
                    </td>

                    <td>
                        <strong>Client Name:</strong>
                        <select class="form-control chosen" name="user">
                            <option value="all" @if (isset($queryParams) && array_key_exists('user', $queryParams) && $queryParams['user'] == 'all')
                                {{ 'selected' }}
                                @endif
                                >All clients</option>
                            @if (isset($subUsers))
                                @forelse($subUsers as $key => $subUser)
                                    <option value="{{ $key }}" @if (isset($queryParams) && array_key_exists('user', $queryParams) && $queryParams['user'] == $key)
                                        {{ 'selected' }}
                                @endif
                                >{{ $subUser }}</option>
                            @empty
                            @endforelse
                            @endif
                        </select>
                    </td>
                    {{-- <td>
                        <strong>Compliance Management:</strong>
                        <select class="form-control chosen" name="compliance_management">
                            <option value="default"
                                @if (isset($queryParams) && array_key_exists('complianceManagement', $queryParams) && $queryParams['complianceManagement'] == 'default')
                                    {{'selected'}}
                                @endif
                            >
                                Default
                            </option>
                            <option value="completed"
                                @if (isset($queryParams) && array_key_exists('complianceManagement', $queryParams) && $queryParams['complianceManagement'] == 'completed')
                                    {{'selected'}}
                                @endif
                            >
                                Completed
                            </option>
                            <option value="incompleted"
                                @if (isset($queryParams) && array_key_exists('complianceManagement', $queryParams) && $queryParams['complianceManagement'] == 'incompleted')
                                    {{'selected'}}
                                @endif
                            >
                                Incompleted
                            </option>
                        </select>
                    </td> --}}
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <strong>Date Type:</strong>
                        <select class="form-control chosen" name="date_type">
                            <option value="all" @if (isset($queryParams) && array_key_exists('dateType', $queryParams) && $queryParams['dateType'] == 'all')
                                {{ 'selected' }}
                                @endif
                                >All</option>
                            <option value="EntryDate" @if (isset($queryParams) && array_key_exists('dateType', $queryParams) && $queryParams['dateType'] == 'EntryDate')
                                {{ 'selected' }}
                                    @endif
                            >Entry Date</option>
                            <option value="NextActionDate" @if (isset($queryParams) && array_key_exists('dateType', $queryParams) && $queryParams['dateType'] == 'NextActionDate')
                                {{ 'selected' }}
                                    @endif
                            >Next Action Date</option>
                        </select>
                    </td>
                    <td>
                        <strong>From:</strong>
                        <input type="text" class="form-control date" name="from"
                            value="{{ isset($queryParams) && array_key_exists('from', $queryParams) && $queryParams['from'] != '' ? $queryParams['from'] : '' }}">
                    </td>
                    <td>
                        <strong>To:</strong>
                        <input type="text" class="form-control date" name="to"
                            value="{{ isset($queryParams) && array_key_exists('to', $queryParams) && $queryParams['to'] != '' ? $queryParams['to'] : '' }}">
                    </td>
                    <td>
                        <strong>Compliance Provider:</strong>
                        <select class="form-control chosen" name="compliance_provider">
                            <option value="all" @if (isset($queryParams) && array_key_exists('complianceProvider', $queryParams) && $queryParams['complianceProvider'] == 'all')
                                {{ 'selected' }}
                                @endif
                                >All</option>
                            @isset($lienProviders)
                                @forelse($lienProviders as $key => $lienProvider)
                                    <option value="{{ $key }}" @if (isset($queryParams) && array_key_exists('complianceProvider', $queryParams) && $queryParams['complianceProvider'] == $key)
                                        {{ 'selected' }}
                                @endif
                                >{{ $lienProvider }}</option>
                            @empty
                                @endforelse
                            @endisset
                        </select>
                    </td>
                    <td style="padding-top: 28px;">
                        <button type="submit" class="btn btn-info form-control">Update list</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
