<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectSaveRequest;
use App\Models\ProjectDates;
use App\Models\ProjectDetail;

class ProjectApiController extends Controller
{
    public function saveProject(ProjectSaveRequest $request)
    {

        $ins = [
            'project_name' => $request->projectName,
            'user_id' => auth()->id(),
            'state_id' => $request->stateId,
            'project_type_id' => $request->projectTypeId,
            'customer_id' => $request->customerTypeId,
            'role_id' => $request->roleId,
            'start_date' => $request->startDate ?? null,
            'esitmated_end_date' => $request->endDate ?? null,
            'address' => $request->jobAddress ?? null,
            'city' => $request->jobCity ?? null,
            'zip' => $request->jobZip ?? null,
            'county_id' => $request->jobCountyId ?? null,
            'description' => $request->jobName ?? null,
        ]; // this will insert wizard project details and project job description

        $project = ProjectDetail::create($ins);
        /**  Insert in Project Dates */
        $dates = $request->furnishingDates ?? [];
        if (!empty($dates) && is_array($dates)) {
            foreach ($dates as $key => $date) {
                $data[$key] = $date;
                ProjectDates::updateOrCreate(
                    [
                        'project_id' => $project->id,
                    ],
                    [
                        'date_id' => $key,
                        'date_value' => $date,
                    ]
                );
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Project saved successfully',
        ], 200);
    }
}
