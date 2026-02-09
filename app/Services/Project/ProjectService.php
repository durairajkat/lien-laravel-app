<?php

namespace App\Services\Project;

use App\Models\Remedy;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\TierRemedyStep;
use App\Models\TierTable;

class ProjectService
{
    public function checkProjectRoleCustomers($request)
    {
        $remedy = Remedy::where('state_id', $request->state_id)
            ->where('project_type_id', $request->project_type_id);
        $remdey_date = RemedyDate::whereIn('remedy_id', $remedy->pluck('id'));
        $remedyStep = RemedyStep::whereIn('remedy_date_id', $remdey_date->pluck('id'));
        $tier = TierTable::where('role_id', $request->role_id);
        $tierRemedyStep = TierRemedyStep::whereIn('remedy_step_id', $remedyStep->pluck('id'))->whereIn('tier_id', $tier->pluck('id'));
        return TierTable::whereIn('id', $tierRemedyStep->pluck('tier_id'))->with('customer')->get();

    }
}
