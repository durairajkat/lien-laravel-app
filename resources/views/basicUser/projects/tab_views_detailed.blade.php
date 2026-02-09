<div class="time-nav">
    <div class="header-progress-container">
        <ol class="header-progress-list">

            <li class="header-progress-item done"> <a
                    href="{{ $project_id ? route('member.create.project') . '?project_id=' . $project_id : 'javascript:void(0)' }}">Project
                    Details</a></li>
            @if (\Request::route()->getName() == 'project.contract.view' || \Request::route()->getName() == 'project.date.view' || \Request::route()->getName() == 'project.deadline.view' || \Request::route()->getName() == 'project.document.view' || \Request::route()->getName() == 'project.task.view')
                <li class="header-progress-item done"> <a
                        href="{{ $project_id ? route('project.contract.view') . '?project_id=' . $project_id : 'javascript:void(0)' }}">CONTRACT
                        DETAILS</a></li>
            @else
                <li class="header-progress-item todo"> <a
                        href="{{ $project_id ? route('project.contract.view') . '?project_id=' . $project_id : 'javascript:void(0)' }}">CONTRACT
                        DETAILS</a></li>
            @endif
            @if (\Request::route()->getName() == 'project.date.view' || \Request::route()->getName() == 'project.deadline.view' || \Request::route()->getName() == 'project.document.view' || \Request::route()->getName() == 'project.task.view')
                <li class="header-progress-item done"> <a
                        href="{{ $project_id ? route('project.date.view') . '?project_id=' . $project_id : 'javascript:void(0)' }}">Dates</a>
                </li>
            @else
                <li class="header-progress-item todo"> <a
                        href="{{ $project_id ? route('project.date.view') . '?project_id=' . $project_id : 'javascript:void(0)' }}">Dates</a>
                </li>
            @endif
            @if (\Request::route()->getName() == 'project.deadline.view' || \Request::route()->getName() == 'project.document.view' || \Request::route()->getName() == 'project.task.view')
                <li class="header-progress-item done"> <a
                        href="{{ $project_id ? route('project.deadline.view') . '?project_id=' . $project_id : 'javascript:void(0)' }}">Deadlines</a>
                </li>
            @else
                <li class="header-progress-item todo"> <a
                        href="{{ $project_id ? route('project.deadline.view') . '?project_id=' . $project_id : 'javascript:void(0)' }}">Deadlines</a>
                </li>
            @endif
            @if (\Request::route()->getName() == 'project.document.view' || \Request::route()->getName() == 'project.task.view')
                <li class="header-progress-item done"> <a href="{{ route('get.job.info.sheet', [$project_id]) }}"
                        target="_blank">Documents</a></li>
            @else
                <li class="header-progress-item todo"> <a href="{{ route('get.job.info.sheet', [$project_id]) }}"
                        target="_blank">Documents</a></li>
            @endif
            @if (\Request::route()->getName() == 'project.task.view')
                <li class="header-progress-item done"> <a
                        href="{{ $project_id ? route('project.task.view') . '?project_id=' . $project_id : 'javascript:void(0)' }}">Tasks</a>
                </li>
            @else
                <li class="header-progress-item todo"> <a
                        href="{{ $project_id ? route('project.task.view') . '?project_id=' . $project_id : 'javascript:void(0)' }}">Tasks</a>
                </li>
            @endif
        </ol>
    </div>
</div>
