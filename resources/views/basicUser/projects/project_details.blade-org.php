@extends('basicUser.projects.create')

@section('body')
@php
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    $mobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
@endphp
@if(isset($_GET['edit']))
<span id="stepNum" data-step="1"></span>

@include('basicUser.partials.multi-step-form')

@endif
@if(isset($_GET['edit']))
    <span id="editFlag"></span>
    <form action="#" method="post" class="form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit create-project-form--large" style="width:100%; margin:auto;">
@else
    <form action="#" method="post" class="form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit create-project-form--large" style="width:100%; margin:auto;">
@endif

        @if(isset($_GET['edit']))
        <div class="row buttons-on-top">
            <a href="javascript:void(0)" id="skip-button-1-out" class="skip-job-dates project-create-skip">
                Skip
            </a>
            <a href="javascript:void(0)" id="activate-step-2-out" class="project-create-continue">
                Save & Continue
            </a>
        </div>
@endif
        <div class="create-project-form-bgcolor">

        <div class="create-project-form-header">
            @if(!isset($_GET['edit']))
            <h2>Add Your Project Details</h2>
            @else
            <h2>Edit Project Details</h2>
            @endif
            @if(isset($_GET['edit']) and $mobile)
            <span class="mobile-nav--menu" onclick="openNav()" data-target="detailed"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></span>
            @endif
        </div>
        @if(isset($_GET['edit']))
            {{--<div class="edit-nav">--}}
                {{--@if(!$mobile)--}}
                {{--<div class="main-tab-menu menu--gray">--}}
                    {{--<ul class="nav nav-tabs">--}}
                        {{--<li class="active">--}}
                            {{--<a href="{{ route('member.create.project') . '?project_id='.$project_id.'&edit=true'}}">Project Overview</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="{{ route('create.remedydates', [$project_id]) . '?edit=true'}}">Job Dates</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="{{ route('create.deadlines', [$project_id]). '?edit=true'}}">Deadlines</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="{{ route('member.create.edit.jobdescription', [$project_id]). '?edit=true'}}">Job Description</a>--}}

                        {{--</li>--}}
                        {{--<li>--}}
                            {{--@php--}}
                                {{--$contractRoute = route('project.contract.view') . '?project_id='. $project_id . '&edit=true';--}}
                            {{--@endphp--}}
                            {{--<a href="{{$contractRoute}}">Contract Details</a>--}}

                        {{--</li>--}}
                        {{--<li >--}}
                            {{--<a href="{{ route('member.create.projectcontacts', [$project_id]). '?edit=true'}}">Contacts</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="{{ route('get.job.info.sheet', [$project_id])}}" target="_blank">Job Information Sheet</a>--}}

                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
                {{--@else--}}
                {{--<div class="select-view sidenav" id="mobileNavDetailed" data-menu="detailed">--}}
                    {{--<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>--}}
                    {{--<h3>Edit A Project</h3>--}}
                    {{--<div class="mobile-overview">--}}
                        {{--<h3>Job Name:</h3>--}}
                        {{--<h4>{{$project->project_name}}</h4>--}}
                        {{--@if(isset($project->customer_contract->company) && !empty($project->customer_contract->company))--}}
                            {{--<h3>Customer:</h3>--}}
                            {{--<h4>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?$project->customer_contract->company->company : 'N/A' }}</h4>--}}

                        {{--@endif--}}
                        {{--<h3>Job State:</h3>--}}
                        {{--<h4>{{ $project->state->name }}</h4>--}}
                        {{--<h3>Job Type:</h3>--}}
                        {{--<h4>{{ $project->project_type->project_type }}</h4>--}}
                    {{--</div>--}}
                    {{--<a href="{{ route('member.create.project') . '?project_id='.$project_id.'&edit=true'}}">Project Overview</a>--}}
                    {{--<a href="{{ route('create.remedydates', [$project_id]). '?edit=true'}}">Job Dates</a>--}}
                    {{--<a href="{{ route('create.deadlines', [$project_id]). '?edit=true'}}">Deadlines</a>--}}
                    {{--<a href="{{ route('member.create.edit.jobdescription', [$project_id]). '?edit=true'}}">Job Description</a>--}}
                    {{--@php--}}
                        {{--$contractRoute = route('project.contract.view') . '?project_id='. $project_id . '&edit=true';--}}
                    {{--@endphp--}}
                    {{--<a href="{{$contractRoute}}">Contract Details</a>--}}
                    {{--<a href="{{ route('member.create.projectcontacts', [$project_id]). '?edit=true'}}">Contacts</a>--}}
                    {{--<a href="{{ route('get.job.info.sheet', [$project_id])}}" target="_blank">Job Information Sheet</a>--}}
                {{--</div>--}}
                {{--@endif--}}
            {{--</div>--}}
        <div class="form-padding-wrapper">
            <div class="status-wrapper">
                @if($project->status == 1)
                    <h4>Project Status: Active</h4>
                @else
                    <h4>Project Status: Inactive</h4>
                @endif
                <button class="toggle-status" data-switch="{{($project->status == 1 ? 0 : 1)}}"> {{($project->status == 1 ? 'Make Inactive' : 'Make Active')}}</button>
            </div>
        @endif
            <input class="tab-view" type="hidden" data-type="express">
            @if(isset($project->project_name))
                <input type="hidden" name="project_name"
                       value="{{ isset($project->project_name) ? $project->project_name : '' }}">
            @endif
            {{ csrf_field() }}
            <input type="hidden" name="form_type"
                   value="{{ (isset($project) && $project != '')?'edit':'add' }}">
            {{--<input type="hidden" name="project_id" value="{{ isset($project->project_name) ? $project->project_name :'0' }}">--}}
            <input type="hidden" name="project_id"
                   value="{{ (isset($project) && $project_id > 0)?$project_id:'0' }}">
        <div class="tab-content-body except-detailed">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="row">
                                @if(!(isset($project->project_name)))
                                    <div class="col-md-12 col-sm-12">
                                        <div class="border-table">
                                            <label for="p_name">Project Name</label>
                                            @if(isset($preferences) && !empty($preferences) && !isset($_GET['edit']))
                                            <input type="text" class="form-control input-field" name="project_name"
                                                   id="p_name"
                                                   value="{{$preferences->project_name}}" data-clear="false" required>
                                            @else
                                            <input type="text" class="form-control input-field" name="project_name"
                                                   id="p_name"
                                                   value="{{  !Session::has('projectName') ? ((isset($project) && $project != '') ? $project->project_name : '') : Session::get('projectName') }}" required>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="border-table">
                                        <label for="state">Project Location By State</label>
                                        @if(isset($preferences) && !empty($preferences) && !isset($_GET['edit']))
                                        <select name="state" class="form-control checkTier input-field"
                                                id="state" required>
                                            <option value="">Select a state</option>
                                            @foreach($states as $state)
                                            @if($preferences->state_id === $state->id)
                                            <option value="{{ $state->id }}"
                                                    data="{{ $state->name }}" selected>{{ $state->name }}</option>
                                            @else
                                            <option value="{{ $state->id }}"
                                                        data="{{ $state->name }}" {{ Session::has('state') && Session::get('state') == $state->id ? 'selected'  : ((isset($project) && $project != '' && $project->state_id == $state->id)?'selected':'') }}>{{ $state->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @else
                                        <select name="state" class="form-control checkTier input-field"
                                                id="state" required>
                                            <option value="">Select a state</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}"
                                                        data="{{ $state->name }}" {{ Session::has('state') && Session::get('state') == $state->id ? 'selected'  : ((isset($project) && $project != '' && $project->state_id == $state->id)?'selected':'') }}>{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="border-table">
                                        <label for="project_type">Project Type</label>
                                        @if(isset($preferences) && !empty($preferences) && !isset($_GET['edit']))
                                        <select name="type" class="form-control checkTier input-field"
                                                id="project_type" required>
                                            <option value="">Select a project type</option>
                                            @foreach($types as $type)
                                                @if($preferences->project_type_id === $type->id)
                                                <option value="{{ $type->id }}" selected>{{ $type->project_type }}</option>
                                                @else
                                                <option value="{{ $type->id }}" {{ Session::has('projectType') && Session::get('projectType') == $type->id ? 'selected' : ((isset($project) && $project != '' && $project->project_type_id == $type->id)?'selected':'') }}>{{ $type->project_type }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @else
                                        <select name="type" class="form-control checkTier input-field"
                                                id="project_type" required>
                                            <option value="">Select a project type</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->id }}" {{ Session::has('projectType') && Session::get('projectType') == $type->id ? 'selected' : ((isset($project) && $project != '' && $project->project_type_id == $type->id)?'selected':'') }}>{{ $type->project_type }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="border-table">
                                        <label for="role">Your Role</label>
                                        @if(isset($preferences) && !empty($preferences) && !isset($_GET['edit']))
                                        <select name="role" class="form-control checkTier input-field"
                                                id="role" required>
                                            <option value="">Select a role</option>
                                            @foreach($roles as $role)
                                            @if($preferences->role_id === $role->id)
                                            <option value="{{ $role->id }}" selected>{{ $role->project_roles }}</option>
                                            @else
                                                <option value="{{ $role->id }}" {{ Session::has('role') && Session::get('role') == $role->id ? 'selected' : ((isset($project) && $project != '' && $project->role_id == $role->id)?'selected':'') }}>{{ $role->project_roles }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @else
                                        <select name="role" class="form-control checkTier input-field"
                                                id="role" required>
                                            <option value="">Select a role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ Session::has('role') && Session::get('role') == $role->id ? 'selected' : ((isset($project) && $project != '' && $project->role_id == $role->id)?'selected':'') }}>{{ $role->project_roles }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="border-table">
                                        <label for="customer">Your Customer</label>
                                        @if(isset($preferences) && !empty($preferences) && !isset($_GET['edit']))
                                        <select name="customer" class="form-control input-field"
                                                id="customer">
                                            @if(Session::has('customer') && Session::get('customer') != '')
                                                <option value="{{ Session::get('customer') }}">{{ Session::get('customer_name') }}</option>
                                            @else
                                            @foreach($customerCodes as $cust)
                                            @if($preferences->customer_id === $cust->id)
                                            <option value="{{ $cust->id }}" selected>{{ $cust->name }}</option>
                                            @else
                                            <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
                                        @else
                                        <select name="customer" class="form-control input-field"
                                                id="customer">
                                            @if(Session::has('customer') && Session::get('customer') != '')
                                                <option value="{{ Session::get('customer') }}">{{ Session::get('customer_name') }}</option>
                                            @else
                                                @if(isset($project) && $project != '')
                                                    <option value="{{ $project->customer_id }}">{{ $project->originalCustomer->name }}</option>
                                                @else
                                                    <option value="">Select your customer</option>
                                                @endif
                                            @endif
                                        </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row question" style="display: none;">
                                <div class="col-md-12 col-sm-12">
                                    <div class="border-table">
                                        <div id="question"></div>
                                    </div>
                                </div>
                            </div>
                            @if(!isset($_GET['edit']))
{{--                            <div class="row">--}}
{{--                                <div class="col-md-12 col-sm-12">--}}
{{--                                    <div class="user-preferences">--}}
{{--                                        <input type="checkbox" id="preferences" value="saveSettings"/>--}}
{{--                                        <label for="preferences">Save these settings for next time.</label>--}}

{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            @endif
                        </div>
                        @php
                            $useragent=$_SERVER['HTTP_USER_AGENT'];
                            $mobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
                        @endphp

                    </div>
                </div>

            </div>
        </div>
        </div><!-- form-padding-wrapper -->
    </div>

        <div class="">
            @if(!isset($_GET['view']) || $_GET['view'] === 'express')
                @if(!isset($_GET['edit']))
                    <a href="javascript:void(0)" id="addContactsStep-out" class="orange-btn">
                        Add Project
                    </a>
                @else

                    <a href="javascript:void(0)" id="skip-button-1" class="skip-job-dates project-create-skip">
                        Skip
                    </a>
                    <a href="javascript:void(0)" id="activate-step-2" class="orange-btn">
                        Save & Continue
                    </a>

                @endif
            @else
                @if(isset($_GET['edit']))
                    <a href="javascript:void(0)" id="activate-step-2" class="orange-btn">
                        Save
                    </a>

                @else
                    <a href="javascript:void(0)" id="activate-step-2" class="orange-btn">
                        Save
                    </a>
                @endif
            @endif
            <div class="tab-right">
                @if(!isset($_GET['edit']))
                    <ul>
                        @if($project_id)
                            <li>
                                @if(!isset($_GET['view']))
                                    <a href="{{ $project_id ? route('project.lien.view').'?project_id='.$project_id : 'javascript:void(0)' }}">Skip</a>
                                @elseif(isset($_GET['view']) && $_GET['view'] != 'detailed')
                                    <a href="{{ $project_id ? route('project.lien.view').'?project_id='.$project_id.'&view='.$_GET['view'] : 'javascript:void(0)' }}">Skip</a>
                                @else
                                    <a href="{{ $project_id ? route("project.contract.view").'?project_id='.$project_id.'&view='.$_GET['view'] : 'javascript:void(0)' }}">Skip</a>
                                @endif
                                {{-- @if(isset($_GET['view']))
                                     <a href="{{ $project_id ? route('project.contract.view').'?project_id='.$project_id.'&view='.$_GET['view'] :'javascript:void(0)' }}">Skip</a>
                                 @else
                                     <a href="{{ $project_id ? route('project.contract.view').'?project_id='.$project_id :'javascript:void(0)' }}">Skip</a>
                                 @endif--}}
                            </li>
                        @endif

                    </ul>
                @endif
                @if(isset($preferences) && !empty($preferences) && !isset($_GET['edit']))
                    <a href="javascript:void(0)" id="clear_form" class="project-save-quit">
                        Clear Form
                    </a>
                @endif
            </div>
        </div>




    </form>
@endsection
@section('modal')
    @include('basicUser.modals.contact_modal',['companies' => $companies,'first_names' => $first_names])
@endsection
@section('script')

<script>
$(document).ready(function(){
    $(document).on('click', '.mobile-nav-tab', function(){
        let tab = $(this).attr('data-tab')
        $('.mobile-nav--menu').attr('data-target', tab)
    })
    $(document).on('click', '.sidenav', function(){
        $(".sidenav").css('width', '0px');
    })
})
function openNav(e) {
    let menu = $('.mobile-nav--menu').attr('data-target')
    if(menu == 'express'){

        $('#mobileNav').css('width', '100%');
    }
    else{
        $('#mobileNavDetailed').css('width', '100%')
    }
}

function closeNav() {
    $(".sidenav").css('width', '0px');
}
</script>
    <script>
        var saveSession = "{{ route('project.save.session') }}";
        var submitContactForm = "{{ route('customer.submit.contact') }}";
        var userId = '{{ Auth::user()->id }}';
        var token = '{{ csrf_token() }}';
        var projectSubmit = "{{ route('project.details.submit') }}";
        @if(isset($_GET['edit']))
        let edit = true
        @else
        let edit = false
        @endif
        var remedyURL = "{{ route('create.remedydates', [0]) }}"
        var view = '{{ isset($_GET['view']) ? $_GET['view'] : 'express' }}';
        var lienUrl = "{{ route('project.lien.view') }}";
        var contractUrl = '{{ route("project.contract.view") }}';
        var memberProject = '{{ route("member.project") }}';
        var checkRole = "{{ route('project.role.check') }}";
        var checkQuestion = "{{ route('project.check.question') }}";
        var project_id = '{{ isset($project_id) ? $project_id : '0' }}';
        var condition = '0';
        var autoCompleteCompanyOnRoleChange = "{{ route('autocomplete.contact.company.rolechange') }}";
        var fetchCompanies = "{{ route('fetch.companies') }}";
        @if((Session::has('state') && Session::get('state') > 0 &&
            Session::has('role') && Session::get('role') > 0 &&
            Session::has('customer') && Session::get('customer') > 0 &&
            Session::has('projectType') && Session::get('projectType') > 0)
            || (isset($project) && $project_id > 0)
            )
                condition = '1';
        @endif
        var answer1 = "{!! isset($project->answer1) ? $project->answer : '' !!}";
        var answer2 = "{!! isset($project->answer2) ? $project->answer2 : '' !!}";
        var checkName = "{{ route('project.name.check') }}";
        var url = '{{ env('ASSET_URL') }}';
        var page = 'project_details';
        var autoComplete = "{{ route('autocomplete.contact.company') }}";
        var autoCompleteCompany = "{{ route('autocomplete.company') }}";
        var addFileUrl = "{{ route('add.job.info.file') }}";
        var baseUrl = "{{ env('ASSET_URL') }}";
        var customerContactRoute = "{{ route('customer.submit.contact') }}";
        var user_id = "{{ Auth::user()->id }}";
        var autoCompleteContact = "{{ route('autocomplete.contact.firstname') }}";
        var editJobDescriptionRoute = "{{ route('edit.job.description') }}";
        var getContactData = "{{ route('get.contact.data') }}";
        var newContacts = "{{ route('create.new.contacts') }}";
        let projectData = ''
        if($('#editFlag').length > 0){
            projectData = "{{(isset($project->status) ? $project->status : 0)}}"
        }
        let statusChange = "{{route('project.status.update')}}"
        $(document).ready(function(){
            $('.toggle-status').on('click', function(e){
                e.preventDefault()
                let toggleStatus = $('.toggle-status').attr('data-switch')
                $.ajax({
                    type: "POST",
                    url: statusChange,
                    data: {
                        id: project_id,
                        status: projectData,
                        _token: token
                    },
                    success: function (data) {
                        window.location.reload()
                    }
                });
            })
        })
    </script>

    <script src="{{ env('ASSET_URL') }}/js/project_details.js?r=<?=time()?>"></script>
    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/contact.js"></script>
    @if(isset($preferences) && !empty($preferences))
    <script>
    let ans1 = "{{$preferences->answer1}}"
    let ans2 = "{{$preferences->answer2}}"

    $(document).on('ready', function(){
        $(document).on('click', '#clear_form', function(){
            $('#p_name').val('')
            $('#state').val('')
            $('#project_type').val('')
            $('#role').val('')
            $('#customer').val('')
            $('#question').html('');

        })
        $('#question').html('');
        var role = $('#role').val();
        if (role == '') {
            return false;
        }
        var customer = $('#customer').val();
        if (customer == '') {
            return false;
        }
        var state = $('#state').val();
        if (state == '') {
            return false;
        }
        var project_type = $('#project_type').val();
        if (project_type == '') {
            return false;
        }
        $.ajax({
            type: "POST",
            url: checkQuestion,
            data: {
                role: role,
                customer: customer,
                state: state,
                project_type: project_type,
                _token: token
            },
            success: function (data) {
                if (data.status) {
                    var html = '';
                    $.each(data.data, function (indexMain, question) {
                        html += '<div class="row">' +
                            '<label class="push-30">' + question.question + '</label>' +
                            '<div class="col-md-12">';
                        $.each(question.answer, function (index, value) {
                            if (index == 0 || value === ans1 || value === ans2) {
                                html += '<label class="radio-label"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '" checked>' + value + '</label>';
                            } else {
                                html += '<label class="radio-label"><input type="radio" class="error" name="answer[' + question.id + ']" value="' + value + '">' + value + '</label>';
                            }
                        });
                        html += '</div></div>'
                    });
                    $('#question').html(html);
                    $('.question').show();
                } else {
                    $('#question').html('');
                    $('.question').hide();
                }
            }
        });
    })
    @endif
    </script>
@endsection
