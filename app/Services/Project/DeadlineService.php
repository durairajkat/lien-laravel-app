<?php

namespace App\Services\Project;

use App\Models\ProjectDates;

class DeadlineService
{
    public function calculateDaysRemaining($deadlineSteps, $project, $furnishingDates = [])
    {
        $daysRemain = [];
        foreach ($deadlineSteps as $key => $value) {
            $years = $value->years;
            $months = $value->months;
            $days = $value->days;
            $dateSelected = '';
            $preliminaryDeadline = '';
            $remedyDateId = $value->remedy_date_id;
            $daysRemain[$key]['dates'] = ($years * 365) + ($months * 30) + ($days * 1); // days conversion
            $daysRemain[$key]['remedy'] = $value->getRemedy->remedy;
            $daysRemain[$key]['remedyDateId'] = $value->remedy_date_id;

            if (isset($project->id)) {

                $preliminaryDeadline = ProjectDates::select('date_value')
                    ->where('project_id', $project->id)
                    ->where('date_id', $remedyDateId)->first();
            } else {
                if(!empty($furnishingDates)) {
                    $dateSelected = $furnishingDates[$remedyDateId] ?? '';
                }
            }

            $preliminaryDate = !empty($preliminaryDeadline) ? $preliminaryDeadline?->date_value : $dateSelected;

            if ($preliminaryDate != '') {

                $dateNew = date_create($preliminaryDate);
                date_add($dateNew, date_interval_create_from_date_string($years . " years " . $months . " months " . $days . " days"));
                if ($value->day_of_month != 0) {
                    $prelim = date_format($dateNew, "m/{$value->day_of_month}/Y");
                } else {
                    $prelim = date_format($dateNew, "m/d/Y");
                }
                $daysRemain[$key]['deadline'] = $preliminaryDate;
                $daysRemain[$key]['preliminaryDates'] = date($prelim);
            } else {
                $daysRemain[$key]['deadline'] = 'N/A';
                $daysRemain[$key]['preliminaryDates'] = 'N/A';
            }
        }
        return $daysRemain;
    }
}
