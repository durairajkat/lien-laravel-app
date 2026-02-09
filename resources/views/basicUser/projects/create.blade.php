@extends('basicUser.layout.main')

@section('title', 'Create project')
@section('style')
    <style>
        .select button {
            width: 100%;
            text-align: left;
        }

        .select .caret {
            position: absolute;
            right: 10px;
            margin-top: 10px;
        }

        .select:last-child>.btn {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .selected {
            padding-right: 10px;
        }

        .option {
            width: 100%;
        }

        .input-error {
            border: 2px solid red;

        }
        .padded-table{
            padding-right:60px;
            padding-left:60px;
        }

    </style>
@endsection
@section('content')
    @php
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $mobile =
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) ||
        preg_match(
            '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4),
        );
    @endphp
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
    @if (isset($_GET['edit']))
        <div class="">
        @else
            <div class="">
    @endif
    <div class="">
        <div class="">
            @if (!isset($_GET['hide']))
                @if (!isset($_GET['edit']))
                    <div class="page-head">
                        @if (!isset($_GET['view']) || $_GET['view'] === 'express')

                            @if(session()->has('express'))
                                <h2>Create A New Express Project</h2>
                            @else
                                <h2>Create A New Project</h2>
                            @endif
                            
                        @elseif(isset($_GET['view']) && $_GET['view'] === 'detailed')
                            <h2>File A Claim</h2>
                        @endif
                        @if (!isset($_GET['view']) || $_GET['view'] === 'express')
                            {{-- <div class="progress"> --}}
                            {{-- <div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">Step 1</div> --}}
                            {{-- </div> --}}
                        @elseif(isset($_GET['view']) && $_GET['view'] === 'detailed')
                            {{-- <div class="progress"> --}}
                            {{-- <div class="progress-bar" role="progressbar" style="width: 16.6666666667%" aria-valuenow="16.6666666667" aria-valuemin="0" aria-valuemax="100">Step 1</div> --}}
                            {{-- </div> --}}
                        @endif
                    </div>
                @else
                    <div class="page-head">
                        @if (!isset($_GET['view']) || $_GET['view'] === 'express')

                        @elseif(isset($_GET['view']) && $_GET['view'] === 'detailed')
                            <h1>Edit A Claim</h1>
                        @endif
                    </div>
                @endif
            @endif
            <div class="center-part">
                <!--<h1><a href="{{ route('member.create.project') }}">Create New Project</a></h1>-->
                @if (\Request::get('view') != 'detailed')
                    <div class="tab-area" style="padding:0;margin:0;height:0;">
                    @else
                        <div class="tab-area" style="margin-bottom:40px;">
                @endif
                @if (\Request::route()->getName() == 'member.create.project')
                    <ul class="nav nav-tabs hidden" style="display:none;">
                        <li class="active" id="expressLi"><a data-toggle="tab" href="#home" data-type="express"
                                class="clickExpress">JOB INFO</a></li>
                        <li id="detailedLi"><a data-toggle="tab" href="#menu1" data-type="detailed"
                                class="clickDetailed">DETAILED</a></li>
                    </ul>
                @endif
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        @if (!empty($project))
                        @endif


                    </div>
                    <div id="menu1" class="tab-pane fade">
                        @if (!empty($project))
                        @endif
                    </div>
                </div>
            </div>
            @if (!$mobile && isset($_GET['edit']))
                {{-- <div class="col-sm-3"> --}}
                {{-- <div class="container create-project-form create-project-form--large" style="width:100%; margin:auto; padding:30px 0;"> --}}
                {{-- <div class="create-project-form-header"> --}}
                {{-- <h2>Job Overview</h2> --}}
                {{-- </div> --}}
                {{-- <div id="overview" class="tab-pane fade in active tab-wrapper"> --}}
                {{-- <div class="col-sm-12"> --}}
                {{-- <h3>Job Name:</h3> --}}
                {{-- <h4>{{$project->project_name}}</h4> --}}
                {{-- </div> --}}
                {{-- @if (isset($project->customer_contract->company) && !empty($project->customer_contract->company)) --}}
                {{-- <div class="col-sm-12"> --}}
                {{-- <h3>Customer:</h3> --}}
                {{-- <h4>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?$project->customer_contract->company->company : 'N/A' }}</h4> --}}
                {{-- </div> --}}
                {{-- @endif --}}
                {{-- <div class="col-sm-12"> --}}
                {{-- <h3>Job State:</h3> --}}
                {{-- <h4>{{ $project->state->name }}</h4> --}}
                {{-- </div> --}}
                {{-- <div class="col-md-12"> --}}
                {{-- <h3>Job Type:</h3> --}}
                {{-- <h4>{{ $project->project_type->project_type }}</h4> --}}
                {{-- <div class="view-type view-type--sidebar"> --}}
                {{-- @php --}}
                {{-- if(!is_null($project->answer1) && !empty($question[0])){ --}}
                {{-- if($question[0]->question_order === 1){ --}}
                {{-- switch($question[0]->question) --}}
                {{-- { --}}
                {{-- case 'Is your project Residential or Commercial?': --}}
                {{-- echo ' - ' . $project->answer1; --}}
                {{-- break; --}}
                {{-- case 'Is your project Residential?': --}}
                {{-- if($project->answer1 == 'Yes'){ --}}
                {{-- echo ' - Residential'; --}}
                {{-- } --}}
                {{-- else{ --}}
                {{-- echo ' - Commercial'; --}}
                {{-- } --}}
                {{-- break; --}}
                {{-- case 'Is this Project Residential (i.e an existing owner occupied single family residence)?': --}}
                {{-- if($project->answer1 == 'Yes'){ --}}
                {{-- echo ' - Residential'; --}}
                {{-- } --}}
                {{-- break; --}}
                {{-- case 'Is this a Residential Project?': --}}
                {{-- if($project->answer1 == 'Yes'){ --}}
                {{-- echo ' - Residential'; --}}
                {{-- } --}}
                {{-- } --}}
                {{-- } --}}
                {{-- else{ --}}
                {{-- switch($question[1]->question) --}}
                {{-- { --}}
                {{-- case 'Is your project Residential or Commercial?': --}}
                {{-- echo ' - ' . $project->answer1; --}}
                {{-- break; --}}
                {{-- case 'Is your project Residential?': --}}
                {{-- if($project->answer1 == 'Yes'){ --}}
                {{-- echo ' - Residential'; --}}
                {{-- } --}}
                {{-- else{ --}}
                {{-- echo ' - Commercial'; --}}
                {{-- } --}}
                {{-- break; --}}
                {{-- case 'Is this Project Residential (i.e an existing owner occupied single family residence)?': --}}
                {{-- if($project->answer1 == 'Yes'){ --}}
                {{-- echo ' - Residential'; --}}
                {{-- } --}}
                {{-- break; --}}
                {{-- case 'Is this a Residential Project?': --}}
                {{-- if($project->answer1 == 'Yes'){ --}}
                {{-- echo ' - Residential'; --}}
                {{-- } --}}
                {{-- } --}}
                {{-- } --}}
                {{-- } --}}

                {{-- @endphp --}}
                {{-- </div> --}}
                {{-- @if (isset($question[0])) --}}
                {{-- @php --}}
                {{-- if(!is_null($project->answer1)){ --}}
                {{-- if($question[0]->question_order === 2){ --}}
                {{-- switch($question[0]->question) --}}
                {{-- { --}}
                {{-- case 'Did you supply Specially Fabricated Materials?': --}}
                {{-- if($project->answer2 == 'Yes'){ --}}
                {{-- echo '<div class="view-type view-type--sidebar">'; --}}
                {{-- echo 'You supplied fabricated materials'; --}}
                {{-- echo '</div>'; --}}
                {{-- } --}}
                {{-- else{ --}}
                {{-- echo '<div class="view-type view-type--sidebar">'; --}}
                {{-- echo 'You did not supply fabricated materials'; --}}
                {{-- echo '</div>'; --}}
                {{-- } --}}
                {{-- break; --}}
                {{-- case 'Was the Prime Contract Recorded?': --}}
                {{-- if($project->answer2 == 'Yes'){ --}}
                {{-- echo '<div class="view-type view-type--sidebar">'; --}}
                {{-- echo 'The Prime Contract was Recorded'; --}}
                {{-- echo '</div>'; --}}
                {{-- } --}}
                {{-- else{ --}}
                {{-- echo '<div class="view-type view-type--sidebar">'; --}}
                {{-- echo 'The Prime Contract was not Recorded'; --}}
                {{-- echo '</div>'; --}}
                {{-- } --}}
                {{-- break; --}}
                {{-- } --}}
                {{-- } --}}
                {{-- elseif($question[0]->question_order === 1){ --}}
                {{-- switch($question[0]->question) --}}
                {{-- { --}}
                {{-- case 'Is Notice of Completion or Cessation Recorded?': --}}
                {{-- if($project->answer1 == 'Yes'){ --}}
                {{-- echo '<div class="view-type view-type--sidebar">'; --}}
                {{-- echo 'Notice of Completion or Cessation was Recorded'; --}}
                {{-- echo '</div>'; --}}
                {{-- } --}}
                {{-- else{ --}}
                {{-- echo '<div class="view-type view-type--sidebar">'; --}}
                {{-- echo 'Notice of Completion or Cessation was not Recorded'; --}}
                {{-- echo '</div>'; --}}
                {{-- } --}}
                {{-- break; --}}
                {{-- case 'Is the project  5,000 sf or less or is the project four residential units or less?': --}}
                {{-- if($project->answer1 == 'Yes'){ --}}
                {{-- echo '<div class="view-type view-type--sidebar">'; --}}
                {{-- echo 'The project is 5,000 sf or less or is four residential units or less'; --}}
                {{-- echo '</div>'; --}}
                {{-- } --}}
                {{-- else{ --}}
                {{-- echo '<div class="view-type view-type--sidebar">'; --}}
                {{-- echo 'The project is not 5,000 sf or less or is not four residential units or less'; --}}
                {{-- echo '</div>'; --}}
                {{-- } --}}
                {{-- break; --}}
                {{-- } --}}
                {{-- } --}}

                {{-- } --}}

                {{-- @endphp --}}
                {{-- @endif --}}
                {{-- @if (count($remedyNames) > 0) --}}
                {{-- <div class="view-type view-type--sidebar"> --}}
                {{-- @foreach ($remedyNames as $remedyKey => $name) --}}
                {{-- {{$name}} --}}
                {{-- @if (count($liens) > 0) --}}
                {{-- @foreach ($liens as $key => $lien) --}}
                {{-- @if ($name == $lien['remedy']) --}}

                {{-- <p style="font-size: medium;"><strong>Description:</strong> {{ $lien->description }}</p> --}}
                {{-- <p style="font-size: medium;"><strong>Tiers:</strong> {{ $lien->tier_limit }}</p> --}}

                {{-- @endif --}}
                {{-- @endforeach --}}
                {{-- @endif --}}
                {{-- @endforeach --}}
                {{-- </div> --}}
                {{-- @endif --}}

                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                <div class="col-sm-12 padded-table">
            @endif
            @yield('body')
        </div>
    </div>
    </div>
    </div>
@endsection
@section('modal')
    @yield('modal')
@endsection

@section('script')
    @yield('script')
@endsection
