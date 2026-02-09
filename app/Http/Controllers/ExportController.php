<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\State;
use App\Models\Remedy;
use App\Models\TierTable;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\ProjectRole;
use App\Models\ProjectType;
use App\Models\CustomerCode;
use Illuminate\Http\Request;
use App\Models\RemedyQuestion;
use App\Models\TierRemedyStep;
use App\Models\JobRequestLabel;
use App\Models\LienBoundSummary;
use App\Models\JobRequestDeadline;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\JobRequestCombination;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ExportController for Export and Import Excel Data sheets
 * @package App\Http\Controllers
 */
class ExportController extends Controller
{
    /**
     * Export Deadline form Excel
     */
    public function deadline()
    {
        $file = public_path() . '/files/deadline.xls';
        Excel::load($file, function ($reader) {
            $results = $reader->all();
            foreach ($results[3] as $key => $value) {
                $roles = explode(',', $value->tiertype);
                $state = State::where('short_code', trim($value->state))->first();
                if (strtolower(trim($value->projtype)) == 'priv' || strtolower(trim($value->projtype)) == 'pri') {
                    $type = ProjectType::where('project_type', 'private')->first();
                } elseif (strtolower(trim($value->projtype)) == 'pub') {
                    $type = ProjectType::where('project_type', 'public')->first();
                } else {
                    dd($value);
                }
                foreach ($roles as $role) {
                    $tier_id = TierTable::where('tier_code', trim($role))->first();
                    if ($tier_id != '') {
                        $combinationFind = JobRequestCombination::where('state_id', $state->id)
                            ->where('role_id', $tier_id->id)
                            ->where('type_id', $type->id)->first();
                        if ($combinationFind == '') {
                            $combinationFind = new JobRequestCombination();
                            $combinationFind->state_id = $state->id;
                            $combinationFind->role_id = $tier_id->id;
                            $combinationFind->type_id = $type->id;
                            $combinationFind->save();
                        }
                        $label = JobRequestLabel::where('combination_id', $combinationFind->id)
                            ->where('label', $value->datefields)->first();
                        if ($label == '') {
                            $label = new JobRequestLabel();
                            $label->combination_id = $combinationFind->id;
                            $label->label = $value->datefields;
                            $label->save();
                        }
                        $deadline = new JobRequestDeadline();
                        $deadline->name = $value->answer;
                        $deadline->combination_id = $combinationFind->id;
                        $deadline->days = (int)$value->answer1;
                        $deadline->label_id = $label->id;
                        $deadline->save();
                    } else {
                        echo 'skip\n';
                    }
                }
            }
        });
        dd('<h1>Operation Successfull</h1>');
    }

    /**
     * Upload All remedy Sheet from admin panel
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadExcel(Request $request)
    {
        try {
            $file = $request->file;
            Excel::load($file, function ($reader) {
                $results = $reader->all();
                foreach ($results as $key => $result) {
                    if ($result->getTitle() == 'Tiers') {
                        foreach ($result as $value) {
                            $tiers = TierTable::where('tier_code', trim($value['tiercode']))->count();
                            if ($tiers == 0) {
                                $customer = CustomerCode::where('name', trim($value['soldto']))->first();
                                if ($customer == '') {
                                    $customer = new CustomerCode();
                                    $customer->name = trim($value['soldto']);
                                    $customer->save();
                                }
                                $role = ProjectRole::where('project_roles', trim($value['businesstype']))->first();
                                if ($role == '') {
                                    $role = new ProjectRole();
                                    $role->project_roles = trim($value['businesstype']);
                                    $role->save();
                                }
                                $customer = CustomerCode::firstOrCreate(['name' => trim($value['soldto'])]);
                                $role = ProjectRole::firstOrCreate(['project_roles' => trim($value['businesstype'])]);
                                try {
                                    $newTier = new TierTable();
                                    $newTier->id = (int)trim($value['id']);
                                    $newTier->tier_code = trim($value['tiercode']);
                                    $newTier->role_id = $role->id;
                                    $newTier->tier_limit = $value['description'];
                                    $newTier->customer_id = $customer->id;
                                    $newTier->save();
                                } catch (Exception $ex) {
                                    Log::info('Excel import error : ' . $ex->getMessage());
                                }
                            }
                        }
                    } elseif ($result->getTitle() == 'Remedies') {
                        foreach ($result as $value) {
                            $remedy = Remedy::find((int)trim($value['id']));
                            if ($remedy == '') {
                                $state = State::where('short_code', trim($value['state']))->firstOrFail();
                                $projectType = ProjectType::where('project_type', trim($value['projecttype']))->firstOrFail();
                                try {
                                    $remedy = new Remedy();
                                    $remedy->id = (int)trim($value['id']);
                                    $remedy->state_id = $state->id;
                                    $remedy->project_type_id = $projectType->id;
                                    $remedy->remedy = trim($value['remedy']);
                                    $remedy->save();
                                } catch (Exception $ex) {
                                    Log::info('Excel import error : ' . $ex->getMessage());
                                }
                            }
                        }
                    } elseif ($result->getTitle() == 'Remedy Dates') {
                        foreach ($result as $value) {
                            $remedy_date = RemedyDate::find((int)trim($value['id']));
                            if ($remedy_date == '') {
                                $remedy_date = new RemedyDate();
                                $remedy_date->id = (int)trim($value['id']);
                                $remedy_date->remedy_id = (int)trim($value['remedyid']);
                                $remedy_date->date_name = trim($value['datename']);
                                $remedy_date->date_order = (int)trim($value['dateorder']);
                                $remedy_date->date_number = trim($value['datenumber']);
                                $remedy_date->recurring = (int)trim($value['recurring']);
                                $remedy_date->save();
                            }
                        }
                    } elseif ($result->getTitle() == 'Remedy Questions') {
                        foreach ($result as $value) {
                            $remedy_question = RemedyQuestion::find((int)trim($value['id']));
                            if ($remedy_question == '') {
                                $state = State::where('short_code', trim($value['state']))->first();
                                $projectType = ProjectType::where('project_type', trim($value['projecttype']))->first();
                                $tier = TierTable::where('tier_code', trim($value['tiercode']))->first();
                                //$remedy = Remedy::where('id', trim($value['tiercode']))->first();

                                try {
                                    $remedy_question = new RemedyQuestion();
                                    $remedy_question->id = (int)trim($value['id']);
                                    $remedy_question->state_id = $state->id;
                                    $remedy_question->project_type_id = $projectType->id;
                                    $remedy_question->remedy_id = (int)trim($value['remedyid']);
                                    $remedy_question->tier_id = $tier->id;
                                    $remedy_question->question_order = (int)trim($value['questionorder']);
                                    $remedy_question->question = trim($value['question']);
                                    $remedy_question->answer = trim($value['answers']);
                                    $remedy_question->save();
                                } catch (Exception $ex) {
                                    Log::info('Excel import error : ' . $ex->getMessage());
                                }
                            }
                        }
                    } elseif ($result->getTitle() == 'Remedy Steps') {
                        foreach ($result as $value) {
                            $remedySteps = RemedyStep::find((int)trim($value['id']));
                            if ($remedySteps == '') {
                                try {
                                    $remedySteps = new RemedyStep();
                                    $remedySteps->id = (int)trim($value['id']);
                                    $remedySteps->remedy_date_id = (int)trim($value['remedydateid']);
                                    $remedySteps->remedy_id = (int)trim($value['remedyid']);
                                    $remedySteps->short_description = trim($value['shortdescription']);
                                    $remedySteps->long_description = trim($value['longdescription']);
                                    $remedySteps->years = trim($value['years']) == '' ? 0 : trim($value['years']);
                                    $remedySteps->months = trim($value['months']) == '' ? 0 : trim($value['months']);
                                    $remedySteps->days = trim($value['days']) == '' ? 0 : trim($value['days']);
                                    $remedySteps->day_of_month = trim($value['dayofmonth']) == '' ? 0 : trim($value['dayofmonth']);
                                    $remedySteps->notes = trim($value['notes']);
                                    $remedySteps->save();
                                } catch (Exception $ex) {
                                    Log::info('Excel import issue : ' . $ex->getMessage());
                                }
                            }
                        }
                    } elseif ($result->getTitle() == 'Tier Remedy Steps') {
                        foreach ($result as $value) {
                            try {
                                $tierRemedyStep = TierRemedyStep::find((int)trim($value['id']));
                                if ($tierRemedyStep == '') {
                                    $tierRemedyStep = new TierRemedyStep();
                                    $tierRemedyStep->id = (int)trim($value['id']);
                                    $tierRemedyStep->tier_id = (int)trim($value['tierid']);
                                    $tierRemedyStep->remedy_step_id = (int)trim($value['remedystepid']);
                                    $tierRemedyStep->answer1 = trim($value['answer1']);
                                    $tierRemedyStep->answer2 = trim($value['answer2']);
                                    $tierRemedyStep->save();
                                }
                            } catch (Exception $ex) {
                                Log::info('Excel import error : ' . $ex->getMessage());
                            }
                        }
                    }
                }
            });
            return redirect()->back()->with('success', 'Uploaded Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * Upload line bound summery details
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lineBoundSummery()
    {
        try {
            LienBoundSummary::truncate();
            $file = public_path() . '/files/BondSummary.xlsx';

            Excel::load($file, function ($render) {
                $results = $render->all();
                foreach ($results as $result) {
                    try {
                        $lineBoundSummary = new LienBoundSummary();
                        $state = State::where('short_code', trim($result['st']))->first();
                        $projectType = ProjectType::where('project_type', trim($result['pt']))->first();
                        $breaks = array("<br />", "<br>", "<br/>");
                        $lineBoundSummary->state_id = $state->id;
                        $lineBoundSummary->project_type_id = $projectType->id;
                        $lineBoundSummary->rights_available = str_ireplace($breaks, "\r\n", $result['rightsavailable']);
                        $lineBoundSummary->claimant = str_ireplace($breaks, "\r\n", $result['claimant']);
                        $lineBoundSummary->prelim_notice = str_ireplace($breaks, "\r\n", $result['prelimnotice']);
                        $lineBoundSummary->other_notice = str_ireplace($breaks, "\r\n", $result['othernotice']);
                        $lineBoundSummary->lien = str_ireplace($breaks, "\r\n", $result['lien']);
                        $lineBoundSummary->suit = str_ireplace($breaks, "\r\n", $result['suit']);
                        $lineBoundSummary->notes = str_ireplace($breaks, "\r\n", $result['notes']);
                        $lineBoundSummary->save();
                    } catch (Exception $e) {
                        Log::info($e->getMessage());
                    }
                }
            });
            return redirect()->route('member.dashboard')->with('success', 'Uploaded Successfully');
        } catch (Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Read All remedy Sheet from admin panel and check different data
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function oldImport()
    {
        try {
            $file = public_path() . '/files/OldDatabaseRemedy.xls';
            //dd($file);
            //$file = $request->file;
            Excel::load($file, function ($reader) {
                $results = $reader->all();
                //  dd($results[3]);
                foreach ($results as $key => $result) {
                    if ($result->getTitle() == 'Tiers') {
                        foreach ($result as $value) {
                            $tiers = TierTable::where('tier_code', trim($value['tiercode']))->count();
                            if ($tiers == 0) {
                                $customer = CustomerCode::where('name', trim($value['soldto']))->first();
                                if ($customer == '') {
                                    $customer = new CustomerCode();
                                    $customer->name = trim($value['soldto']);
                                    $customer->save();
                                }
                                $role = ProjectRole::where('project_roles', trim($value['businesstype']))->first();
                                if ($role == '') {
                                    $role = new ProjectRole();
                                    $role->project_roles = trim($value['businesstype']);
                                    $role->save();
                                }
                                $customer = CustomerCode::firstOrCreate(['name' => trim($value['soldto'])]);
                                $role = ProjectRole::firstOrCreate(['project_roles' => trim($value['businesstype'])]);
                                try {
                                    $newTier = new TierTable();
                                    $newTier->id = (int)trim($value['id']);
                                    $newTier->tier_code = trim($value['tiercode']);
                                    $newTier->role_id = $role->id;
                                    $newTier->tier_limit = $value['description'];
                                    $newTier->customer_id = $customer->id;
                                    $newTier->save();
                                } catch (Exception $ex) {
                                    Log::info('Excel import error : ' . $ex->getMessage());
                                }
                            }
                        }
                    } elseif ($result->getTitle() == 'Remidy') {
                        foreach ($result as $value) {
                            $remedy = Remedy::find((int)trim($value['id']));
                            if ($remedy == '') {
                                $state = State::where('short_code', trim($value['state']))->firstOrFail();
                                $projectType = ProjectType::where('project_type', trim($value['projecttype']))->firstOrFail();
                                try {
                                    $remedy = new Remedy();
                                    $remedy->id = (int)trim($value['id']);
                                    $remedy->state_id = $state->id;
                                    $remedy->project_type_id = $projectType->id;
                                    $remedy->remedy = trim($value['remedy']);
                                    $remedy->save();
                                } catch (Exception $ex) {
                                    Log::info('Excel import error : ' . $ex->getMessage());
                                }
                            }
                        }
                    } elseif ($result->getTitle() == 'RemidyDates') {
                        foreach ($result as $value) {
                            $remedy_date = RemedyDate::find((int)trim($value['id']));
                            if ($remedy_date == '') {
                                $remedy_date = new RemedyDate();
                                $remedy_date->id = (int)trim($value['id']);
                                $remedy_date->remedy_id = (int)trim($value['remedyid']);
                                $remedy_date->date_name = trim($value['datename']);
                                $remedy_date->date_order = (int)trim($value['dateorder']);
                                $remedy_date->date_number = trim($value['datenumber']);
                                $remedy_date->recurring = (int)trim($value['recurring']);
                                $remedy_date->save();
                            }
                        }
                    } elseif ($result->getTitle() == 'RemidyQuestions') {
                        foreach ($result as $value) {
                            $remedy_question = RemedyQuestion::find((int)trim($value['id']));
                            if ($remedy_question == '') {
                                $state = State::where('short_code', trim($value['state']))->first();
                                $projectType = ProjectType::where('project_type', trim($value['projecttype']))->first();
                                $tier = TierTable::where('tier_code', trim($value['tiercode']))->first();
                                //$remedy = Remedy::where('id', trim($value['tiercode']))->first();

                                try {
                                    $remedy_question = new RemedyQuestion();
                                    $remedy_question->id = (int)trim($value['id']);
                                    $remedy_question->state_id = $state->id;
                                    $remedy_question->project_type_id = $projectType->id;
                                    //$remedy_question->remedy_id = (int)trim($value['remedyid']);
                                    $remedy_question->tier_id = $tier->id;
                                    $remedy_question->question_order = (int)trim($value['questionorder']);
                                    $remedy_question->question = trim($value['question']);
                                    $remedy_question->answer = trim($value['answers']);
                                    $remedy_question->save();
                                } catch (Exception $ex) {
                                    Log::info('Excel import error : ' . $ex->getMessage());
                                }
                            }
                        }
                    } elseif ($result->getTitle() == 'RemidySteps') {
                        foreach ($result as $value) {
                            $remedySteps = RemedyStep::find((int)trim($value['id']));
                            if ($remedySteps == '') {
                                try {
                                    $remedySteps = new RemedyStep();
                                    $remedySteps->id = (int)trim($value['id']);
                                    $remedySteps->remedy_date_id = (int)trim($value['remedydateid']);
                                    $remedySteps->remedy_id = (int)trim($value['remedyid']);
                                    $remedySteps->short_description = trim($value['shortdescription']);
                                    $remedySteps->long_description = trim($value['longdescription']);
                                    $remedySteps->years = trim($value['years']) == '' ? 0 : trim($value['years']);
                                    $remedySteps->months = trim($value['months']) == '' ? 0 : trim($value['months']);
                                    $remedySteps->days = trim($value['days']) == '' ? 0 : trim($value['days']);
                                    $remedySteps->day_of_month = trim($value['dayofmonth']) == '' ? 0 : trim($value['dayofmonth']);
                                    $remedySteps->notes = trim($value['notes']);
                                    $remedySteps->save();
                                } catch (Exception $ex) {
                                    Log::info('Excel import issue : ' . $ex->getMessage());
                                }
                            }
                        }
                    } elseif ($result->getTitle() == 'TiersRemedySteps') {
                        foreach ($result as $value) {
                            try {
                                $tierRemedyStep = TierRemedyStep::find((int)trim($value['id']));
                                if ($tierRemedyStep == '') {
                                    $tierRemedyStep = new TierRemedyStep();
                                    $tierRemedyStep->id = (int)trim($value['id']);
                                    $tierRemedyStep->tier_id = (int)trim($value['tierid']);
                                    $tierRemedyStep->remedy_step_id = (int)trim($value['remedystepid']);
                                    $tierRemedyStep->answer1 = trim($value['answer1']);
                                    $tierRemedyStep->answer2 = trim($value['answer2']);
                                    $tierRemedyStep->save();
                                }
                            } catch (Exception $ex) {
                                Log::info('Excel import error : ' . $ex->getMessage());
                            }
                        }
                    }
                }
            });
            return redirect()->back()->with('success', 'Uploaded Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * Old database Export
     */
    public function oldExport()
    {
        $tiers = TierTable::all();
        $remedy = Remedy::all();
        $remedy_date = RemedyDate::all();
        $remedy_question = RemedyQuestion::all();
        $remedy_steps = RemedyStep::all();
        $tier_remedy_steps = TierRemedyStep::all();
        $tier_array = [];
        $remedy_array = [];
        $remedy_date_array = [];
        $remedy_question_array = [];
        $remedy_steps_array = [];
        $tier_remedy_steps_array = [];

        $tier_array[] = ['id', 'BusinessType', 'SoldTo', 'TierCode', 'Description', 'Modified', 'Created'];
        $remedy_array[] = ['ID', 'State', 'ProjectType', 'Remedy', 'Modified', 'Created'];
        $remedy_date_array[] = ['ID', 'RemedyID', 'DateName', 'DateOrder', 'DateNumber', 'Recurring', 'Modified', 'Created'];
        $remedy_question_array[] = ['ID', 'State', 'ProjectType', 'TierCode', 'QuestionOrder', 'Question', 'Modified', 'Created'];
        $remedy_steps_array[] = ['ID', 'RemedyDateID', 'RemedyID', 'ShortDescription', 'LongDescription', 'Years', 'Months', 'Days', 'DayOfMonth', 'Notes', 'Modified', 'Created'];
        $tier_remedy_steps_array[] = ['ID', 'TierID', 'RemedyStepID', 'Answer1', 'Answer2', 'Modified', 'Created'];

        foreach ($tiers as $tier) {
            $tier_array[] = $tier->toArray();
        }
        foreach ($remedy as $remedy) {
            $remedy_array[] = $remedy->toArray();
        }

        foreach ($remedy_date as $remedy_date) {
            $remedy_date_array[] = $remedy_date->toArray();
        }
        foreach ($remedy_question as $remedy_question) {
            $remedy_question_array[] = $remedy_question->toArray();
        }
        foreach ($remedy_steps as $remedy_steps) {
            $remedy_steps_array[] = $remedy_steps->toArray();
        }
        foreach ($tier_remedy_steps as $tier_remedy_steps) {
            $tier_remedy_steps_array[] = $tier_remedy_steps->toArray();
        }
        Excel::create('OldDatabaseRemedyData', function ($excel) use ($tier_array, $remedy_array, $remedy_date_array, $remedy_question_array, $remedy_steps_array, $tier_remedy_steps_array) {


            $excel->setTitle('Tiers');


            $excel->sheet('Tiers', function ($sheet) use ($tier_array) {
                $sheet->fromArray($tier_array, null, 'A1', false, false);
            });
            $excel->setTitle('Remidy');


            $excel->sheet('Remidy', function ($sheet) use ($remedy_array) {
                $sheet->fromArray($remedy_array, null, 'A1', false, false);
            });
            $excel->setTitle('RemidyDates');


            $excel->sheet('RemidyDates', function ($sheet) use ($remedy_date_array) {
                $sheet->fromArray($remedy_date_array, null, 'A1', false, false);
            });
            $excel->setTitle('RemidyQuestions');

            $excel->sheet('RemidyQuestions', function ($sheet) use ($remedy_question_array) {
                $sheet->fromArray($remedy_question_array, null, 'A1', false, false);
            });
            $excel->setTitle('RemidySteps');


            $excel->sheet('RemidySteps', function ($sheet) use ($remedy_steps_array) {
                $sheet->fromArray($remedy_steps_array, null, 'A1', false, false);
            });
            $excel->setTitle('TiersRemedySteps');


            $excel->sheet('TiersRemedySteps', function ($sheet) use ($tier_remedy_steps_array) {
                $sheet->fromArray($tier_remedy_steps_array, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
