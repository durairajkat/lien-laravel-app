<?php

namespace App\Http\Controllers;

use App\Models\Remedy;
use App\Models\TierTable;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\ProjectDetail;
use App\Models\TierRemedyStep;
use App\Models\NotificationSettings;
use Illuminate\Support\Facades\Auth;


class Notification
{
    public $days;
    public $project_name;
    public $project_id;
}

class NotificationController extends Controller
{
    public static function showProjectNoticeOwner($project) {
        $remedy = Remedy::where('state_id', $project->state_id)->where('project_type_id', $project->project_type_id);
        $remedyStepsCount = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'))->where('long_description', 'LIKE', '%Notice to Owner%')->count();
        if($remedyStepsCount > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getUserProjectDeadlines()
    {

        $user = Auth::user();
        $projects = ProjectDetail::where('user_id', $user->id)->get();
        $deoist = array();
        // var_dump($projects);
        $setting = NotificationSettings::where('user_id', Auth::user()->id)->first();
        if (isset($setting)) {
            $days_alert = $setting->days;
        } else {
            $days_alert = 0;
        }
        $deadline_list = array();

        foreach ($projects  as $project) {
            $remedy = Remedy::where('state_id', $project->state_id);
            $role_id = ProjectDetail::where('id', $project->id);
            $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
            $tiers = TierTable::where('role_id', $project->role_id);

            $tierRemedySteps = TierRemedyStep::whereIn('tier_id', $tiers->pluck('id'));

            $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));

            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();

            $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
            $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
            $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                ->whereIn('remedy_id', $remedy->pluck('id'));
            $deadlines = $deadline1->whereIn('id', $tierRem->pluck('remedy_step_id'))->get();

            foreach ($deadlines as $key => $value) {
                $years = $value->years;
                $months = $value->months;
                $days = $value->days;
                $remedyDateId = $value->remedy_date_id;
                $daysRemain = ($years * 365) + ($months * 30) + ($days * 1);
                if (($daysRemain <= $days_alert) && $daysRemain > 0) {
                    $deadline = new Notification();
                    $deadline->days = $daysRemain;
                    $deadline->project_name = $project->project_name;
                    $deadline->project_id = $project->id;

                    array_push($deadline_list, $deadline);
                }
            }
        }
        return $deadline_list;
    }
}
