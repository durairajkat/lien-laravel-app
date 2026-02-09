<section class="multi_step_form">
    <form id="msform">
        <!-- progressbar -->
        <ul id="progressbar" class="tabs @if(Session::has('express')) express-progressbar @endif">
        @if(Session::get('express'))
            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('create.remedydates', [$project_id]) . '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('create.remedydates', [$project_id])) tablink-number-active @endif">1</span>
                <span class="tablink-body">Dates</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('project.summary.view', [$project_id]). '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('project.summary.view', [$project_id])) tablink-number-active @endif">2</span>
                <span class="tablink-body">Summary</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('get.job.info.sheet', [$project_id]). '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('get.job.info.sheet', [$project_id])) tablink-number-active @endif">3</span>
                <span class="tablink-body">Info Sheet</span>
            </li>

            @if(isset($project->state) && $project->state->name == 'Florida')
                <li class="tablink" onclick="window.location.href=(this.id)" id="{{ route('get.owner.notice.sheet', [$project_id]). '?edit=true'}}"> Notice to Owner</li>
            @endif
        @else

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('member.create.project') . '?project_id='.$project_id.'&edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('member.create.project')) tablink-number-active @endif">1</span>
                <span class="tablink-body">Details</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('create.remedydates', [$project_id]) . '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('create.remedydates', [$project_id])) tablink-number-active @endif">2</span>
                <span class="tablink-body">Dates</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('member.create.edit.jobdescription', [$project_id]). '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('member.create.edit.jobdescription', [$project_id])) tablink-number-active @endif">3</span>
                <span class="tablink-body">Description</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('project.contract.view') . '?project_id='. $project_id . '&edit=true' }}">
                <span class="tablink-number @if(url()->current() == route('project.contract.view')) tablink-number-active @endif">4</span>
                <span class="tablink-body">Contract</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('member.create.projectcontacts', [$project_id]). '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('member.create.projectcontacts', [$project_id])) tablink-number-active @endif">5</span>
                <span class="tablink-body">Contacts</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('get.project.documents', [$project_id]). '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('get.project.documents', [$project_id])) tablink-number-active @endif">6</span>
                <span class="tablink-body">Documents</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('create.deadlines', [$project_id]). '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('create.deadlines', [$project_id])) tablink-number-active @endif">7</span>
                <span class="tablink-body">Deadlines</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('project.task.view', [$project_id]). '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('project.task.view', [$project_id])) tablink-number-active @endif">8</span>
                <span class="tablink-body">Tasks</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('project.summary.view', [$project_id]). '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('project.summary.view', [$project_id])) tablink-number-active @endif">9</span>
                <span class="tablink-body">Summary</span>
            </li>

            <li class="tablink-spacer"></li>

            <li class="flex flex-col align-center tablink" onclick="window.location.href=(this.id)" id="{{ route('get.job.info.sheet', [$project_id]). '?edit=true'}}">
                <span class="tablink-number @if(url()->current() == route('get.job.info.sheet', [$project_id])) tablink-number-active @endif">10</span>
                <span class="tablink-body">Info Sheet</span>
            </li>
            @php
                $showNoticeOwner = \App\Http\Controllers\NotificationController::showProjectNoticeOwner($project);
            @endphp
            @if(isset($project->state) && $showNoticeOwner)
                <li class="tablink" onclick="window.location.href=(this.id)" id="{{ route('get.owner.notice.sheet', [$project_id]). '?edit=true'}}"> Notice to Owner</li>
            @endif
        @endif

        </ul>
    </form>
</section>
