<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use App\Models\State;
use App\Models\Company;
use App\Models\JobInfo;
use App\Models\ProjectType;
use App\Models\UserDetails;
use App\Models\ProjectDetail;
use App\Models\LienBoundSummary;
use App\Models\MapCompanyContact;
use App\Models\ProjectIndustryContactMap;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LienDocumentController extends Controller
{
    /**
     * getJobInfoSheet
     * @param $projectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getJobInfoSheet($projectId)
    {
		
        try {
            $companies = Company::pluck('company', 'id');
			//$companydetails = Company::where('user_id',$project->user_id)->first();
			// $state_id = Company::where('user_id',$project->user_id)->get(['state_id']);
			$state_id = 5;
            $firstNames = UserDetails::pluck('first_name', 'id');
            $project = ProjectDetail::find($projectId);
			
            if (!is_null($project)) {
                $states = State::all();
                $projectContactsCompany = ProjectIndustryContactMap::where('projectId', $projectId)->pluck('contactId');
                $companyContacts = MapCompanyContact::whereIn('id', $projectContactsCompany)->get();
                $projectContacts = [];
                $count = 0;
                $contactTypes = [];
                $contactArray = [];
                foreach ($companyContacts as $key => $companyContact) {
                    if (in_array($companyContact->contacts->contact_type, $contactTypes) && in_array($companyContact->company_id, $contactArray)) {
                        foreach ($projectContacts as $key1 => $contact) {
                            if ($contact['company_id'] == $companyContact->company_id && $contact['type'] == $companyContact->contacts->contact_type) {
                                $projectContacts[$key1]['customers'][] = $companyContact->contacts;
                                $projectContacts[$key1]['customer_id'] = $projectContacts[$key1]['customer_id'] . ',' . $companyContact->contacts->id;
                            }
                        }
                    } else {
                        $contactTypes[] = $companyContact->contacts->contact_type;
                        $contactArray[] = $companyContact->company_id;
                        $projectContacts[$count]['company_id'] = $companyContact->company_id;
                        $projectContacts[$count]['type'] = $companyContact->contacts->contact_type;
                        $projectContacts[$count]['company'] = $companyContact->company;
                        $projectContacts[$count]['customers'][] = $companyContact->contacts;
                        $projectContacts[$count]['customer_id'] = $companyContact->contacts->id;
                        $count++;
                    }
                }
                $jobInfoSheet = JobInfo::where('project_id', $projectId)->first();
				
                if ($jobInfoSheet != '') {
                    $user = User::findOrFail($jobInfoSheet->customer_company_id);
                } else {
                    $user = User::findOrFail($project->user_id);
                    
                }
				
				
                return view('lienProviders.document.job_info_sheet', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'state_id' => $state_id,
                    'user' => $user,
                    'states' => $states, 
                    'projectContacts' => $projectContacts,
                    'jobInfoSheet' => $jobInfoSheet,
                    'companies' => $companies,
                    'first_names' => $firstNames,
					
                ]);
				
            } else if (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            // return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Export job info form in PDF
     * @param $projectId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exportJobInfo($projectId)
    {
        ini_set('memory_limit', '-1');
        try {
            $project = ProjectDetail::findOrFail($projectId);
            $projectContactsCompany = ProjectIndustryContactMap::where('projectId', $project->id)->pluck('contactId');
            $companyContacts = MapCompanyContact::whereIn('id', $projectContactsCompany)->get();
            $projectContacts = [];
            $count = 0;
            $contactTypes = [];
            $contactArray = [];
            foreach ($companyContacts as $key => $companyContact) {
                if (in_array($companyContact->contacts->contact_type, $contactTypes) && in_array($companyContact->company_id, $contactArray)) {
                    foreach ($projectContacts as $key1 => $contact) {
                        if ($contact['company_id'] == $companyContact->company_id && $contact['type'] == $companyContact->contacts->contact_type) {
                            $projectContacts[$key1]['customers'][] = $companyContact->contacts;
                            $projectContacts[$key1]['customer_id'] = $projectContacts[$key1]['customer_id'] . ',' . $companyContact->contacts->id;
                        }
                    }
                } else {
                    $contactTypes[] = $companyContact->contacts->contact_type;
                    $contactArray[] = $companyContact->company_id;
                    $projectContacts[$count]['company_id'] = $companyContact->company_id;
                    $projectContacts[$count]['type'] = $companyContact->contacts->contact_type;
                    $projectContacts[$count]['company'] = $companyContact->company;
                    $projectContacts[$count]['customers'][] = $companyContact->contacts;
                    $projectContacts[$count]['customer_id'] = $companyContact->contacts->id;
                    $count++;
                }
            }
            $jobInfoSheet = JobInfo::where('project_id', $project->id)->first();
            if ($jobInfoSheet != '') {
                $user = User::findOrFail($jobInfoSheet->customer_company_id);
            } else {
                $user = User::findOrFail($project->user_id);
            }
            /*return view('basicUser.document.job_info_sheet_export', [
            'project_id' => $project->id,
            'project' => $project,
            'user' => $user,
            'projectContacts' => $projectContacts,
            'jobInfoSheet' => $jobInfoSheet
        ]);*/
            $pdf = PDF::loadView('basicUser.document.job_info_sheet_export', [
                'project_id' => $project->id,
                'project' => $project,
                'user' => $user,
                'projectContacts' => $projectContacts,
                'jobInfoSheet' => $jobInfoSheet
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(str_replace(' ', '-', $project->project_name) . '.pdf');
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Line Bound Summery Form
     * @param $state
     * @param $projectType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLineBoundSummery($state, $projectType)
    {
        try {
            $lineBoundSummery = LienBoundSummary::where('state_id', $state)
                ->where('project_type_id', $projectType)->get();
            $state = State::findOrFail($state);
            $project = ProjectType::findOrFail($projectType);
            return view('basicUser.document.line_bound_summery', [
                'data' => $lineBoundSummery,
                'state' => $state,
                'projectType' => $project
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }
}
