@extends('basicUser.layout.main')
@section('title', 'Project Details')
@section('style')
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
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

        .multiple {
            margin-bottom: 5px;
        }

        .row .date-space {
            border-bottom: 1px solid rgba(0, 0, 0, .1);
            padding: 10px;
        }

        .row .date-space:last-of-type {
            border-bottom: none;
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
    <div class="project-details-wrapper" id="main">
        @if (!$mobile)
            <div class="col-sm-3">
                <div class="container create-project-form create-project-form--large"
                    style="width:100%; margin:auto; padding:30px 0;">
                    <div class="create-project-form-header">
                        <h2>Job Overview</h2>
                    </div>
                    <div id="overview" class="tab-pane fade in active tab-wrapper">
                        <div class="col-sm-12">
                            <h3>Job Name:</h3>
                            <h4>{{ $project->project_name }}</h4>
                        </div>
                        @if (isset($project->customer_contract->company) && !empty($project->customer_contract->company))
                            <div class="col-sm-12">
                                <h3>Customer:</h3>
                                <h4>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ? $project->customer_contract->company->company : 'N/A' }}
                                </h4>
                            </div>
                        @endif
                        <div class="col-sm-12">
                            <h3>Job State:</h3>
                            <h4>{{ $project->state->name }}</h4>
                        </div>
                        <div class="col-md-12">
                            <div class="view-type view-type--sidebar">
                                {{ $project->project_type->project_type }}
                                @php
                                    if (!is_null($project->answer1) && !empty($question[0])) {
                                        if ($question[0]->question_order === 1) {
                                            switch ($question[0]->question) {
                                                case 'Is your project Residential or Commercial?':
                                                    echo ' - ' . $project->answer1;
                                                    break;
                                                case 'Is your project Residential?':
                                                    if ($project->answer1 == 'Yes') {
                                                        echo ' - Residential';
                                                    } else {
                                                        echo ' - Commercial';
                                                    }
                                                    break;
                                                case 'Is this Project Residential (i.e an existing owner occupied single family residence)?':
                                                    if ($project->answer1 == 'Yes') {
                                                        echo ' - Residential';
                                                    }
                                                    break;
                                                case 'Is this a Residential Project?':
                                                    if ($project->answer1 == 'Yes') {
                                                        echo ' - Residential';
                                                    }
                                            }
                                        } else {
                                            switch ($question[1]->question) {
                                                case 'Is your project Residential or Commercial?':
                                                    echo ' - ' . $project->answer1;
                                                    break;
                                                case 'Is your project Residential?':
                                                    if ($project->answer1 == 'Yes') {
                                                        echo ' - Residential';
                                                    } else {
                                                        echo ' - Commercial';
                                                    }
                                                    break;
                                                case 'Is this Project Residential (i.e an existing owner occupied single family residence)?':
                                                    if ($project->answer1 == 'Yes') {
                                                        echo ' - Residential';
                                                    }
                                                    break;
                                                case 'Is this a Residential Project?':
                                                    if ($project->answer1 == 'Yes') {
                                                        echo ' - Residential';
                                                    }
                                            }
                                        }
                                    }
                                    
                                @endphp
                            </div>
                            @if (isset($question[0]))
                                @php
                                    if (!is_null($project->answer1)) {
                                        if ($question[0]->question_order === 2) {
                                            switch ($question[0]->question) {
                                                case 'Did you supply Specially Fabricated Materials?':
                                                    if ($project->answer2 == 'Yes') {
                                                        echo '<div class="view-type view-type--sidebar">';
                                                        echo 'You supplied fabricated materials';
                                                        echo '</div>';
                                                    } else {
                                                        echo '<div class="view-type view-type--sidebar">';
                                                        echo 'You did not supply fabricated materials';
                                                        echo '</div>';
                                                    }
                                                    break;
                                                case 'Was the Prime Contract Recorded?':
                                                    if ($project->answer2 == 'Yes') {
                                                        echo '<div class="view-type view-type--sidebar">';
                                                        echo 'The Prime Contract was Recorded';
                                                        echo '</div>';
                                                    } else {
                                                        echo '<div class="view-type view-type--sidebar">';
                                                        echo 'The Prime Contract was not Recorded';
                                                        echo '</div>';
                                                    }
                                                    break;
                                            }
                                        } elseif ($question[0]->question_order === 1) {
                                            switch ($question[0]->question) {
                                                case 'Is Notice of Completion or Cessation Recorded?':
                                                    if ($project->answer1 == 'Yes') {
                                                        echo '<div class="view-type view-type--sidebar">';
                                                        echo 'Notice of Completion or Cessation was Recorded';
                                                        echo '</div>';
                                                    } else {
                                                        echo '<div class="view-type view-type--sidebar">';
                                                        echo 'Notice of Completion or Cessation was not Recorded';
                                                        echo '</div>';
                                                    }
                                                    break;
                                                case 'Is the project  5,000 sf or less or is the project four residential units or less?':
                                                    if ($project->answer1 == 'Yes') {
                                                        echo '<div class="view-type view-type--sidebar">';
                                                        echo 'The project is 5,000 sf or less or is four residential units or less';
                                                        echo '</div>';
                                                    } else {
                                                        echo '<div class="view-type view-type--sidebar">';
                                                        echo 'The project is not 5,000 sf or less or is not four residential units or less';
                                                        echo '</div>';
                                                    }
                                                    break;
                                            }
                                        }
                                    }
                                    
                                @endphp
                            @endif
                            @if (count($remedyNames) > 0)
                                <div class="view-type view-type--sidebar">
                                    @foreach ($remedyNames as $remedyKey => $name)
                                        {{ $name }}
                                        @if (count($liens) > 0)
                                            @foreach ($liens as $key => $lien)
                                                @if ($name == $lien['remedy'])

                                                    <p style="font-size: medium;"><strong>Description:</strong>
                                                        {{ $lien->description }}</p>
                                                    <p style="font-size: medium;"><strong>Tiers:</strong>
                                                        {{ $lien->tier_limit }}</p>

                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="container create-project-form create-project-form--large" style="width:100%; margin:auto;">
                    <div class="create-project-form-header">
                        <h2>Job Details</h2>
                        <div class="select-view" style="display:none;">
                            <ul class="nav nav-tabs"  id="myTab">
                                <li><a id="express" data-toggle="tab" href="#express">Express</a></li>
                                <li class="active"><a id="detailed" data-toggle="tab" href="#detailed">Detailed</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row dateBreak dateBreak--margin">
                        <div class="col-md-12">
                            <div class="tab-area">
                                <div class="tab-content">
                                    <div class="border-table border-table--project-details">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="main-tab-sec">

                                                    <div class="tab-content tab-final">

                                                    @else
                                                        <!-- Mobile View for Project Details -->
                                                        <div
                                                            class="container create-project-form create-project-form--large">
                                                            <div class="create-project-form-header">
                                                                <h2>Job Details</h2>
                                                                <span class="mobile-nav--menu" onclick="openNav()"
                                                                    data-target="detailed"><i class="fa fa-ellipsis-v"
                                                                        aria-hidden="true"></i></span>
                                                            </div>
                                                            <div class="row dateBreak dateBreak--margin">
                                                                <div class="col-md-12">
                                                                    <div class="tab-area">
                                                                        <div class="tab-content">
                                                                            <div
                                                                                class="border-table border-table--project-details">
                                                                                <div class="row">
                                                                                    <div class="col-md-12 col-sm-12">
                                                                                        <div class="main-tab-sec">

                                                                                            <div
                                                                                                class="tab-content tab-final">


        @endif

        <div id="detailed" class="tab-pane fade in active">

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="main-tab-sec">
                        @if (!$mobile)
                            <div class="main-tab-menu menu--gray">
                                <ul class="nav nav-tabs">
                                    <li class="active">

                                        <a data-toggle="tab" href="#home">Job Deadlines</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#contracts">Job Contract Information</a>

                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#project-dates">Job Dates</a>

                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#tasks">Job Tasks</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('get.job.info.sheet', [$project_id]) }}" target="_blank">File A
                                            Claim</a>

                                    </li>
                                </ul>
                            </div>
                        @else
                            <div class="select-view sidenav" id="mobileNavDetailed" data-menu="detailed">
                                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

                                <h3>Navigation</h3>
                                <a data-toggle="tab" href="#projectOverviewDetailed">Project Overview</a>
                                <a data-toggle="tab" href="#home">Deadlines</a>
                                <a data-toggle="tab" href="#lienLawChartDetailed">Lien Slide Chart</a>
                                <a data-toggle="tab" href="#contracts">Contract</a>
                                <a data-toggle="tab" href="#project-dates">Project Dates</a>
                                <a data-toggle="tab" href="#tasks">Tasks</a>
                                <a href="{{ route('get.job.info.sheet', [$project_id]) }}" target="_blank">File A
                                    Claim</a>
                            </div>
                        @endif
                        <div class="tab-content tab-final">

                            <div id="lienLawChartDetailed" class="tab-pane fade tab-wrapper text-center">
                                <div class="box">
                                    <div class="head-top">
                                        {{ $project->state->name }}

                                    </div>
                                    <div class="head-top2">
                                        {{ $project->project_type->project_type }}
                                        @php
                                            if (!is_null($project->answer1) && !empty($question[0])) {
                                                if ($question[0]->question_order === 1) {
                                                    switch ($question[0]->question) {
                                                        case 'Is your project Residential or Commercial?':
                                                            echo ' - ' . $project->answer1;
                                                            break;
                                                        case 'Is your project Residential?':
                                                            if ($project->answer1 == 'Yes') {
                                                                echo ' - Residential';
                                                            } else {
                                                                echo ' - Commercial';
                                                            }
                                                            break;
                                                        case 'Is this Project Residential (i.e an existing owner occupied single family residence)?':
                                                            if ($project->answer1 == 'Yes') {
                                                                echo ' - Residential';
                                                            } else {
                                                                echo ' - Commercial';
                                                            }
                                                            break;
                                                        case 'Is this a Residential Project?':
                                                            if ($project->answer1 == 'Yes') {
                                                                echo ' - Residential';
                                                            } else {
                                                                echo ' - Commercial';
                                                            }
                                                    }
                                                } else {
                                                    switch ($question[1]->question) {
                                                        case 'Is your project Residential or Commercial?':
                                                            echo ' - ' . $project->answer1;
                                                            break;
                                                        case 'Is your project Residential?':
                                                            if ($project->answer1 == 'Yes') {
                                                                echo ' - Residential';
                                                            } else {
                                                                echo ' - Commercial';
                                                            }
                                                            break;
                                                        case 'Is this Project Residential (i.e an existing owner occupied single family residence)?':
                                                            if ($project->answer1 == 'Yes') {
                                                                echo ' - Residential';
                                                            } else {
                                                                echo ' - Commercial';
                                                            }
                                                            break;
                                                        case 'Is this a Residential Project?':
                                                            if ($project->answer1 == 'Yes') {
                                                                echo ' - Residential';
                                                            } else {
                                                                echo ' - Commercial';
                                                            }
                                                    }
                                                }
                                            }
                                            
                                        @endphp
                                    </div>
                                    @if (isset($question[0]) && !empty($question[0]))
                                        @php
                                            if (!is_null($project->answer1)) {
                                                if ($question[0]->question_order === 2) {
                                                    switch ($question[0]->question) {
                                                        case 'Did you supply Specially Fabricated Materials?':
                                                            if ($project->answer2 == 'Yes') {
                                                                echo '<div class="panel-group" id="accordion">
                                                                                                                                                                <div class="panel panel-default">
                                                                                                                                                                    <div class="panel-heading">
                                                                                                                                                                        <h4 class="panel-title">';
                                                                echo 'You supplied fabricated materials';
                                                                echo '
                                                                                                                                                                        </h4>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>';
                                                            } else {
                                                                echo '<div class="panel-group" id="accordion">
                                                                                                                                                                <div class="panel panel-default">
                                                                                                                                                                    <div class="panel-heading">
                                                                                                                                                                        <h4 class="panel-title">';
                                                                echo 'You did not supply fabricated materials';
                                                                echo '
                                                                                                                                                                        </h4>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>';
                                                            }
                                                            break;
                                                        case 'Was the Prime Contract Recorded?':
                                                            if ($project->answer2 == 'Yes') {
                                                                echo '<div class="panel-group" id="accordion">
                                                                                                                                                                <div class="panel panel-default">
                                                                                                                                                                    <div class="panel-heading">
                                                                                                                                                                        <h4 class="panel-title">';
                                                                echo 'The Prime Contract was Recorded';
                                                                echo '
                                                                                                                                                                        </h4>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>';
                                                            } else {
                                                                echo '<div class="panel-group" id="accordion">
                                                                                                                                                                <div class="panel panel-default">
                                                                                                                                                                    <div class="panel-heading">
                                                                                                                                                                        <h4 class="panel-title">';
                                                                echo 'The Prime Contract was not Recorded';
                                                                echo '
                                                                                                                                                                        </h4>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>';
                                                            }
                                                            break;
                                                    }
                                                } elseif ($question[0]->question_order === 1) {
                                                    switch ($question[0]->question) {
                                                        case 'Is Notice of Completion or Cessation Recorded?':
                                                            if ($project->answer1 == 'Yes') {
                                                                echo '<div class="panel-group" id="accordion">
                                                                                                                                                                <div class="panel panel-default">
                                                                                                                                                                    <div class="panel-heading">
                                                                                                                                                                        <h4 class="panel-title">';
                                                                echo 'Notice of Completion or Cessation was Recorded';
                                                                echo '
                                                                                                                                                                        </h4>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>';
                                                            } else {
                                                                echo '<div class="panel-group" id="accordion">
                                                                                                                                                                <div class="panel panel-default">
                                                                                                                                                                    <div class="panel-heading">
                                                                                                                                                                        <h4 class="panel-title">';
                                                                echo 'Notice of Completion or Cessation was not Recorded';
                                                                echo '
                                                                                                                                                                        </h4>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>';
                                                            }
                                                            break;
                                                        case 'Is the project  5,000 sf or less or is the project four residential units or less?':
                                                            if ($project->answer1 == 'Yes') {
                                                                echo '<div class="panel-group" id="accordion">
                                                                                                                                                                <div class="panel panel-default">
                                                                                                                                                                    <div class="panel-heading">
                                                                                                                                                                        <h4 class="panel-title">';
                                                                echo 'The project is 5,000 sf or less or is four residential units or less';
                                                                echo '
                                                                                                                                                                        </h4>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>';
                                                            } else {
                                                                echo '<div class="panel-group" id="accordion">
                                                                                                                                                                <div class="panel panel-default">
                                                                                                                                                                    <div class="panel-heading">
                                                                                                                                                                        <h4 class="panel-title">';
                                                                echo 'The project is not 5,000 sf or less or is not four residential units or less';
                                                                echo '
                                                                                                                                                                        </h4>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>';
                                                            }
                                                            break;
                                                    }
                                                }
                                            }
                                        @endphp
                                    @endif
                                    <div class="panel-group" id="accordion_detailed">
                                        @if (count($remedyNames) > 0)
                                            @foreach ($remedyNames as $remedyKey => $name)
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            {{ $name }}
                                                        </h4>
                                                    </div>
                                                    @if (count($liens) > 0)
                                                        @foreach ($liens as $key => $lien)
                                                            @if ($name == $lien['remedy'])
                                                                <div id="detailed_{{ $key }}"
                                                                    class="panel-collapse collapse">
                                                                    <div class="panel-body">
                                                                        <div class="panel-main">
                                                                            <p style="font-size: medium;">
                                                                                <strong>Description:</strong>
                                                                                {{ $lien->description }}
                                                                            </p>
                                                                            <p style="font-size: medium;">
                                                                                <strong>Tiers:</strong>
                                                                                {{ $lien->tier_limit }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    Job
                                                    Description(<small><strong>{{ $project->project_name }}</strong></small>)
                                                </h4>
                                            </div>
                                            <div id="jobDescription_detailed" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="panel-main">
                                                        <div class="col-md-12">
                                                            <div align="left">
                                                                <p>Job Name :
                                                                    <strong>{{ $project->project_name }}</strong>
                                                                </p>
                                                                <p>Job Address :
                                                                    <strong>{{ $project->address != '' ? $project->address : 'N/A' }}</strong>
                                                                </p>
                                                                <p>Job City :
                                                                    <strong>{{ $project->city != '' ? $project->city : 'N/A' }}</strong>
                                                                </p>
                                                                <p>Job State :
                                                                    <strong>{{ $project->state->name }}</strong>
                                                                </p>
                                                                <p>Job Zip :
                                                                    <strong>{{ $project->zip != '' ? $project->zip : 'N/A' }}</strong>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!is_null($project->customer_contract))
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a data-toggle="collapse" data-parent="#accordion_detailed"
                                                            href="#customer_detailed">Customer
                                                            -
                                                            <small><strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ? $project->customer_contract->company->company : 'N/A' }}</strong></small>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="customer_detailed" class="panel-collapse collapse">
                                                    <div class="panel-body">
                                                        <div class="panel-main">
                                                            <div class="col-md-12">
                                                                <div class="col-md-5">
                                                                    <div align="left">
                                                                        <p>
                                                                            Company
                                                                            Name:
                                                                            <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ? $project->customer_contract->company->company : 'N/A' }}</strong>
                                                                        </p>
                                                                        <p>
                                                                            Address
                                                                            :
                                                                            <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->address) ? $project->customer_contract->company->company : 'N/A' }}</strong>
                                                                        </p>
                                                                        <p>City
                                                                            :
                                                                            <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->city) ? $project->customer_contract->company->city : 'N/A' }}</strong>,
                                                                            State
                                                                            :
                                                                            <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->state) ? $project->customer_contract->company->state->name : 'N/A' }}</strong>,
                                                                            Zip
                                                                            :
                                                                            <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->zip) ? $project->customer_contract->company->zip : 'N/A' }}</strong>
                                                                        </p>
                                                                        <p>
                                                                            Telephone
                                                                            :
                                                                            <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->phone) ? $project->customer_contract->company->phone : 'N/A' }}</strong>
                                                                        </p>
                                                                        <p>Fax :
                                                                            <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->fax) ? $project->customer_contract->company->fax : 'N/A' }}</strong>
                                                                        </p>
                                                                        <p>Web :
                                                                            <strong>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->website) ? $project->customer_contract->company->website : 'N/A' }}</strong>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    &nbsp;
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div align="right">
                                                                        @if (!is_null($project->customer_contract->contactInformation))
                                                                            @foreach ($project->customer_contract->contactInformation as $customerKey => $contactInformation)
                                                                                @if ($customerKey == 0)
                                                                                    <p>
                                                                                        First
                                                                                        :
                                                                                        <strong>{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}</strong>,
                                                                                        Last
                                                                                        :
                                                                                        <strong>{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}</strong>,
                                                                                        Title
                                                                                        :
                                                                                        <strong>{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}</strong>
                                                                                    </p>
                                                                                    <p>
                                                                                        Direct
                                                                                        Phone
                                                                                        :
                                                                                        <strong>{{ $contactInformation->direct_phone }}</strong>
                                                                                    </p>
                                                                                    <p>
                                                                                        Cell
                                                                                        Phone
                                                                                        :
                                                                                        <strong>{{ $contactInformation->cell }}</strong>
                                                                                    </p>
                                                                                    <p>
                                                                                        Email
                                                                                        :
                                                                                        <strong>{{ $contactInformation->email }}</strong>
                                                                                    </p>
                                                                                    @if (count($project->customer_contract->contactInformation) > 1)
                                                                                        <p>
                                                                                            <a href="javascript:void(0)"
                                                                                                class="show_more"
                                                                                                id="customerMore{{ $project->customer_contract->id }}"
                                                                                                data-id="{{ $project->customer_contract->id }}">Show
                                                                                                More</a>
                                                                                            <a href="javascript:void(0)"
                                                                                                class="show_less"
                                                                                                id="customerLess{{ $project->customer_contract->id }}"
                                                                                                data-id="{{ $project->customer_contract->id }}">Show
                                                                                                Less</a>
                                                                                        </p>
                                                                                    @endif
                                                                                @else
                                                                                    <div class="customer{{ $project->customer_contract->id }}"
                                                                                        style="display: none;">
                                                                                        <p>
                                                                                            First
                                                                                            :
                                                                                            <strong>{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}</strong>,
                                                                                            Last
                                                                                            :
                                                                                            <strong>{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}</strong>,
                                                                                            Title
                                                                                            :
                                                                                            <strong>{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}</strong>
                                                                                        </p>
                                                                                        <p>
                                                                                            Direct
                                                                                            Phone
                                                                                            :
                                                                                            <strong>{{ $contactInformation->direct_phone }}</strong>
                                                                                        </p>
                                                                                        <p>
                                                                                            Cell
                                                                                            Phone
                                                                                            :
                                                                                            <strong>{{ $contactInformation->cell }}</strong>
                                                                                        </p>
                                                                                        <p>
                                                                                            Email
                                                                                            :
                                                                                            <strong>{{ $contactInformation->email }}</strong>
                                                                                        </p>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (count($projectContacts) > 0)
                                            @foreach ($projectContacts as $key => $contact)
                                                @php
                                                    $customerDetails = \App\Models\CompanyContact::find($contact['customer_id']);
                                                    $mapDetails = $customerDetails->mapContactCompany;
                                                @endphp
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            {{ $contact['type'] }} -
                                                            <small><strong>{{ $contact['company']->company }}</strong></small>
                                                        </h4>
                                                    </div>
                                                    <div id="industry_detailed{{ $key }}"
                                                        class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <div class="panel-main">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-5">
                                                                        <div align="left">
                                                                            <p>Company Name:
                                                                                <strong>{{ $contact['company']->company }}</strong>
                                                                            </p>
                                                                            <p>Address :
                                                                                <strong>{{ $contact['company']->address != '' ? $contact['company']->address : 'N/A' }}</strong>
                                                                            </p>
                                                                            <p>City :
                                                                                <strong>{{ $contact['company']->city != '' ? $contact['company']->city : 'N/A' }}</strong>,
                                                                                State :
                                                                                <strong>{{ $contact['company']->state->name }}</strong>,
                                                                                Zip :
                                                                                <strong>{{ $contact['company']->zip != '' ? $contact['company']->zip : 'N/A' }}</strong>
                                                                            </p>
                                                                            <p>Telephone :
                                                                                <strong>{{ $contact['company']->phone != '' ? $contact['company']->phone : 'N/A' }}</strong>
                                                                            </p>
                                                                            <p>Fax :
                                                                                <strong>{{ $contact['company']->fax != '' ? $contact['company']->fax : 'N/A' }}</strong>
                                                                            </p>
                                                                            <p>Web :
                                                                                <strong>{{ $contact['company']->website != '' ? $contact['company']->website : 'N/A' }}</strong>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        &nbsp;
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <div align="right">
                                                                            @if (count($contact['customers']) > 0)
                                                                                @foreach ($contact['customers'] as $industryKey => $contactInformationIns)
                                                                                    @if ($industryKey == 0)
                                                                                        <p>First :
                                                                                            <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A' }}</strong>,
                                                                                            Last :
                                                                                            <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>,
                                                                                            Title :
                                                                                            <strong>{{ $contactInformationIns->title != '' ? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong>
                                                                                        </p>
                                                                                        <p>Direct Phone :
                                                                                            <strong>{{ $contactInformationIns->phone }}</strong>
                                                                                        </p>
                                                                                        <p>Cell Phone :
                                                                                            <strong>{{ $contactInformationIns->cell }}</strong>
                                                                                        </p>
                                                                                        <p>Email :
                                                                                            <strong>{{ $contactInformationIns->email }}</strong>
                                                                                        </p>
                                                                                        @if (count($contact['customers']) > 1)
                                                                                            <p>
                                                                                                <a href="javascript:void(0)"
                                                                                                    class="show_more"
                                                                                                    id="customerMore{{ $key }}"
                                                                                                    data-id="{{ $key }}">Show
                                                                                                    More</a>
                                                                                                <a href="javascript:void(0)"
                                                                                                    class="show_less"
                                                                                                    id="customerLess{{ $key }}"
                                                                                                    data-id="{{ $key }}">Show
                                                                                                    Less</a>
                                                                                            </p>
                                                                                        @endif
                                                                                    @else
                                                                                        <div class="customer{{ $key }}"
                                                                                            style="display: none;">
                                                                                            <p>First :
                                                                                                <strong>{{ $contactInformationIns->first_name != '' ? $contactInformationIns->first_name : 'N/A' }}</strong>,
                                                                                                Last :
                                                                                                <strong>{{ $contactInformationIns->last_name != '' ? $contactInformationIns->last_name : 'N/A' }}</strong>,
                                                                                                Title :
                                                                                                <strong>{{ $contactInformationIns->title != '' ? ($contactInformationIns->title == 'Other' ? $contactInformationIns->title_other : $contactInformationIns->title) : 'N/A' }}</strong>
                                                                                            </p>
                                                                                            <p>Direct Phone :
                                                                                                <strong>{{ $contactInformationIns->phone }}</strong>
                                                                                            </p>
                                                                                            <p>Cell Phone :
                                                                                                <strong>{{ $contactInformationIns->cell }}</strong>
                                                                                            </p>
                                                                                            <p>Email :
                                                                                                <strong>{{ $contactInformationIns->email }}</strong>
                                                                                            </p>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="head-top">
                                            &nbsp;

                                        </div>
                                    </div>
                                </div>
                            </div>





                            {{-- <div id="project_details_details" class="tab-pane fade in active">
                                                                    <div class="project-heading">
                                                                        <a href="javascript:void(0)">Project Name</a>
                                                                        <strong>{{ $project->project_name }}</strong>
                                                                    </div>
                                                                    <div class="row tab-final">

                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    Project Location by State :
                                                                                                </a>
                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                @foreach ($states as $state)
                                                                                                    @if ($state->id == $project->state_id)
                                                                                                        <strong> {{$state->name}}</strong>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    Your Role :
                                                                                                </a>
                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                @foreach ($roles as $role)
                                                                                                    @if ($role->id == $project->role_id)
                                                                                                        <strong>{{$role->project_roles}}</strong>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="row tab-final">
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    Project Type :
                                                                                                </a>

                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                @foreach ($types as $type)
                                                                                                    @if ($type->id == $project->project_type_id)
                                                                                                        <strong>{{$type->project_type}}</strong>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    Who Hired You :
                                                                                                </a>

                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                @if (isset($project) && $project != '')
                                                                                                    <strong>{{ $project->tier->customer->name }}</strong>
                                                                                                @else
                                                                                                    Select a Select your customer
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row tab-final">
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    Customer Contract :
                                                                                                </a>

                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                {{ !is_null($project->customer_contract) && !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?$project->customer_contract->company->company : 'N/A'  }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    Industry Contract :
                                                                                                </a>
                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                @if ($project->industryContacts != '')
                                                                                                    @foreach ($project->industryContacts as $contact)
                                                                                                        {{ $contact->contacts ? $contact->contacts->company.', ' : '' }}
                                                                                                    @endforeach
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row tab-final">
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    Address :
                                                                                                </a>

                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                {{ $project->address }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    City :
                                                                                                </a>

                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                {{ $project->city }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row tab-final">
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    Country :
                                                                                                </a>

                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                {{ $project->country }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-12">
                                                                                    <div class="border-bottom">
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                <a href="javascript:void(0)">
                                                                                                    Your Company's Work :
                                                                                                </a>

                                                                                            </div>
                                                                                            <div class="col-md-6 col-sm-6">
                                                                                                {{ $project->company_work }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> --}}
                            <div id="home" class="tab-pane fade in active tab-wrapper">
                                <div class="tab-area1">
                                    <ul class="nav nav-tabs">
                                        @if (count($remedyNames) > 0)
                                            <li class="active"><a data-toggle="tab" href="#allRemedies">All
                                                    Remedies</a></li>
                                            @foreach ($remedyNames as $key => $name)
                                                <li class=""><a data-toggle="tab"
                                                        href="#{{ $key }}">{{ $name }}</a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                <div class="tab-content">

                                    <div id="allRemedies" class="tab-pane fade in active">

                                        <div class="table-responsive text-left">

                                            @if (count($deadlines) > 0)
                                                @foreach ($deadlines as $key => $deadline)
                                                    @if (strlen($daysRemain[$key]['preliminaryDates']) > 5)
                                                        @php
                                                            $today = date('Y-m-d');
                                                            $today = new DateTime($today);
                                                            $prelimDead = $daysRemain[$key]['preliminaryDates'];
                                                            $formatPrelim = new \DateTime($prelimDead);
                                                            $daysUntilDeadline = date_diff($formatPrelim, $today);
                                                            $daysUntilDeadline = $daysUntilDeadline->format('%a');
                                                            $late = date_diff($formatPrelim, $today);
                                                            $late = $late->format('%R');
                                                            
                                                        @endphp
                                                    @else
                                                        @php
                                                            $daysUntilDeadline = 'N/A';
                                                            $late = 0;
                                                        @endphp
                                                    @endif
                                                    <div class="col-md-12 deadlines deadlines--large">
                                                        <span class="tag label label-info"
                                                            style="margin-bottom:30px;">{{ $deadlines[$key]->getRemedy->remedy }}</span>
                                                        <p>{{ $deadline->short_description }}</p>

                                                        <div class="col-md-6">
                                                            @if (strlen($daysRemain[$key]['preliminaryDates']) > 5)
                                                                <p class="prelim-date">Complete by:
                                                                    {{ $daysRemain[$key]['preliminaryDates'] }}</p>
                                                            @else
                                                                <p class="prelim-date">Complete by: N/A</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            @if (strlen($daysRemain[$key]['preliminaryDates']) > 5)
                                                                @if ($late === '+')
                                                                    <p class="prelim-date prelim-date--late">Days Late:
                                                                        {{ $daysUntilDeadline }}</p>
                                                                @else
                                                                    <p class="prelim-date">Days Remaining:
                                                                        {{ $daysUntilDeadline }}</p>
                                                                @endif
                                                            @else
                                                                <p class="prelim-date">Days Remaining: N/A</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="prelim-date">Date Legal Step Completed</p>
                                                            <div class="col-sm-6 no-pad">
                                                                <input type="text"
                                                                    class="form-control date form-control--mobilewidth"
                                                                    name="date-legal[]" disabled="disabled"
                                                                    data-provide="datepicker"
                                                                    value="{{ $deadlines[$key]['legal_completion_date'] }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="prelim-date">Email Alerts:</p>
                                                            <select name="email-alert[]" disabled="disabled">
                                                                <option value="1"
                                                                    {{ $deadlines[$key]['email_alert'] == 1 ? 'selected' : '' }}>
                                                                    On
                                                                </option>
                                                                <option value="0"
                                                                    {{ $deadlines[$key]['email_alert'] == 0 ? 'selected' : '' }}>
                                                                    Off
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                    @if (count($remedyNames) > 0)
                                        @foreach ($remedyNames as $remedyKey => $name)
                                            @if (strlen($daysRemain[$key]['preliminaryDates']) > 5)
                                                @php
                                                    
                                                    $today = date('Y-m-d');
                                                    $today = new DateTime($today);
                                                    $prelimDead = $daysRemain[$key]['preliminaryDates'];
                                                    $formatPrelim = new \DateTime($prelimDead);
                                                    $daysUntilDeadline = date_diff($formatPrelim, $today);
                                                    $daysUntilDeadline = $daysUntilDeadline->format('%a');
                                                    $late = date_diff($formatPrelim, $today);
                                                    $late = $late->format('%R');
                                                    
                                                @endphp
                                            @else
                                                @php
                                                    $daysUntilDeadline = 'N/A';
                                                    $late = 0;
                                                @endphp
                                            @endif
                                            <div id="{{ $remedyKey }}" class="tab-pane fade">

                                                <div class="table-responsive text-left">

                                                    @if (count($deadlines) > 0)
                                                        @foreach ($deadlines as $key => $deadline)
                                                            @if (strlen($daysRemain[$key]['preliminaryDates']) > 5)
                                                                @php
                                                                    $today = date('Y-m-d');
                                                                    $today = new DateTime($today);
                                                                    $prelimDead = $daysRemain[$key]['preliminaryDates'];
                                                                    $formatPrelim = new \DateTime($prelimDead);
                                                                    $daysUntilDeadline = date_diff($formatPrelim, $today);
                                                                    $daysUntilDeadline = $daysUntilDeadline->format('%a');
                                                                    $late = date_diff($formatPrelim, $today);
                                                                    $late = $late->format('%R');
                                                                    
                                                                @endphp
                                                            @else
                                                                @php
                                                                    $daysUntilDeadline = 'N/A';
                                                                    $late = 0;
                                                                @endphp
                                                            @endif
                                                            <div class="col-md-12 deadlines deadlines--large">
                                                                <span class="tag label label-info"
                                                                    style="margin-bottom:30px;">{{ $deadlines[$key]->getRemedy->remedy }}</span>
                                                                <p>{{ $deadline->short_description }}</p>

                                                                <div class="col-md-6">
                                                                    @if (strlen($daysRemain[$key]['preliminaryDates']) > 5)
                                                                        <p class="prelim-date">Complete by:
                                                                            {{ $daysRemain[$key]['preliminaryDates'] }}
                                                                        </p>
                                                                    @else
                                                                        <p class="prelim-date">Complete by: N/A</p>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    @if (strlen($daysRemain[$key]['preliminaryDates']) > 5)
                                                                        @if ($late === '+')
                                                                            <p class="prelim-date prelim-date--late">Days
                                                                                Late: {{ $daysUntilDeadline }}</p>
                                                                        @else
                                                                            <p class="prelim-date">Days Remaining:
                                                                                {{ $daysUntilDeadline }}</p>
                                                                        @endif
                                                                    @else
                                                                        <p class="prelim-date">Days Remaining: N/A</p>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p class="prelim-date">Date Legal Step Completed</p>
                                                                    <input type="text" style="width:50%;"
                                                                        class="form-control date form-control--mobilewidth"
                                                                        name="date-legal[]" disabled="disabled"
                                                                        data-provide="datepicker"
                                                                        value="{{ $deadlines[$key]['legal_completion_date'] }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p class="prelim-date">Email Alerts:</p>
                                                                    <select name="email-alert[]" disabled="disabled">
                                                                        <option value="1"
                                                                            {{ $deadlines[$key]['email_alert'] == 1 ? 'selected' : '' }}>
                                                                            On
                                                                        </option>
                                                                        <option value="0"
                                                                            {{ $deadlines[$key]['email_alert'] == 0 ? 'selected' : '' }}>
                                                                            Off
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        @endforeach
                                                    @endif

                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div id="contracts" class="tab-pane fade in tab-wrapper">
                                <!--   <h1> Project contracts</h1> -->
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="border-table">
                                            <div class="table-responsive amount-table">
                                                <div class="contract--wrapper contract--wrapper--med">
                                                    <label>Base Contract Amount</label>
                                                    <input class="form-control" type="number" name="base_amount"
                                                        id="base_amount"
                                                        value="{{ isset($contract) && $contract != '' && $contract->base_amount != '' ? $contract->base_amount : '0' }}"
                                                        disabled="disabled">
                                                    <label>+ Additional Costs</label>
                                                    <input class="form-control" type="number" name="extra_amount"
                                                        id="extra_amount"
                                                        value="{{ isset($contract) && $contract != '' && $contract->extra_amount != '' ? $contract->extra_amount : '0' }}"
                                                        disabled="disabled">
                                                    <label>Revised Cost</label>
                                                    <input class="form-control" type="text" id="contact_total"
                                                        disabled="disabled"></th>
                                                    <label>- Payments/Credits</label>
                                                    <input class="form-control" type="number" name="payment" id="payment"
                                                        value="{{ isset($contract) && $contract != '' && $contract->credits != '' ? $contract->credits : '0' }}"
                                                        disabled="disabled">
                                                    <label>Total Claim Amount</label>
                                                    <input class="form-control" type="text" id="claim_amount"
                                                        name="claim_amount" disabled="disabled">
                                                </div>
                                                <div class="contract--wrapper contract--wrapper--med">
                                                    <label>Waiver Amount</label>
                                                    <input class="form-control" type="number" name="waiver_amount"
                                                        value="{{ isset($contract) && $contract != '' && $contract->waiver != '' ? $contract->waiver : '0' }}"
                                                        disabled="disabled">

                                                </div>
                                                <div class="contract--wrapper contract--wrapper--med">
                                                    <label>Receivable Status</label>
                                                    <p>{{ isset($contract) && $contract != '' && $contract->receivable_status ? $contract->receivable_status : 'N/A' }}
                                                    </p>
                                                    <label>Deadline Calculation Status</label>
                                                    @php(isset($contract) && $contract != '' && $contract->receivable_status) ? $var = $contract->calculation_status :
                                                    $var = 'N/A'; @endphp
                                                    @if ($var == '0')
                                                        <strong>In
                                                            Process</strong>
                                                    @elseif($var == '1')
                                                        Completed
                                                    @elseif($var === 'N/A')
                                                        <p>N/A</p>
                                                    @endif
                                                </div>
                                                @php
                                                    $url = route('project.contract.view') . '?project_id=' . $project->id . '&view=detailed';
                                                @endphp
                                                <div class="edit-link-wrapper">
                                                    <a href="{{ $url }}"
                                                        class="project-save-quit project-save-quit--view-button">Edit
                                                        Contract Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4">
                                    </div>
                                </div>
                            </div>
                            <div id="project-dates" class="tab-pane fade tab-wrapper">
                                <div class="border-table">
                                    <!-- <h1> Project Dates</h1> -->
                                    <form action="#" method="post" class="form-horizontal project-form project_dates">
                                        @foreach ($projectDateField as $date)
                                            <div class="row date-space">
                                                <div class="form-group">
                                                    <label class="col-md-6 control-label">{{ $date['name'] }}</label>
                                                    <div class="col-md-6 date-parent">
                                                        @if (empty($date['dates']))
                                                            @if ($date['recurring'] === 1)
                                                                <div class="row">
                                                                    <div class="col-md-12 input-space">
                                                                        <input type="text"
                                                                            class="form-control date multiple"
                                                                            name="remedyDates[{{ $date['id'] }}]"
                                                                            value="" data-provide="datepicker"
                                                                            data-recurring="{{ $date['recurring'] }}"
                                                                            data-dateId="1" data-existing="false" disabled>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="row">
                                                                    <div class="col-md-12 input-space">
                                                                        <input type="text"
                                                                            class="form-control date multiple"
                                                                            name="remedyDates[{{ $date['id'] }}]"
                                                                            value="" data-provide="datepicker"
                                                                            data-recurring="{{ $date['recurring'] }}"
                                                                            data-dateId="null" disabled>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @else
                                                            @foreach ($date['dates'] as $value)
                                                                @if ($date['recurring'] === 1)
                                                                    <div class="row">
                                                                        <div class="col-md-12 input-space">
                                                                            <input type="text"
                                                                                class="form-control date multiple"
                                                                                name="remedyDates[{{ $date['id'] }}]"
                                                                                value="{{ $value['value'] }}"
                                                                                data-provide="datepicker"
                                                                                data-recurring="{{ $date['recurring'] }}"
                                                                                data-dateId="{{ $value['id'] }}"
                                                                                data-existing="true" disabled>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="row">
                                                                        <div class="col-md-12 input-space">
                                                                            <input type="text"
                                                                                class="form-control date multiple"
                                                                                name="remedyDates[{{ $date['id'] }}]"
                                                                                value="{{ $value['value'] }}"
                                                                                data-provide="datepicker"
                                                                                data-recurring="{{ $date['recurring'] }}"
                                                                                data-dateId="{{ $value['id'] }}"
                                                                                data-existing="true" disabled>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @if ($date['recurring'] === 1)
                                                                <div class="row">
                                                                    <div class="col-md-12 input-space">
                                                                        <input type="text"
                                                                            class="form-control date multiple"
                                                                            name="remedyDates[{{ $date['id'] }}]"
                                                                            value="" data-provide="datepicker"
                                                                            data-recurring="{{ $date['recurring'] }}"
                                                                            data-dateId="1" data-existing="false" disabled>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>



                                        @endforeach
                                        {{ csrf_field() }}
                                        <input type="hidden" name="project_id" value="{{ $project_id }}"
                                            class="project_id">
                                    </form>
                                </div>
                                @php
                                    $url = route('create.remedydates', [$project->id]) . '?edit=true';
                                @endphp
                                <div class="edit-link-wrapper">
                                    <a href="{{ $url }}"
                                        class="project-save-quit project-save-quit--view-button">Edit Job Dates</a>
                                </div>
                            </div>
                            <div id="documents" class="tab-pane fade">
                                <div class="border-table">
                                    <!-- <h1 class="text-center">Project Documents</h1>
                                                                            <hr class="divider"> -->
                                    <form action="#" method="post" class="form-horizontal project-form project_documents">
                                        <div class="form-group">
                                            <label class="col-md-2">Create
                                                Documents</label>
                                            <div class="col-md-6">
                                                <select id="DocumentType" class="form-control" name="DocumentType">
                                                    <option value="">Select a
                                                        Document Type
                                                    </option>
                                                    <option value="JobInfo">Job
                                                        Info Sheet
                                                    </option>
                                                    <option value="ClaimData">
                                                        Claim Form and Project
                                                        Data Sheet
                                                    </option>
                                                    <option value="CreditApplication">
                                                        Credit
                                                        Application
                                                    </option>
                                                    <option value="JointPaymentAuthorization">
                                                        Joint
                                                        Payment
                                                        Authorization
                                                    </option>
                                                    <optgroup label="Waiver of Lien Forms">
                                                        <option value="UnconditionalWaiverProgress">
                                                            Unconditional Waiver
                                                            and Release Upon
                                                            Progress Payment
                                                        </option>
                                                        <option value="ConditionalWaiver">
                                                            Conditional Waiver
                                                            and Release
                                                            Upon Progress
                                                            Payment
                                                        </option>
                                                        <option value="ConditionalWaiverFinal">
                                                            Conditional Waiver
                                                            and
                                                            Release Upon Final
                                                            Payment
                                                        </option>
                                                        <option value="UnconditionalWaiverFinal">
                                                            Unconditional Waiver
                                                            and Release Upon
                                                            Final Payment
                                                        </option>
                                                        <option value="PartialWaiver">
                                                            Partial Waiver
                                                            of Lien (For an
                                                            Amount Only)
                                                        </option>
                                                        <option value="PartialWaiverDate">
                                                            Partial
                                                            Waiver of Lien (To
                                                            Date Only)
                                                        </option>
                                                        <option value="WaiverOfLien">
                                                            Final Waiver of
                                                            Lien (Standard)
                                                        </option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="blue-btn" id="createDocument" type="button" disabled>
                                                    Create
                                                    document
                                                </button>
                                            </div>
                                        </div>
                                        {{-- <div class="form-group"> --}}
                                        {{-- <label>Existing Documents</label> --}}
                                        {{-- </div> --}}
                                        <table class="table">
                                            <div class="form-group">
                                                <label>Existing
                                                    Documents</label>
                                            </div>
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Document
                                                        Date
                                                    </th>
                                                    <th class="text-center">Document
                                                        Type
                                                    </th>
                                                    <th class="text-center">View
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>N/A</td>
                                                    <td>Lien Law Summary</td>
                                                    <td>
                                                        <button id="lianLawSummery" type="button"
                                                            class="form-control btn blue-btn btn-success">
                                                            View
                                                        </button>
                                                    </td>
                                                </tr>
                                                @if (!empty($project_documents))
                                                    @foreach ($project_documents as $document)
                                                        @if ($document['data'])
                                                            <tr>
                                                                <td>{{ date('F d, Y h:i:s A', strtotime(isset($document['data']) ? $document['data']->created_at : '')) }}
                                                                </td>
                                                                {{-- @if ($document['name']) --}}
                                                                <td>{{ $document['name'] }}</td>
                                                                {{-- @endif --}}
                                                                @if ($document['name'] == 'Claim form and project data sheet')
                                                                    <td>
                                                                        <a href="{{ route('get.documentClaimView', [$project_id, $flag]) }}"
                                                                            class="form-control btn blue-btn btn-success">View</a>
                                                                    </td>
                                                                @elseif($document['name'] == 'Credit Application')
                                                                    <td>
                                                                        <a href="{{ route('get.documentCreditView', [$project_id, $flag]) }}"
                                                                            class="form-control btn blue-btn btn-success">View</a>
                                                                    </td>
                                                                @elseif($document['name'] == 'joint payment
                                                                    authorization')
                                                                    <td>
                                                                        <a href="{{ route('get.documentJointView', [$project_id, $flag]) }}"
                                                                            class="form-control btn blue-btn btn-success">View</a>
                                                                    </td>
                                                                @elseif($document['name'] == 'Waver progress')
                                                                    <td>
                                                                        <a href="{{ route('get.documentWaverView', [$project_id, $flag]) }}"
                                                                            class="form-control btn blue-btn btn-success">View</a>
                                                                    </td>
                                                                @elseif($document['name'] == 'job info sheet')
                                                                    <td>
                                                                        <div class="col-md-12">
                                                                            <div class="col-md-6">
                                                                                <a href="{{ route('get.job.info.sheet', [$project_id]) }}"
                                                                                    class="form-control btn blue-btn btn-success viewBtn"
                                                                                    target="_blank">View</a>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <a href="{{ route('get.jobInfoExport', [$project_id]) }}"
                                                                                    class="form-control btn btn-info"
                                                                                    target="_blank">Export
                                                                                    as
                                                                                    PDF</a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div id="tasks" class="tab-pane fade tab-wrapper">
                                <div class="border-table">
                                    <h1 class="text-center">Project Tasks</h1>
                                    <hr class="divider">
                                    @if (count($tasks) > 0)
                                        <div class="row border-class">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th class="text-center">Action</th>
                                                                <th class="text-center">Comments</th>
                                                                <th class="text-center">Deadline</th>
                                                                <th class="text-center">Date Completed</th>
                                                                <th class="text-center">Email Alert</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($tasks as $key => $task)
                                                                @php
                                                                    $date1 = new DateTime($task->due_date);
                                                                    $date2 = new DateTime(date('Y-m-d'));
                                                                    $diff = $date2->diff($date1)->format('%R%a days');
                                                                    $exactDiff = $date2->diff($date1)->format('%a days');
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td>{{ $task->task_name }}</td>
                                                                    <td>{{ $task->comment }}</td>
                                                                    <td>
                                                                        {{ \DateTime::createFromFormat('Y-m-d', $task->due_date)->format('m/d/Y') }}
                                                                        <br /> <span style="color: red;">
                                                                            {{ $diff > 0 ? 'You have ' . $exactDiff . ' remaining.' : 'This deadline has passed' }}
                                                                        </span>
                                                                    </td>
                                                                    <td>{{ $task->complete_date != '' ? \DateTime::createFromFormat('Y-m-d', $task->complete_date)->format('m/d/Y') : '' }}
                                                                    </td>
                                                                    <td>{{ $task->email_alert == 0 ? 'Off' : 'On' }}</td>
                                                                    <td>
                                                                        <button class="btn btn-xs btn-warning editButton"
                                                                            type="button" data-id="{{ $task->id }}"
                                                                            data-action="{{ $task->task_name }}"
                                                                            data-due_date="{{ \DateTime::createFromFormat('Y-m-d', $task->due_date)->format('d/m/Y') }}"
                                                                            data-complete_date="{{ $task->complete_date != '' ? \DateTime::createFromFormat('Y-m-d', $task->complete_date)->format('d/m/Y') : '' }}"
                                                                            data-email="{{ $task->email_alert }}"
                                                                            data-comment="{{ $task->comment }}"
                                                                            data-toggle="modal">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </button>
                                                                        <button class="btn btn-xs btn-danger deleteTask"
                                                                            data-id="{{ $task->id }}" type="button">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <h4 class="text-center">You currently have no tasks set up for this project.
                                            </h4>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12 project-tasks">
                                            <form action="{{ route('project.add.task') }}"
                                                class="form-horizontal border-class task-padding" method="post">
                                                <h4 class="project-tasks-heading">Add A Task</h4>
                                                <div class="contract-wrapper">
                                                    <label for="action">Action</label>
                                                    <select id="action" name="action"
                                                        class="form-control form-control--mobilewidth">
                                                        <option value="Call Customer">Call Customer</option>
                                                        <option value="Follow Up Payment">Follow Up Payment</option>
                                                        <option value="Prepare Waivers for Draw">Prepare Waivers for Draw
                                                        </option>
                                                        <option value="Prepare Credit Application">Prepare Credit
                                                            Application</option>
                                                        <option value="Prepare  Rider to Contract">Prepare Rider to Contract
                                                        </option>
                                                        <option value="Forward Claim To NLB">Forward Claim To NLB</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    <div class="project-tasks-input--half-wrap">
                                                        <label for="due_date">Due Date</label>
                                                        @if (!$mobile)
                                                            <input type="text" id="due_date"
                                                                class="form-control date form-control--mobilewidth"
                                                                name="due_date" data-provide="datepicker">
                                                        @else
                                                            <input type="date" id="due_date"
                                                                class="form-control date form-control--mobilewidth"
                                                                name="due_date">
                                                        @endif
                                                    </div>
                                                    <div class="project-tasks-input--half-wrap">
                                                        <label for="email_alert">Email Alert</label>
                                                        <select name="email_alert" id="email_alert"
                                                            class="form-control form-control--mobilewidth">
                                                            <option value="0">Off</option>
                                                            <option value="1">On</option>
                                                        </select>
                                                    </div>
                                                    <label for="comment">Comments</label>
                                                    <textarea id="comment" class="form-control form-control--mobilewidth"
                                                        name="comment"></textarea>
                                                </div>
                                                <input type="hidden" name="project_id" value="{{ $project_id }}">
                                                <button type="submit"
                                                    class="btn btn-lg blue-btn project-create-continue">Add Task</button>
                                                {{ csrf_field() }}
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

@endsection
@section('modal')
    <!-- Modal -->
    <div id="editTask" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Task</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Action</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="action">
                                            <option value="Call Customer">Call Customer</option>
                                            <option value="Follow Up Payment">Follow Up Payment</option>
                                            <option value="Prepare Waivers for Draw">Prepare Waivers for Draw</option>
                                            <option value="Prepare Credit Application">Prepare Credit Application
                                            </option>
                                            <option value="Prepare  Rider to Contract">Prepare Rider to Contract
                                            </option>
                                            <option value="Forward Claim To NLB">Forward Claim To NLB</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Due-Date</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control date" id="due_date"
                                            data-provide="datepicker">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Complete-Date</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control date" id="complete_date"
                                            data-provide="datepicker">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Email Alert</label>
                                    <div class="col-md-8">
                                        <select id="email_alert" class="form-control">
                                            <option value="0">Off</option>
                                            <option value="1">On</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" id="task_id">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Comments</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="comment"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary editTaskButton">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $(document).on('click', '.mobile-nav-tab', function() {
                let tab = $(this).attr('data-tab')
                $('.mobile-nav--menu').attr('data-target', tab)
            })
            $(document).on('click', '.sidenav', function() {
                $(".sidenav").css('width', '0px');
            })
        })

        function openNav(e) {
            let menu = $('.mobile-nav--menu').attr('data-target')
            console.log(menu)
            if (menu == 'express') {

                $('#mobileNav').css('width', '100%');
            } else {
                $('#mobileNavDetailed').css('width', '100%')
            }
        }

        function closeNav() {
            $(".sidenav").css('width', '0px');
        }
    </script>
    <script>
        $(document).ready(function() {

            let mobile = {
                Android: function() {
                    return navigator.userAgent.match(/Android/i);
                },
                BlackBerry: function() {
                    return navigator.userAgent.match(/BlackBerry/i);
                },
                iOS: function() {
                    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                },
                Opera: function() {
                    return navigator.userAgent.match(/Opera Mini/i);
                },
                Windows: function() {
                    return navigator.userAgent.match(/IEMobile/i);
                },
                any: function() {
                    return (mobile.Android() || mobile.BlackBerry() || mobile.iOS() || mobile.Opera() ||
                        mobile.Windows());
                }
            };

            base_amount = $('#base_amount').val();
            extra_amount = $('#extra_amount').val();
            payment = $('#payment').val();
            total = parseFloat(base_amount) + parseFloat(extra_amount);
            claim_total = parseFloat(total) - parseFloat(payment);
            $('#contact_total').val(total);
            $('#claim_amount').val(claim_total);


            $('#activate-step-5').on('click', function(e) {
                if (mobile.any()) {
                    window.location.href = "{{ route('get.job.info.sheet', [$project_id]) }}";
                } else {
                    window.location.href =
                    "{{ route('project.task.view') . '?project_id=' . $project_id }}";
                }
            });
            $('#lianLawSummery').on('click', function() {
                url =
                    "{{ route('get.lineBoundSummery', [$project->state_id, $project->project_type_id]) }}";
                window.open(url, '_blank');
            });
            $('#DocumentType').on('change', function() {
                var selected = $(this).val();
                if (selected != '') {
                    $('#createDocument').removeAttr('disabled');
                } else {
                    $('#createDocument').attr('disabled', 'disabled');
                }
            });
            $('#DocumentTypeLien').on('change', function() {
                var selected = $(this).val();
                if (selected != '') {
                    $('#createDocumentLien').removeAttr('disabled');
                } else {
                    $('#createDocumentLien').attr('disabled', 'disabled');
                }
            });
            $('#createDocument').on('click', function() {
                var document = $('#DocumentType').val();
                var url = '';
                if (document == 'ClaimData') {
                    url = '{{ route('get.documentClaimData', [$project_id]) }}';
                } else if (document == 'CreditApplication') {
                    url = '{{ route('get.creditApplication', [$project_id]) }}';
                } else if (document == 'JointPaymentAuthorization') {
                    url = '{{ route('get.jointPaymentAuthorization', [$project_id]) }}';
                } else if (document == 'UnconditionalWaiverProgress') {
                    url = '{{ route('get.documentUnconditionalWaiverRelease', [$project_id]) }}';
                } else if (document == 'ConditionalWaiver') {
                    url = '{{ route('get.documentConditionalWaiver', [$project_id]) }}';
                } else if (document == 'ConditionalWaiverFinal') {
                    url = '{{ route('get.documentConditionalWaiverFinal', [$project_id]) }}';
                } else if (document == 'UnconditionalWaiverFinal') {
                    url = '{{ route('get.documentUnconditionalWaiverFinal', [$project_id]) }}';
                } else if (document == 'PartialWaiver') {
                    url = '{{ route('get.documentPartialWaiver', [$project_id]) }}';
                } else if (document == 'PartialWaiverDate') {
                    url = '{{ route('get.documentPartialWaiverDate', [$project_id]) }}';
                } else if (document == 'WaiverOfLien') {
                    url = '{{ route('get.documentStandardWaiverFinal', [$project_id]) }}';
                } else if (document == 'JobInfo') {
                    url = '{{ route('get.job.info.sheet', [$project_id]) }}';
                } else {
                    url = '{{ route('member.dashboard') }}';
                }
                window.open(url, '_blank');
            });
            $('#createDocumentLien').on('click', function() {
                var document = $('#DocumentTypeLien').val();
                var url = '';
                if (document == 'ClaimData') {
                    url = '{{ route('get.documentClaimData', [$project_id]) }}';
                } else if (document == 'CreditApplication') {
                    url = '{{ route('get.creditApplication', [$project_id]) }}';
                } else if (document == 'JointPaymentAuthorization') {
                    url = '{{ route('get.jointPaymentAuthorization', [$project_id]) }}';
                } else if (document == 'UnconditionalWaiverProgress') {
                    url = '{{ route('get.documentUnconditionalWaiverRelease', [$project_id]) }}';
                } else if (document == 'ConditionalWaiver') {
                    url = '{{ route('get.documentConditionalWaiver', [$project_id]) }}';
                } else if (document == 'ConditionalWaiverFinal') {
                    url = '{{ route('get.documentConditionalWaiverFinal', [$project_id]) }}';
                } else if (document == 'UnconditionalWaiverFinal') {
                    url = '{{ route('get.documentUnconditionalWaiverFinal', [$project_id]) }}';
                } else if (document == 'PartialWaiver') {
                    url = '{{ route('get.documentPartialWaiver', [$project_id]) }}';
                } else if (document == 'PartialWaiverDate') {
                    url = '{{ route('get.documentPartialWaiverDate', [$project_id]) }}';
                } else if (document == 'WaiverOfLien') {
                    url = '{{ route('get.documentStandardWaiverFinal', [$project_id]) }}';
                } else if (document == 'JobInfo') {
                    url = '{{ route('get.job.info.sheet', [$project_id]) }}';
                } else {
                    url = '{{ route('member.dashboard') }}';
                }
                window.open(url, '_blank');
            });
            $('.editButton').on('click', function() {
                var id = $(this).data('id');
                $('#task_id').val(id);
                var action = $(this).data('action');
                $('#action').val(action);
                var due_date = $(this).data('due_date');
                $('#due_date').val(due_date);
                var email = $(this).data('email');
                $('#email_alert').val(email);
                var comment = $(this).data('comment');
                $('#comment').val(comment);
                var complete_date = $(this).data('complete_date');
                $('#complete_date').val(complete_date);
                $('#editTask').modal('show');
            });
            $('.editTaskButton').on('click', function() {
                var action = $('#action').val();
                if (action == '') {
                    $('#action').addClass('input-error');
                    return false;
                } else {
                    $('#action').removeClass('input-error');
                }
                var id = $('#task_id').val();
                var due_date = $('#due_date').val();
                if (due_date == '') {
                    $('#due_date').addClass('input-error');
                    return false;
                } else {
                    $('#due_date').removeClass('input-error');
                }
                var complete_date = $('#complete_date').val();
                var email = $('#email_alert').val();
                var comment = $('#comment').val();
                // if(comment == ''){
                //     $('#comment').addClass('input-error');
                //     return false;
                // } else {
                //     $('#comment').removeClass('input-error');
                // }
                $.ajax({
                    type: "POST",
                    url: "{{ route('project.update.task') }}",
                    data: {
                        action: action,
                        task_id: id,
                        due_date: due_date,
                        complete_date: complete_date,
                        email: email,
                        comment: comment,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status) {
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Task Updated successfully',
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
            $('.deleteTask').on('click', function() {
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
                        url: "{{ route('project.delete.task') }}",
                        data: {
                            task_id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Task deleted successfully',
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
            //expand/collapse all
            $('.expandall-icon').click(function() {
                $(this).addClass('expand-inactive');
                $('.expandall-icon2').addClass('expand-inactive');
                $('.collapseall-icon').removeClass('collapse-inactive');
                $('.collapseall-icon2').removeClass('collapse-inactive');
                $('.collapse').addClass('in');
                $('.collapse').css('height', 'auto');
            });
            $('.collapseall-icon').click(function() {
                $(this).addClass('collapse-inactive');
                $('.collapseall-icon2').addClass('collapse-inactive');
                $('.expandall-icon').removeClass('expand-inactive');
                $('.expandall-icon2').removeClass('expand-inactive');
                $('.collapse').removeClass('in');
                $('.collapse').css('height', '0px');
            }).addClass('collapse-inactive');
            $('.expandall-icon2').click(function() {
                $(this).addClass('expand-inactive');
                $('.expandall-icon').addClass('expand-inactive');
                $('.collapseall-icon2').removeClass('collapse-inactive');
                $('.collapseall-icon').removeClass('collapse-inactive');
                $('.collapse').addClass('in');
                $('.collapse').css('height', 'auto');
            });
            $('.collapseall-icon2').click(function() {
                $(this).addClass('collapse-inactive');
                $('.collapseall-icon').addClass('collapse-inactive');
                $('.expandall-icon2').removeClass('expand-inactive');
                $('.expandall-icon').removeClass('expand-inactive');
                $('.collapse').removeClass('in');
                $('.collapse').css('height', '0px');
            }).addClass('collapse-inactive');

            $('.show_more').click(function() {
                var id = $(this).data('id');
                $(this).addClass('show-inactive');
                $('#customerLess' + id).removeClass('show-inactive');
                $('.customer' + id).show();
            });

            $('.show_less').click(function() {
                var id = $(this).data('id');
                $(this).addClass('show-inactive');
                $('#customerMore' + id).removeClass('show-inactive');
                $('.customer' + id).hide();
            }).addClass('show-inactive');
        });
    </script>
@endsection
