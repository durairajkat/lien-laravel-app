@extends('basicUser.projects.create')

@section('body')
    @php
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $mobile =
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) ||
        preg_match(
            '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4),
        );
    @endphp
    @if (isset($_GET['view']) && $_GET['view'] === 'detailed')
        <span id="stepNumDetailed" data-step="4"></span>
    @else
        <span id="stepNum" data-step="3"></span>
    @endif

    @include('basicUser.partials.multi-step-form')

    @if (isset($_GET['edit']))
        <span id="editFlag"></span>
        <form action="#" method="post"
            class="form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit">
        @else
            <form action="#" method="post" class="form-horizontal project-form project_details create-project-form">
    @endif

    <div class="buttons-on-top row button-area">
        <a href="javascript:void(0)" id="skip-button-3-out" class="skip-job-description project-create-skip">
            Skip
        </a>
        <a href="javascript:void(0)" id="activate-step-8-out" class="save-job-description project-create-continue">
            Save & Continue
        </a>
    </div>
    <div class="create-project-form-bgcolor">


        <div class="create-project-form-header">
            <h2>View Preliminary Deadlines</h2>
            @if (isset($_GET['edit']) and $mobile)
                <span class="mobile-nav--menu" onclick="openNav()" data-target="detailed"><i class="fa fa-ellipsis-v"
                        aria-hidden="true"></i></span>
            @endif
        </div>
        @if (isset($_GET['edit']))
            {{-- <div class="edit-nav"> --}}
            {{-- @if (!$mobile) --}}
            {{-- <div class="main-tab-menu menu--gray"> --}}
            {{-- <ul class="nav nav-tabs"> --}}
            {{-- <li> --}}
            {{-- <a href="{{ route('member.create.project') . '?project_id='.$project_id.'&edit=true'}}">Project Overview</a> --}}
            {{-- </li> --}}
            {{-- <li> --}}
            {{-- <a href="{{ route('create.remedydates', [$project_id]) . '?edit=true'}}">Job Dates</a> --}}
            {{-- </li> --}}
            {{-- <li class="active"> --}}
            {{-- <a href="{{ route('create.deadlines', [$project_id]). '?edit=true'}}">Deadlines</a> --}}
            {{-- </li> --}}
            {{-- <li> --}}
            {{-- <a href="{{ route('member.create.edit.jobdescription', [$project_id]). '?edit=true'}}">Job Description</a> --}}

            {{-- </li> --}}
            {{-- <li> --}}
            {{-- @php --}}
            {{-- $contractRoute = route('project.contract.view') . '?project_id='. $project_id . '&edit=true'; --}}
            {{-- @endphp --}}
            {{-- <a href="{{$contractRoute}}">Contract Details</a> --}}

            {{-- </li> --}}
            {{-- <li > --}}
            {{-- <a href="{{ route('member.create.projectcontacts', [$project_id]). '?edit=true'}}">Contacts</a> --}}
            {{-- </li> --}}
            {{-- <li> --}}
            {{-- <a href="{{ route('get.job.info.sheet', [$project_id])}}" target="_blank">Job Information Sheet</a> --}}

            {{-- </li> --}}
            {{-- </ul> --}}
            {{-- </div> --}}
            {{-- @else --}}
            {{-- <div class="select-view sidenav" id="mobileNavDetailed" data-menu="detailed"> --}}
            {{-- <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a> --}}

            {{-- <h3>Edit A Project</h3> --}}
            {{-- <div class="mobile-overview"> --}}
            {{-- <h3>Job Name:</h3> --}}
            {{-- <h4>{{$project->project_name}}</h4> --}}
            {{-- @if (isset($project->customer_contract->company) && !empty($project->customer_contract->company)) --}}
            {{-- <h3>Customer:</h3> --}}
            {{-- <h4>{{ !is_null($project->customer_contract->company) && !is_null($project->customer_contract->company->company) ?$project->customer_contract->company->company : 'N/A' }}</h4> --}}

            {{-- @endif --}}
            {{-- <h3>Job State:</h3> --}}
            {{-- <h4>{{ $project->state->name }}</h4> --}}
            {{-- <h3>Job Type:</h3> --}}
            {{-- <h4>{{ $project->project_type->project_type }}</h4> --}}
            {{-- </div> --}}
            {{-- <a href="{{ route('member.create.project') . '?project_id='.$project_id.'&edit=true'}}">Project Overview</a> --}}
            {{-- <a href="{{ route('create.remedydates', [$project_id]). '?edit=true'}}">Job Dates</a> --}}
            {{-- <a href="{{ route('create.deadlines', [$project_id]). '?edit=true'}}">Deadlines</a> --}}
            {{-- <a href="{{ route('member.create.edit.jobdescription', [$project_id]). '?edit=true'}}">Job Description</a> --}}
            {{-- @php --}}
            {{-- $contractRoute = route('project.contract.view') . '?project_id='. $project_id . '&edit=true'; --}}
            {{-- @endphp --}}
            {{-- <a href="{{$contractRoute}}">Contract Details</a> --}}
            {{-- <a href="{{ route('member.create.projectcontacts', [$project_id]). '?edit=true'}}">Contacts</a> --}}
            {{-- <a href="{{ route('get.job.info.sheet', [$project_id])}}" target="_blank">Job Information Sheet</a> --}}
            {{-- </div> --}}
            {{-- @endif --}}
            {{-- </div> --}}
            <div class="form-padding-wrapper">
        @endif
        <div class="row">
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
                    <div class="col-md-12 deadlines" style="display:flex;justify-content:center;gap:60px;">
                        <div style="display:flex; flex-direction:column;">
                        <div style="margin-bottom:10px"><b>{{ $deadline->getRemedy->remedy }}</b></div>
                                                 <div>
                                                    @if (strlen($daysRemain[$key]['preliminaryDates']) > 5)
                                                        <p class="prelim-date">Complete by: {{ $daysRemain[$key]['preliminaryDates'] }}</p>
                                                    @else
                                                        <p class="prelim-date">Complete by: N/A</p>
                                                    @endif
                                                </div>
                                    </div>
                                    <div style="display:flex;flex-direction:column;">
                                                <div>
                                                    <p>{{ $deadline->short_description }}</p>
                                                </div>
                                                <div>
                                                    @if (strlen($daysRemain[$key]['preliminaryDates']) > 5)
                                                        @if ($late === '+')
                                                            <p class="prelim-date prelim-date--late">Days Late: {{ $daysUntilDeadline }}</p>
                                                        @else
                                                            <p class="prelim-date">Days Remaining: {{ $daysUntilDeadline }}</p>
                                                        @endif
                                                    @else
                                                        <p class="prelim-date">Days Remaining: N/A</p>
                                                    @endif
                                                </div>
                                    </div>
                    </div>

                @endforeach

        </div>
        <div class="red" style="text-align:center;">WARNING: These are estimated job deadlines**</div>
        @endif

</div>
</div>
        <div class="flex items-center save-skip">

            <a href="javascript:void(0)" id="skip-button-3" class="skip">
                Skip
            </a>
            <a href="javascript:void(0)" id="saveDeadlines" class="orange-btn">
                Save & Continue
            </a>

        </div>


        <div class="col-md-12 col-sm-12 no-pad">
            @if (!isset($_GET['edit']))
                <h4 class="text-center">If you would like to complete a claim please select File A Claim, if not you may
                    save and quit.</h4>
                @if (isset($_GET['view']) && $_GET['view'] === 'detailed')
                    <a href="{{ route('member.create.edit.jobdescription', [$project_id]) . '?create=true&view=detailed' }}"
                        id="activate-step-4" class="project-create-continue">
                        File A Claim
                    </a>
                @else
                    <a href="{{ route('member.create.edit.jobdescription', [$project_id]) . '?create=true&view=detailed' }}"
                        id="activate-step-4" class="project-create-continue">
                        File A Claim
                    </a>
                @endif
                <a href="" id="save_quit" class="project-save-quit">
                    Save & Quit
                </a>
            @endif
        </div>
    </div>

    </div>
    </form>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('html, body').animate({
                scrollTop: $('#progressbar').offset().top
            }, 'slow');

            $(document).on('click', '#save_quit', function(e) {
                e.preventDefault()
                window.location = "{{ route('member.dashboard') }}"
            })
            $(document).on('click', '.mobile-nav-tab', function() {
                let tab = $(this).attr('data-tab')
                $('.mobile-nav--menu').attr('data-target', tab)
            })
            $(document).on('click', '.sidenav', function() {
                $(".sidenav").css('width', '0px');
            })

            // skip buttons
            // 13-aug-2019

            $('.skip').on('click', function(event) {

                //event.stopPropagation();
                event.preventDefault();
                swal({
                    title: 'Are you sure you want to skip this?',
                    // text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    // buttonsStyling: false
                }).then(function() {
                    window.location.href = '/member/project/task/view/' + project_id +
                        '?edit=true';
                })
            });

            $(document).on('click', '#saveDeadlines', function(event) {
                event.preventDefault();

                window.location.href = "{{ route('project.task.view', $project->id) . '?edit=true' }}";
            });
        })

        function openNav(e) {
            let menu = $('.mobile-nav--menu').attr('data-target')
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
        let token = '{{ csrf_token() }}'
        let baseUrl = "{{ env('ASSET_URL') }}"
        let project_id = "{{ $project_id }}"
        let submitDate = "{{ route('project.dates.submit') }}"
        let updateDate = "{{ route('project.dates.update') }}"
    </script>
    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/job_info.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/job_info_dates.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/createProject.js"></script>
@endsection
