<?php

namespace App\Services\Project;

use App\Models\ProjectDates;

class DeadlineService
{
    public function calculateDaysRemaining($deadlineSteps, $project)
    {
        $daysRemain = [];
        foreach ($deadlineSteps as $key => $value) {
            $years = $value->years;
            $months = $value->months;
            $days = $value->days;
            $remedyDateId = $value->remedy_date_id;
            $daysRemain[$key]['dates'] = ($years * 365) + ($months * 30) + ($days * 1); // days conversion
            $daysRemain[$key]['remedy'] = $value->getRemedy->remedy;
            $daysRemain[$key]['remedyDateId'] = $value->remedy_date_id;

            if (isset($project->id)) {

                $preliminaryDeadline = ProjectDates::select('date_value')
                    ->where('project_id', $project->id)
                    ->where('date_id', $remedyDateId)->first();
            }

            if (isset($preliminaryDeadline) && $preliminaryDeadline != null && $preliminaryDeadline->date_value != '') {

                $dateNew = date_create($preliminaryDeadline->date_value);
                date_add($dateNew, date_interval_create_from_date_string($years . " years " . $months . " months " . $days . " days"));
                if ($value->day_of_month != 0) {
                    $prelim = date_format($dateNew, "m/{$value->day_of_month}/Y");
                } else {
                    $prelim = date_format($dateNew, "m/d/Y");
                }
                $daysRemain[$key]['deadline'] = $preliminaryDeadline->date_value;
                $daysRemain[$key]['preliminaryDates'] = date($prelim);
            } else {
                $daysRemain[$key]['deadline'] = 'N/A';
                $daysRemain[$key]['preliminaryDates'] = 'N/A';
            }
        }
        return $daysRemain;
    }
}
