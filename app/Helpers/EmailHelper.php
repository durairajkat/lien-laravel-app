<?php

use App\Models\ProjectEmail;
use App\Models\ProjectDeadline;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailHelper
{
    public static function getEmails()
    {
        //return 1;
        try {
            //$project = ProjectDetail::all();
            // if(count($project)>0)
            // {
            $email_id = ProjectEmail::all();
            $todays_date = date('Y-m-d', strtotime("+1 days"));
            // /   $today=
            $deadline = ProjectDeadline::select('project_id', 'remedy_step_id')->where('preliminary_deadline', $todays_date)->where('status', 1)->get();
            $project = [];
            foreach ($deadline as $key => $value) {

                $project_email = ProjectEmail::select('project_emails')->where('project_id', $value->project_id)->get();
                $emails = [];
                foreach ($project_email as $pkey => $pvalue) {
                    $emails[$pkey] = $pvalue->project_emails;
                }

                $project[$key]['emails'] = $emails;
                $project[$key]['desc'] = $value->remedy_step->short_description;
            }

            //dd($date_dealine->project_id);

            return $project;
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }
}
