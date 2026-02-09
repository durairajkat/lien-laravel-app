<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use App\User;
use Exception;
use Carbon\Carbon;
use App\Models\State;
use App\Models\Remedy;
use App\Models\Company;
use App\Models\JobInfo;
use App\Models\TierTable;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\JobContract;
use App\Models\ProjectRole;
use App\Models\ProjectTask;
use App\Models\ProjectType;
use App\Models\UserDetails;
use App\Models\JobInfoFiles;
use App\Models\LienProvider;
use App\Models\ProjectDates;
use Illuminate\Http\Request;
use App\Models\ProjectDetail;
use App\Models\CompanyContact;
use App\Models\TierRemedyStep;
use App\Models\ProjectContract;
use App\Models\LienLawSlideChart;
use App\Models\MapCompanyContact;
use App\Jobs\SendJobInfoController;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectIndustryContactMap;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ProjectController for Project System
 * This controller was designed to allow future pages to built with Vine, without having to utilize too much of the Laravel framework
 * Please see the 'Vine' folder for more
 * @package App\Http\Controllers
 */
class VineTransferController extends Controller
{

    /**
     * This function will replace the view() function for transferring information to a vine oriented page, please see the 'Vine' folder
     * @param $view
     * @param $vars
     */
    public function vineView($view, $vars)
    {

        // this code will be enhanced later, right now just trying to achieve the desired functionality

        require_once 'vine/' . $view . '.php';
    }

    /**
     * This function just returns a list of actions predefined by the client
     * getMappedActions
     * @return Array of task actions
     */
    public function getMappedActions()
    {
        $task_actions = [
            'Call Customer',
            'Follow Up Payment',
            'Prepare Waivers for Draw',
            'Prepare Credit Application',
            'Prepare Rider to Contract',
            'Forward Claim to NLB',
            'Other'
        ];

        $task_actions_mapped = [];

        foreach ($task_actions as $key => $val) {
            $task_actions_mapped[$val] = $val;
        }

        return $task_actions_mapped;
    }

    public function mapDropdownArray($arr)
    {
        $task_actions_mapped = [];

        foreach ($arr as $key => $val) {
            $task_actions_mapped[$val] = $val;
        }

        return $task_actions_mapped;
    }

    public function sendClaim(Request $request)
    {
        $json = ['result' => 'true'];

        $project_id = $request->get('project_id');

        $project = ProjectDetail::find($project_id);
        $project_id = $project->id; // for safety

        $jobInfoSheet = JobInfo::where('project_id', $project_id)->first();
        $user = User::findOrFail($project->user_id);

        $json['title'] = 'Send Claim for ' . $project->project_name;

        if (isset($_GET['save'])) {
            $save = $_GET['save'];
            return $this->saveJobInfo($request, $project->id);
        }

        if (isset($_GET['upload'])) {
            return $this->uploadFileJSON($request, $project->id, $jobInfoSheet);
        }

        $json['info_sheet_active'] = (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? false : true;

        return response()->json($json, 200);
    }

    public function uploadFileJSON(Request $request, $project_id, $jobInfoSheet)
    {

        $json = ['result' => 'true'];
        $json['upload_result'] = 'test';


        $project = ProjectDetail::find($project_id);
        $user = User::findOrFail($project->user_id);


        ini_set('memory_limit', '-1');
        $code = $message = $status = '';
        try {
            if ($request->hasFile('lien')) {
                $extension = \File::extension($request->lien->getClientOriginalName());
                if (strtolower($extension) != "sql") {
                    $file = $request->lien;
                    $fileName = time() . '.' . $extension;
                    $filePath = public_path() . "/upload";
                    $file->move($filePath, $fileName);

                    $lien = JobInfo::where('project_id', $project_id)->first();
                    if ($lien == '') {
                        $lien = new JobInfo();
                        $lien->project_id = $project_id;
                        $lien->customer_company_id = $user->details->getCompany->id;

                        $lien->status = '1';

                        $lien->save();
                    }

                    $jobFile = new JobInfoFiles();
                    $jobFile->job_info_id = $lien->id;
                    $jobFile->file = $fileName;
                    $jobFile->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Upload successful',
                        'name' => $fileName,
                        'filename' => $file->getClientOriginalName(),
                        'time' => str_replace('.', '_', $fileName),
                    ], 200);
                } else {
                    $status = false;
                    $message = 'Thats not a valid file';
                    $code = 200;
                }
            } else {
                $status = false;
                $message = 'Please select a file';
                $code = 200;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $code = 200;
        } catch (ModelNotFoundException $e) {
            $status = false;
            $message = $e->getMessage();
            $code = 200;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }

    /**
     * Save and Edit Job Info Sheet
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveJobInfo(Request $request, $project_id)
    {
        $fields = [];
        $json = ['result' => 'true'];

        foreach ($_GET['save'] as $field) {
            $name = $field['name'];
            $val = $field['value'];

            $fields[$name] = $val;
        }

        if (empty($fields['Agree'])) $json['errors'][] = "You must agree to the terms to continue";
        if (empty($fields['Signature'])) $json['errors'][] = "Please provide your signature";
        if (empty($fields['SignatureDate'])) $json['errors'][] = "Please provide your signature date";

        if (empty($json['errors'])) {

            ini_set('memory_limit', '-1');

            $customerContract = ProjectContract::where('project_id', $project_id)->first();

            $project = ProjectDetail::findOrFail($project_id);
            $lien = JobInfo::where('project_id', $project_id)->first();
            if ($lien == '') {
                $lien = new JobInfo();
                $lien->project_id = $project_id;
            }

            //$lien->customer_company_id = $request->customer_company_id;
            //$lien->contract_amount = $request->contract_amount;
            //$lien->first_day_of_work = $request->first_day_of_work;
            //$lien->is_gc = $request->is_gc;
            $lien->signature = $fields['Signature'];
            $lien->signature_date = $fields['SignatureDate'];


            if ($request->send == 'save') {
                $lien->status = '1';
            } else {
                $lien->status = '2';
                $lien->date_completed = Carbon::now();
            }

            $lien->save();
            JobContract::where('job_info_id', $lien->id)->delete();

            if ($request->industry_id != '' && count($request->industry_id) > 0) {
                foreach ($request->industry_id as $key => $id) {
                    $lienContract = new JobContract();
                    $lienContract->job_info_id = $lien->id;
                    $lienContract->industry_id = $id;
                    $lienContract->save();
                }
            }


            if ($request->send != 'save') {
                $state = State::all();
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
                $user = User::findOrFail($project->user_id);


                $pdf = PDF::loadView('basicUser.document.job_info_sheet_export', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'user' => $user,
                    'projectContacts' => $projectContacts,
                    'jobInfoSheet' => $jobInfoSheet,
                    'contract' => $customerContract
                ]);

                $pdf->setPaper('A4', 'portrait');
                $output = $pdf->output();
                $path = public_path() . '/job_info/';
                $fileName = str_replace(' ', '-', $project->project_name) . time() . '.pdf';
                $actualPath = env('ASSET_URL') . '/job_info/';

                file_put_contents($actualPath . $fileName, $output);

                $user = User::findOrFail(Auth::user()->id);
                $lienProvider = [];
                if ($user->details != '' && $user->details->lien_status == '1') {
                    $userProvider = $user->lienProvider;
                    if (!is_null($userProvider)) {
                        foreach ($userProvider as $pro) {
                            if ($pro->getLien != '') {
                                foreach ($pro->getLien as $proL) {
                                    $lienProvider[] = $proL->email;
                                }
                            }
                        }
                    }
                } elseif ($user->details = '' || $user->details->lien_status == '0') {
                    $nationalProvider = LienProvider::where('id', '3')->first();
                    $lienProvider[] = $nationalProvider->email;
                }


                $files = JobInfoFiles::where('job_info_id', $lien->id)->pluck('file');
                $admin = User::where('role', '1')->pluck('email');
                $adminDetails = User::where('role', '1')->firstOrFail();
                /*if (count($lienProvider) == 0) {
                    $lienProvider = $admin->toArray();
                }*/
                SendJobInfoController::dispatch($lienProvider, $admin->toArray(), $actualPath . $fileName, $files, $adminDetails, Auth::user()->email);
            }

            $json['message'] = "Claim Sent";
        }

        return response()->json($json, 200);
    }

    /**
     * getJobInfoSheet
     * @param $projectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewDash(Request $request)
    {
        try {
            $projectId = $request->get('project_id');

            $project = ProjectDetail::find($projectId);
            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
            $tiers = TierTable::where('role_id', $project->role_id)
                ->where('customer_id', $project->customer_id)->firstOrFail();
            $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
                ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
                ->whereIn('answer1', [$project->answer1, ''])
                ->whereIn('answer2', [$project->answer2, '']);
            $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();
            $roles = ProjectRole::all();
            $types = ProjectType::all();
            $documents = DB::table('project_documents')->where('project_id', $projectId)->get();
            $dateFields = [];
            $datesEntered = [];

            foreach ($project->project_date as $date) {
                if ($date->date_value != '') {
                    $dateFormat = \DateTime::createFromFormat('Y-m-d', $date->date_value);
                    $formattedDate = $dateFormat->format('m/d/Y');
                } else {
                    $formattedDate = $date->date_value;
                }

                $datesEntered[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id, 'value' => $formattedDate];
            }

            foreach ($remedyDate as $date) {
                $dateFields[$date->id] = ['id' => $date->id, 'name' => $date->date_name, 'recurring' => $date->recurring, 'dates' => []];

                foreach ($datesEntered as $value) {
                    if ($value['remedy'] == $date->id) {
                        $dateFields[$date->id]['dates'] += [$value['id'] => ['id' => $value['id'], 'remedy' => $value['remedy'], 'value' => $value['value'], 'recurring' => $date->recurring]];
                    }
                }
            }



            $companyData = [];
            $companies = Company::pluck('company', 'id');
            $com = Company::get();
            $companyCont = CompanyContact::get();
            foreach ($com as $company) {
                foreach ($companyCont as $con) {
                    if ($con->id === $company->id) {
                        $companyData += [$company->id => ['id' => $company->id, 'company' => $company->company, 'type' => $con->contact_type]];
                    }
                }
            }
            $firstNames = UserDetails::pluck('first_name', 'id');
            $project = ProjectDetail::find($projectId);
            if (!is_null($project) && $project->user_id == Auth::id()) {
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
                        $projectContacts[$count]['contactAd'] = $companyContact->address;
                        $projectContacts[$count]['customers'][] = $companyContact;
                        $projectContacts[$count]['customer'][] = $companyContact->contacts;
                        $projectContacts[$count]['customer_id'] = $companyContact->contacts->id;
                        $count++;
                    }
                }
                $jobInfoSheet = JobInfo::where('project_id', $projectId)->first();
                $user = User::findOrFail($project->user_id);

                $contract = ProjectContract::where('project_id', $projectId)->first();
                if (!empty($contract)) {
                    $contractTotal = $contract->total_claim_amount;
                } else {
                    $contractTotal = '0';
                }
                $remedyNames = [];
                if (isset($_GET['edit'])) {
                    $liens = LienLawSlideChart::where('state_id', $project->state_id)
                        ->where('project_type', $project->project_type_id)->get();
                    $remedyNames = [];
                    foreach ($liens as $lien) {
                        $remedyNames[] = $lien->remedy;
                    }
                }
                return view('vine.project_dash', [
                    'vine_view' => 'member/project-dash',
                    'project_id' => $project->id,
                    'project' => $project,
                    'user' => $user,
                    'states' => $states,
                    'projectContacts' => $projectContacts,
                    'jobInfoSheet' => $jobInfoSheet,
                    'companies' => $companies,
                    'first_names' => $firstNames,
                    'contactInfo' => $companyData,
                    'contract' => $contract,
                    'projectDates' => $dateFields,
                    'documents' => $documents,
                    'roles' => $roles,
                    'types' => $types,
                    'remedyNames' => array_unique($remedyNames),
                    'liens' => $liens
                ]);
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            // return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    public function deleteJSON($project_id, $panel, $delete_id)
    {

        $json = ['result' => 'true'];

        $project = ProjectDetail::find($project_id);
        $project_id = $project->id; // for safety

        if ($panel == 'tasks') {
            ProjectTask::where('project_id', $project_id)->where('id', $delete_id)->delete();
        }

        return response()->json($json, 200);
    }

    public function saveFieldsJSON($project_id, $modal, $save_data)
    {

        //var_dump($project_id, $modal);

        $json = ['result' => 'true'];

        $project = ProjectDetail::find($project_id);
        $project_id = $project->id; // for safety
        $fields = [];

        foreach ($save_data as $field) {
            $name = $field['name'];
            $val = $field['value'];

            $fields[$name] = $val;
        }

        $item_id = isset($fields['item_id']) ? $fields['item_id'] : false;


        // TODO: Error checking!
        if ($modal == 'modal-send-claim') {

            $json['request'] = $fields;
        } elseif ($modal == 'modal-add-contact') {

            if (isset($_GET['custom_value'])) {
                // custom assign/unassign bulk add modal

                $values = $_GET['custom_value'];

                if (isset($values['assign'])) {
                    $assign = $values['assign'];

                    foreach ($assign as $contact_id => $val) {
                        $contactMap = ProjectIndustryContactMap::where('projectId', $project->id)->where('contactId', $contact_id);
                        $contactMap->delete();

                        $contactMap = new ProjectIndustryContactMap();
                        $contactMap->projectId = $project->id;
                        $contactMap->contactId = $contact_id;
                        $contactMap->save();
                    }
                }

                if (isset($values['unassign'])) {
                    $unassign = $values['unassign'];

                    foreach ($unassign as $contact_id => $val) {
                        $contactMap = ProjectIndustryContactMap::where('projectId', $project->id)->where('contactId', $contact_id);
                        $contactMap->delete();
                    }
                }
            } else {

                /*
                    Array
                    (
                        [project_id] => 272
                        [item_id] =>
                        [company_id] => 236
                        [customer_first] => newfirst1
                        [customer_last] => newlast1
                        [title] => title2
                        [phone] => 9548210952
                        [cell] => 9548210953
                        [email] => newemail@leaonard.com
                    )
                 */


                // where do these items update:
                //
                // $project->customer_contract->contactsAd
                // map_company_contacts:
                // -state, address, city, zip, phone, fax
                //
                // $project->customer_contract->contacts
                // company_contacts:
                // -title, first name, last name, cell, email
                //
                // $project->customer_contract->company
                // companies:
                // -company name, website

                //if(empty($fields['city']))              $json['errors'][] = ['Company Name field is required'];
                //if(empty($fields['state']))             $json['errors'][] = ['State field is required'];
                //if(empty($fields['zip']))               $json['errors'][] = ['Zip code field is required'];
                //if(empty($fields['address']))           $json['errors'][] = ['Address field is required'];
                //if(empty($fields['cphone']))            $json['errors'][] = ['Company phone field is required'];
                //if(empty($fields['title']))             $json['errors'][] = ['Title field is required'];
                if (empty($fields['customer_first'])) $json['errors'][] = ['First name field is required'];
                //if(empty($fields['customer_last']))     $json['errors'][] = ['Last name field is required'];
                //if(empty($fields['phone']))             $json['errors'][] = ['Customer phone field is required'];
                //if(empty($fields['cell']))              $json['errors'][] = ['Cell phone field is required'];
                //if(empty($fields['email']))             $json['errors'][] = ['Email field is required'];
                //if(empty($fields['company_name']))      $json['errors'][] = ['Company Name field is required'];
                //if(empty($fields['website']))           $json['errors'][] = ['Job City field is required'];

                if (empty($json['errors'])) {

                    if ($fields['company_id'] == "new") {

                        // create new company
                        $table = new Company();
                        $table->user_id = $project->user_id;
                        $table->company = $fields['company_name'];
                        $table->website = $fields['website'];
                        $table->address = $fields['address'];
                        $table->city = $fields['city'];
                        $table->state_id = $fields['state'];
                        $table->zip = $fields['zip'];
                        $table->phone = $fields['cphone'];
                        $table->fax = $fields['fax'];
                        $table->save();

                        $company_id = $table->id;
                    } else {
                        $company_id = $fields['company_id'];
                    }


                    // UPDATE: title, first name, last name, cell, email
                    $table = new Companycontact();
                    $table->user_id = $project->user_id;
                    $table->title = $fields['title'];
                    $table->first_name = $fields['customer_first'];
                    $table->last_name = $fields['customer_last'];
                    $table->phone = $fields['phone'];
                    $table->cell = $fields['cell'];
                    $table->email = $fields['email'];

                    if (isset($fields['role'])) {
                        $role = $fields['role'];

                        if ($role == "Other" && isset($fields['custom_role'])) {
                            $role = $fields['custom_role'];
                        }

                        $table->contact_type = $role;
                    }

                    $table->save();

                    $contact_id = $table->id;

                    // UPDATE: state, address, city, zip
                    $table = new MapCompanyContact();
                    //$table->city = $fields['city'];
                    //$table->state_id = $fields['state'];
                    //$table->zip = $fields['zip'];
                    //$table->address = $fields['address'];
                    //$table->phone = $fields['cphone'];
                    //$table->fax = $fields['fax'];
                    $table->company_id = $company_id;
                    $table->company_contact_id = $contact_id;
                    $table->save();

                    $map_contact_id = $table->id;

                    // assign new contact to project
                    $contactMap = new ProjectIndustryContactMap();
                    $contactMap->projectId = $project->id;
                    $contactMap->contactId = $map_contact_id;
                    $contactMap->save();


                    //$table->id
                    // UPDATE: company name, website
                    //                $table = Company::where('id', $project->customer_contract->company->id)->first();
                    //                $table->company = $fields['company_name'];
                    //                $table->website = $fields['website'];
                    //                $table->update();


                }
            }


            return response()->json($json, 200);
        } elseif ($modal == 'modal-edit-contact') {
            /*
            Array (
            [project_id] => 267
            [item_id] => 28
            [customer_first] => jen
            [customer_last] => lind
            [title] => CEO
            [phone] => 34245678878
            [cell] => 4325678908
            [email] => surety@bond.com )
             */


            // where do these items update:
            //
            // $project->customer_contract->contactsAd
            // map_company_contacts:
            // -state, address, city, zip, phone, fax
            //
            // $project->customer_contract->contacts
            // company_contacts:
            // -title, first name, last name, cell, email
            //
            // $project->customer_contract->company
            // companies:
            // -company name, website

            //if(empty($fields['city']))              $json['errors'][] = ['Company Name field is required'];
            //if(empty($fields['state']))             $json['errors'][] = ['State field is required'];
            //if(empty($fields['zip']))               $json['errors'][] = ['Zip code field is required'];
            //if(empty($fields['address']))           $json['errors'][] = ['Address field is required'];
            //if(empty($fields['cphone']))            $json['errors'][] = ['Company phone field is required'];
            //if(empty($fields['title']))             $json['errors'][] = ['Title field is required'];
            if (empty($fields['customer_first']))    $json['errors'][] = ['First name field is required'];
            //if(empty($fields['customer_last']))     $json['errors'][] = ['Last name field is required'];
            //if(empty($fields['phone']))             $json['errors'][] = ['Customer phone field is required'];
            //if(empty($fields['cell']))              $json['errors'][] = ['Cell phone field is required'];
            //if(empty($fields['email']))             $json['errors'][] = ['Email field is required'];
            //if(empty($fields['company_name']))      $json['errors'][] = ['Company Name field is required'];
            //if(empty($fields['website']))           $json['errors'][] = ['Job City field is required'];

            if (empty($json['errors'])) {
                // UPDATE: state, address, city, zip
                $table = MapCompanyContact::where('id', $item_id)->first();
                //                $table->city = $fields['city'];
                //                $table->state_id = $fields['state'];
                //                $table->zip = $fields['zip'];
                //                $table->address = $fields['address'];
                //                $table->phone = $fields['cphone'];
                //                $table->fax = $fields['fax'];
                //                $table->update();

                $contact_id = $table->company_contact_id;

                // UPDATE: title, first name, last name, cell, email
                $table = Companycontact::where('id', $contact_id)->first();
                $table->title = $fields['title'];
                $table->first_name = $fields['customer_first'];
                $table->last_name = $fields['customer_last'];
                $table->phone = $fields['phone'];
                $table->cell = $fields['cell'];
                $table->email = $fields['email'];
                $table->update();

                // UPDATE: company name, website
                //                $table = Company::where('id', $project->customer_contract->company->id)->first();
                //                $table->company = $fields['company_name'];
                //                $table->website = $fields['website'];
                //                $table->update();
            }


            return response()->json($json, 200);
        } elseif ($modal == 'modal-edit-job-dates') {
            /*
             * array(4) {
              ["project_id"]=>
              string(3) "267"
              [255]=>
              string(10) "01/22/2019"
              [256]=>
              string(10) "01/23/2019"
              [257]=>
              string(10) "01/29/2019"
             */


            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
            $tiers = TierTable::where('role_id', $project->role_id)
                ->where('customer_id', $project->customer_id)->firstOrFail();
            $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
                ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
                ->whereIn('answer1', [$project->answer1, ''])
                ->whereIn('answer2', [$project->answer2, '']);
            $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();

            $dateFields = [];
            $datesEntered = [];
            foreach ($project->project_date as $date) {
                if ($date->date_value != '') {
                    $dateFormat = \DateTime::createFromFormat('Y-m-d', $date->date_value);
                    $formattedDate = $dateFormat->format('m/d/Y');
                } else {
                    $formattedDate = $date->date_value;
                }
                $datesEntered[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id, 'value' => $formattedDate];
            }
            foreach ($remedyDate as $date) {
                $dateFields[$date->id] = ['id' => $date->id, 'name' => $date->date_name, 'recurring' => $date->recurring, 'dates' => []];
                foreach ($datesEntered as $key => $value) {
                    if ($value['remedy'] == $date->id) {
                        $dateFields[$date->id]['date'][] = ['id' => $value['id'], 'remedy' => $value['remedy'], 'value' => $value['value'], 'recurring' => $date->recurring];
                        $datesEntered[$key]['name'] = $date->date_name;
                    }
                }
            }

            $liens = LienLawSlideChart::where('state_id', $project->state_id)
                ->where('project_type', $project->project_type_id)->get();
            $remedyNames = [];
            foreach ($liens as $lien) {
                $remedyNames[] = $lien->remedy;
            }
            ///////////////////////////////////
            ///


            // will store our values to save after error checking
            $save = [];

            foreach ($fields as $key => $date) {

                // create new date
                if (preg_match("/new\[([0-9]+)\]/", $key, $matches) || is_numeric($key)) {

                    // if true, create a new record
                    if (isset($matches[1])) {
                        $remedy = $matches[1];
                    } else {
                        $remedy = false;
                    }

                    $dateFormat = \DateTime::createFromFormat('m/d/Y', $date);
                    if (!$dateFormat && !empty($date)) {

                        if (!$remedy) {
                            $name = $datesEntered[$key]['name'];
                        } else {
                            $name = $dateFields[$remedy]['name'];
                        }

                        $json['errors'][] = "Invalid date format for '{$name}'";
                    } else {
                        if (empty($date)) {
                            $date_formatted = "";
                        } else {
                            $date_formatted = $dateFormat->format('Y-m-d');
                        }

                        if (empty($date_formatted) && $remedy) continue;

                        $save[] = ['date_id' => $key, 'remedy' => $remedy, 'date' => $date_formatted];
                    }
                }
            }

            //print_r($save);exit;

            // no errors, so lets save
            if (empty($json['errors'])) {

                foreach ($save as $date) {

                    // if it doesnt exist, make a new record
                    if ($date['remedy']) {
                        $projectDate = new ProjectDates();
                        $projectDate->project_id = $project_id;
                        $projectDate->date_id = $date['remedy'];
                        $projectDate->date_value = $date['date'];
                        $projectDate->save();
                    } else {
                        $projectDate = ProjectDates::find($date['date_id']);
                        $projectDate->date_value = $date['date'];

                        if (empty($date['date'])) {
                            $projectDate->delete();
                        } else {
                            $projectDate->update();
                        }
                    }
                }
            }
        } elseif ($modal == 'modal-edit-job-task' || $modal == 'modal-add-job-task') {

            /*
             * array(6) {
              ["project_id"]=>
              string(3) "267"
              ["name"]=>
              string(24) "Prepare Waivers for Draw"
              ["due_date"]=>
              string(10) "05/31/2019"
              ["completed"]=>
              string(0) ""
              ["alert"]=>
              string(1) "1"
              ["comment"]=>
              string(18) "Another test task!"
            */

            if (empty($fields['name'])) $json['errors'][] = ['Task Name field is required'];
            if (empty($fields['due_date'])) $json['errors'][] = ['Due Date field is required'];
            if (!isset($fields['alert']) || empty($fields['alert']) && $fields['alert'] !== '0') $json['errors'][] = ['Email Alert field is required'];

            if (empty($json['errors'])) {

                // if theres existing item id make a new task
                if (empty($item_id)) {
                    $task = new ProjectTask(); //::findOrFail($item_id);
                    $task->project_id = $project_id;
                    $task->task_name = $fields['name'];
                    $dateFormat = \DateTime::createFromFormat('m/d/Y', $fields['due_date']);
                    $task->due_date = $dateFormat->format('Y-m-d');
                    if (!empty($fields['completed'])) {
                        $completeDate = \DateTime::createFromFormat('m/d/Y', $fields['completed']);
                        $task->complete_date = $completeDate->format('Y-m-d');
                    } else {
                        $task->complete_date = null;
                    }
                    $task->email_alert = $fields['alert'];
                    $task->comment = $fields['comment'];
                    $task->save();
                } else {
                    $task = ProjectTask::findOrFail($item_id);
                    $task->task_name = $fields['name'];
                    $dateFormat = \DateTime::createFromFormat('m/d/Y', $fields['due_date']);
                    $task->due_date = $dateFormat->format('Y-m-d');
                    if (!empty($fields['completed'])) {
                        $completeDate = \DateTime::createFromFormat('m/d/Y', $fields['completed']);
                        $task->complete_date = $completeDate->format('Y-m-d');
                    } else {
                        $task->complete_date = null;
                    }
                    $task->email_alert = $fields['alert'];
                    $task->comment = $fields['comment'];
                    $task->update();
                }
            }
        } elseif ($modal == 'modal-edit-job-contract') {
            /*
             * array(6) {
              ["project_id"]=>
              string(3) "267"
              ["base_amount"]=>
              string(8) "64988.00"
              ["additional_cost"]=>
              string(4) "0.00"
              ["revised_total"]=>
              string(8) "64988.00"
              ["payments_credits"]=>
              string(4) "0.00"
              ["unpaid_balance"]=>
              string(8) "64988.00"

                            'waiver_amount' => ['Waiver Amount', number_format((isset($contract) && $contract != '' && $contract->waiver != '')?$contract->waiver:'0', 2), 'financial'],
                            'rec_status' => ['Recieveable Status', $contract->receivable_status, 'dropdown', $rec_status_values],
                            'deadline_status' => ['Deadline Calculation Status', $contract->calculation_status, 'dropdown' , $calc_status_values],
             */

            if (!empty($fields['base_amount']) && preg_match("/[^0-9\.\-]/", $fields['base_amount'])) $json['errors'][] = ['Base Contract Amount contains an invalid value'];
            if (!empty($fields['additional_cost']) && preg_match("/[^0-9\.\-]/", $fields['additional_cost'])) $json['errors'][] = ['Additional Costs contains an invalid value'];
            if (!empty($fields['payments_credits']) && preg_match("/[^0-9\.\-]/", $fields['payments_credits'])) $json['errors'][] = ['Payments and Credits contains an invalid value'];
            if (!empty($fields['waiver_amount']) && preg_match("/[^0-9\.\-]/", $fields['waiver_amount'])) $json['errors'][] = ['Waiver amount contains an invalid value'];

            if (empty($json['errors'])) {

                $projectContract = ProjectContract::where('project_id', $project_id)->first();

                if (!$projectContract) {
                    $new_contract = true;
                    $projectContract = new ProjectContract();
                    $projectContract->project_id = $project->id;
                }

                $projectContract->base_amount = round($fields['base_amount'], 2);
                $projectContract->extra_amount = round($fields['additional_cost'], 2);
                $projectContract->credits = round($fields['payments_credits'], 2);
                $projectContract->waiver = round($fields['waiver_amount'], 2);
                $projectContract->receivable_status = $fields['rec_status'];
                $projectContract->calculation_status = $fields['deadline_status'];
                $projectContract->total_claim_amount = (($projectContract->base_amount + $projectContract->extra_amount) - $projectContract->credits);

                if (isset($new_contract)) {
                    $projectContract->save();
                } else {
                    $projectContract->update();
                }
            }
        } elseif ($modal == 'modal-edit-job-details') {
            /*
             * array(6) {
              ["job_id"]=>
              string(3) "267"
              ["job_name"]=>
              string(6) "garage"
              ["job_city"]=>
              string(3) "ftl"
              ["job_state"]=>
              string(2) "10"
              ["job_type"]=>
              string(7) "Private"
              ["your_role"]=>
              string(2) "14"
             */

            if (empty($fields['job_name'])) $json['errors'][] = ['Job Name field is required'];
            if (empty($fields['job_city'])) $json['errors'][] = ['Job City field is required'];

            if (empty($json['errors'])) {
                $project = ProjectDetail::where('id', $project_id)->first();
                $project->address = $fields['job_address'];
                $project->city = $fields['job_city'];
                $project->zip = $fields['job_zip'];
                $project->project_name = $fields['job_name'];
                //$project->state_id = $fields['job_state'];
                //$project->project_type_id = $fields['job_type'];
                //$project->role_id = $fields['your_role'];

                //if($fields['active'] == "1" || $fields['active'] == "0") $project->status = $fields['active'];

                $project->update();
            }
        } elseif ($modal == 'modal-assign-customer') {

            /*
             * array(16) {
              ["project_id"]=>
              string(3) "285"
              ["item_id"]=>
              string(0) ""
              ["customer"]=>
              string(3) "new"
              ["company_name"]=>
              string(11) "new company"
              ["address"]=>
              string(16) "new company addr"
              ["city"]=>
              string(4) "city"
              ["zip"]=>
              string(6) "234234"
              ["cphone"]=>
              string(12) "239840293840"
              ["fax"]=>
              string(12) "203948209348"
              ["website"]=>
              string(7) "website"
              ["customer_first"]=>
              string(13) "contact first"
              ["customer_last"]=>
              string(12) "contact last"
              ["title"]=>
              string(5) "title"
              ["phone"]=>
              string(10) "8932479384"
              ["cell"]=>
              string(12) "839475938475"
              ["email"]=>
              string(20) "email@newcompany.com"
            }
             */

            if ($fields['customer'] == "new") {

                // create new company
                $table = new Company();
                $table->user_id = $project->user_id;
                $table->company = $fields['company_name'];
                $table->website = $fields['website'];
                $table->address = $fields['address'];
                $table->city = $fields['city'];
                $table->state_id = $fields['state'];
                $table->zip = $fields['zip'];
                $table->phone = $fields['cphone'];
                $table->fax = $fields['fax'];
                $table->save();

                $company_id = $table->id;

                // create new company contact
                $table = new CompanyContact();
                $table->user_id = $project->user_id;
                $table->title = $fields['title'];
                $table->first_name = $fields['customer_first'];
                $table->last_name = $fields['customer_last'];
                $table->email = $fields['email'];
                $table->phone = $fields['phone'];
                $table->cell = $fields['cell'];
                $table->save();

                $company_contact_id = $table->id;

                // create map company contact
                $table = new MapCompanyContact();
                $table->company_id = $company_id;
                $table->company_contact_id = $company_contact_id;
                $table->user_id = $project->user_id;
                $table->address = $fields['address'];
                $table->city = $fields['city'];
                $table->state_id = $fields['state'];
                $table->zip = $fields['zip'];
                $table->phone = $fields['cphone'];
                $table->fax = $fields['fax'];
                $table->save();

                $customer_id = $table->id;
            } else {
                $customer_id = $fields['customer'];
            }

            $project = ProjectDetail::where('id', $project_id)->first();
            $project->customer_contract_id = $customer_id; // <-- map company contact id

            $project->update();

            return response()->json($json, 200);
        } elseif ($modal == 'modal-edit-customer') {
            /*
             * array(14) {
              ["project_id"]=>
              string(3) "267"
              ["company_name"]=>
              string(15) "Jenifer and Co."
              ["address"]=>
              string(14) "226 Raupp Blvd"
              ["city"]=>
              string(13) "buffalo grove"
              ["state"]=>
              string(2) "14"
              ["zip"]=>
              string(5) "60089"
              ["phone"]=>
              string(10) "8474363459"
              ["fax"]=>
              string(0) ""
              ["website"]=>
              string(10) "www.jl.com"
              ["customer_first"]=>
              string(7) "jenifer"
              ["customer_last"]=>
              string(9) "linderman"
              ["title"]=>
              string(3) "CEO"
              ["cell"]=>
              string(0) ""
              ["email"]=>
              string(26) "jeniferlinderman@gmail.com"
             */

            // where do these items update:
            //
            // $project->customer_contract->contactsAd
            // map_company_contacts:
            // -state, address, city, zip, phone, fax
            //
            // $project->customer_contract->contacts
            // company_contacts:
            // -title, first name, last name, cell, email
            //
            // $project->customer_contract->company
            // companies:
            // -company name, website

            //if(empty($fields['city']))              $json['errors'][] = ['Company Name field is required'];
            //if(empty($fields['state']))             $json['errors'][] = ['State field is required'];
            if (empty($fields['zip']))               $json['errors'][] = ['Zip code field is required'];
            //if(empty($fields['address']))           $json['errors'][] = ['Address field is required'];
            if (empty($fields['cphone']))            $json['errors'][] = ['Company phone field is required'];
            //if(empty($fields['title']))             $json['errors'][] = ['Title field is required'];
            //if(empty($fields['customer_first']))    $json['errors'][] = ['First name field is required'];
            //if(empty($fields['customer_last']))     $json['errors'][] = ['Last name field is required'];
            if (empty($fields['phone']))             $json['errors'][] = ['Customer phone field is required'];
            //if(empty($fields['cell']))              $json['errors'][] = ['Cell phone field is required'];
            //if(empty($fields['email']))             $json['errors'][] = ['Email field is required'];
            if (empty($fields['company_name']))      $json['errors'][] = ['Company Name field is required'];
            //if(empty($fields['website']))           $json['errors'][] = ['Job City field is required'];

            if (empty($json['errors'])) {
                // UPDATE: state, address, city, zip
                $table = MapCompanyContact::where('id', $project->customer_contract->contactsAd->id)->first();

                $table->city = $fields['city'];
                $table->state_id = $fields['state'];
                if (!empty($fields['zip'])) $table->zip = $fields['zip'];
                if (!empty($fields['address'])) $table->address = $fields['address'];
                if (!empty($fields['cphone'])) $table->phone = $fields['cphone'];
                if (!empty($fields['fax'])) $table->fax = $fields['fax'];
                $table->update();

                // UPDATE: title, first name, last name, cell, email
                $table = Companycontact::where('id', $project->customer_contract->contacts->id)->first();
                $table->title = $fields['title'];
                $table->first_name = $fields['customer_first'];
                $table->last_name = $fields['customer_last'];
                $table->phone = $fields['phone'];
                $table->cell = $fields['cell'];
                $table->email = $fields['email'];
                $table->update();

                // UPDATE: company name, website
                $table = Company::where('id', $project->customer_contract->company->id)->first();
                $table->company = $fields['company_name'];
                $table->website = $fields['website'];
                $table->update();
            }
        }

        return response()->json($json, 200);
    }


    public function getJSON(Request $request)
    {

        $projectId = $request->get('project_id');

        $project = ProjectDetail::find($projectId);
        $remedy = Remedy::where('state_id', $project->state_id)
            ->where('project_type_id', $project->project_type_id);
        $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
        $tiers = TierTable::where('role_id', $project->role_id)
            ->where('customer_id', $project->customer_id)->firstOrFail();
        $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
            ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
            ->whereIn('answer1', [$project->answer1, ''])
            ->whereIn('answer2', [$project->answer2, '']);
        $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
        $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();
        $roles = ProjectRole::all();
        $types = ProjectType::all();
        $dateFields = [];
        $datesEntered = [];
        foreach ($project->project_date as $date) {
            if ($date->date_value != '') {
                $dateFormat = \DateTime::createFromFormat('Y-m-d', $date->date_value);
                $formattedDate = $dateFormat->format('m/d/Y');
            } else {
                $formattedDate = $date->date_value;
            }
            $datesEntered[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id, 'value' => $formattedDate];
        }
        foreach ($remedyDate as $date) {
            $dateFields[$date->id] = ['id' => $date->id, 'name' => $date->date_name, 'recurring' => $date->recurring, 'dates' => []];
            foreach ($datesEntered as $value) {
                if ($value['remedy'] == $date->id) {
                    $dateFields[$date->id]['dates'] += [$value['id'] => ['id' => $value['id'], 'remedy' => $value['remedy'], 'value' => $value['value'], 'recurring' => $date->recurring]];
                }
            }
        }
        $companyData = [];
        $companies = Company::pluck('company', 'id');
        $com = Company::get();
        $companyCont = CompanyContact::get();
        foreach ($com as $company) {
            foreach ($companyCont as $con) {
                if ($con->id === $company->id) {
                    $companyData += [$company->id => ['id' => $company->id, 'company' => $company->company, 'type' => $con->contact_type]];
                }
            }
        }
        $firstNames = UserDetails::pluck('first_name', 'id');
        $project = ProjectDetail::find($projectId);
        if (!is_null($project) && $project->user_id == Auth::id()) {
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
                    $projectContacts[$count]['contactAd'] = $companyContact->address;
                    $projectContacts[$count]['customers'][] = $companyContact;
                    $projectContacts[$count]['customer'][] = $companyContact->contacts;
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
            $contract = ProjectContract::where('project_id', $projectId)->first();
            if (!empty($contract)) {
                $contractTotal = $contract->total_claim_amount;
            } else {
                $contractTotal = '0';
            }
            $remedyNames = [];

            $dateFields = [];
            $datesEntered = [];
            foreach ($project->project_date as $date) {
                if ($date->date_value != '') {
                    $dateFormat = \DateTime::createFromFormat('Y-m-d', $date->date_value);
                    $formattedDate = $dateFormat->format('m/d/Y');
                } else {
                    $formattedDate = $date->date_value;
                }
                $datesEntered[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id, 'value' => $formattedDate];
            }
            foreach ($remedyDate as $date) {
                $dateFields[$date->id] = ['id' => $date->id, 'name' => $date->date_name, 'recurring' => $date->recurring, 'dates' => []];
                foreach ($datesEntered as $value) {
                    if ($value['remedy'] == $date->id) {
                        $dateFields[$date->id]['date'][] = ['id' => $value['id'], 'remedy' => $value['remedy'], 'value' => $value['value'], 'recurring' => $date->recurring];
                    }
                }
            }

            //var_dump($dateFields); exit;

            $liens = LienLawSlideChart::where('state_id', $project->state_id)
                ->where('project_type', $project->project_type_id)->get();
            $remedyNames = [];
            foreach ($liens as $lien) {
                $remedyNames[] = $lien->remedy;
            }




            $baseAmount = (isset($contract->base_amount) && !empty($contract->base_amount) ? $contract->base_amount : 0);
            $extraCharges = (isset($contract->extra_amount) && !empty($contract->extra_amount) ? $contract->extra_amount : 0);
            $credits = (isset($contract->credits) && !empty($contract->credits) ? $contract->credits : 0);
            $revised = $baseAmount + $extraCharges;
            $totalClaimAmount = $revised - $credits;
            // contractCreated is used to set a value on data-contract, this watches for contracts not already created
            $contractCreated = (isset($contract) && !empty($contract) ? 'contract-exists' : 'no-contract');

            $project_info = [
                'vine_view' => 'member/project-dash',
                'project_id' => $project->id,
                'project' => $project,
                'user' => $user,
                'states' => $states,
                'projectContacts' => $projectContacts,
                'jobInfoSheet' => $jobInfoSheet,
                'companies' => $companies,
                'first_names' => $firstNames,
                'contactInfo' => $companyData,
                'contract' => $contract,
                'projectDates' => $dateFields,
                'roles' => $roles,
                'types' => $types,
                'remedyNames' => array_unique($remedyNames),
                'liens' => $liens
            ];


            // todo: move these functions
            function getContactInfo($item_id)
            {
                $allContacts = Company::pluck('id');

                $industryContracts = MapCompanyContact::whereIn('company_id', $allContacts)
                    ->whereHas('getContacts', function ($query) {
                        $query->where('type', '1');
                    })->with(['company', 'contacts'])->where('id', $item_id)->get();

                $contactsF = (isset($project) && $project != '' ? ($project->industryContacts ? $project->industryContacts->pluck('contactId') : '') : '');


                return $industryContracts[0];
            }

            function getStateName($state_id)
            {

                $states = State::all();

                $st = "";
                foreach ($states as $state) {
                    if ($state->id === $state_id) {
                        $st = $state_id == $state->id ? $state->name : '';
                    }
                }

                return $st;
            }


            // lets make a json object to send to the modals for which items can be edited, and their placeholder info
            $json = [];

            if (isset($_GET['delete_id'])) {
                $panel = $_GET['panel'];
                $delete = $_GET['delete_id'];
                return $this->deleteJSON($project->id, $panel, $delete);
            }

            if (isset($_GET['modal'])) {
                $modal = $_GET['modal'];

                if (isset($_GET['save'])) {
                    $save = $_GET['save'];
                    return $this->saveFieldsJSON($project->id, $modal, $save);
                }

                if ($modal == 'modal-add-contact') {

                    $company_values = [];
                    $companies = Company::where('user_id', $project->user_id)->get();

                    $state_dropdown_values = [];
                    foreach ($states as $state) {
                        $state_dropdown_values[$state->id] = $state->name;
                    }

                    foreach ($companies as $company) {

                        $company_values[$company->id] = $company->company;
                    }

                    $project_roles = [
                        'Architect',
                        'Bonding Company',
                        'Engineer',
                        'General Contractor',
                        'Lender',
                        'Owner',
                        'Sub-Contractor',
                        'Title Company',
                        'Other'
                    ];

                    $role_values = $this->mapDropdownArray($project_roles);

                    $role_values['Other'] = [
                        "name" => "Other",
                        "values" => [
                            'custom_role' => ['Custom', '']
                        ]
                    ];

                    $company_values['new'] = [
                        "name" => "-- Add New Company --",
                        "values" => [
                            'role' => ['Role', '', 'dropdown', $role_values],
                            'company_name' => ['Company Name', '', 'longtext'],
                            'address' => ['Address', '', 'longtext'],
                            'city' => ['City', ''],
                            'state' => ['State', '', 'dropdown', $state_dropdown_values],
                            'zip' => ['Zip Code', ''],
                            'cphone' => ['Phone', ''],
                            'fax' => ['Fax', ''],
                            'website' => ['Website', '', 'longtext'],
                            'sep' => ['', '', 'sep'],
                        ]
                    ];

                    $json['result'] = true;
                    $json['title'] = "Add New Contact";
                    $json['fields'] = [
                        'company_id' => ['Company', 'new', 'dropdown', $company_values],
                        'customer_first' => ['Contact First Name', ''],
                        'customer_last' => ['Contact Last Name', ''],
                        'title' => ['Title', ''],
                        'phone' => ['Phone', ''],
                        'cell' => ['Cell', ''],
                        'email' => ['Email', '']
                    ];


                    ///////////
                    ///
                    /// get contacts
                    ///
                    ///////////

                    $contacts_assigned = [];
                    $contacts_unassigned = [];

                    $allContacts = Company::pluck('id');

                    $industryContracts = MapCompanyContact::whereIn('company_id', $allContacts)
                        ->whereHas('getContacts', function ($query) {
                            $query->where('type', '1');
                        })->with(['company', 'contacts'])->get();

                    $contactsF = (isset($project) && $project != '' ? ($project->industryContacts ? $project->industryContacts->pluck('contactId') : '') : '');


                    foreach ($industryContracts as $industryContract) {

                        $is_assigned = isset($contactsF) && $contactsF != '' && count($contactsF) > 0 ? (in_array($industryContract->id, $contactsF->toArray()) ? true : false) : false;

                        // if we have no query, we are just listing the linked contacts on page load/refresh
                        // if (!$is_assigned) continue;

                        $contact = [
                            'id' => $industryContract->id,
                            'name' => $industryContract->contacts->first_name . ' ' . $industryContract->contacts->last_name . ' - ' . $industryContract->contacts->contact_type,
                            'role' => $industryContract->contacts->contact_type,
                            'company' => $industryContract->company->company,
                            'address' => $industryContract->company->address,
                            'phone' => $industryContract->contacts->phone,
                            'assigned' => $is_assigned
                        ];

                        if ($is_assigned) {
                            $contacts_assigned[] = $contact;
                        } else {
                            $contacts_unassigned[] = $contact;
                        }
                    }

                    // right column
                    $json['col_2'] = $contacts_assigned;

                    // left column
                    $json['col_1'] = $contacts_unassigned;

                    // filter dropdown
                    $json['roles'] = $project_roles;
                } elseif ($modal == 'modal-edit-contact') {

                    $state_dropdown_values = [];
                    foreach ($states as $state) {
                        $state_dropdown_values[$state->id] = $state->name;
                    }

                    $item_id = $_GET['item_id'];

                    $industryContract = getContactInfo($item_id);



                    $map_contact = MapCompanyContact::where('id', $item_id)->first();
                    $company_contact_id = $map_contact->company_contact_id;
                    $company_contact = Companycontact::where('id', $company_contact_id)->first();
                    $company = Company::where('id', $map_contact->company_id)->first();

                    //////

                    $json['result'] = true;
                    $json['title'] = "Edit Contact Information";
                    $json['fields'] = [
                        'company_name' => ['Company', $company->company, 'label'],
                        'role' => ['Role', $company_contact->contact_type, 'label'],
                        'address' => ['Address', $company->address, 'label'],
                        'city' => ['City', $company->city, 'label'],
                        'state' => ['State', getStateName($company->state_id), 'label'],
                        'zip' => ['Zip Code', $company->zip, 'label'],
                        'cphone' => ['Phone', $company->phone, 'label'],
                        'fax' => ['Fax', $company->fax, 'label'],
                        'website' => ['Website', $company->website, 'label'],
                        'sep' => ['', '', 'sep'],
                        'customer_first' => ['Contact First Name', $company_contact->first_name],
                        'customer_last' => ['Contact Last Name', $company_contact->last_name],
                        'title' => ['Title', $company_contact->title],
                        'phone' => ['Phone', $company_contact->phone],
                        'cell' => ['Cell', $company_contact->cell],
                        'email' => ['Email', $company_contact->email]
                    ];
                } elseif ($modal == 'modal-view-contact') {


                    $item_id = $_GET['item_id'];

                    $industryContract = getContactInfo($item_id);

                    $map_contact = MapCompanyContact::where('id', $item_id)->first();
                    $company_contact_id = $map_contact->company_contact_id;
                    $company_contact = Companycontact::where('id', $company_contact_id)->first();
                    $company = Company::where('id', $map_contact->company_id)->first();

                    //////
                    ///
                    //////

                    $json['result'] = true;
                    $json['title'] = "View Contact Information ";
                    $json['fields'] = [
                        'company_name' => ['Company', $company->company, 'label'],
                        'role' => ['Role', $company_contact->contact_type, 'label'],
                        'address' => ['Address', $company->address, 'label'],
                        'city' => ['City', $company->city, 'label'],
                        'state' => ['State', getStateName($company->state_id), 'label'],
                        'zip' => ['Zip Code', $company->zip, 'label'],
                        'cphone' => ['Phone', $company->phone, 'label'],
                        'fax' => ['Fax', $company->fax, 'label'],
                        'website' => ['Website', $company->website, 'label'],
                        'sep' => ['', '', 'sep'],
                        'customer_first' => ['Contact First Name', $company_contact->first_name, 'label'],
                        'customer_last' => ['Contact Last Name', $company_contact->last_name, 'label'],
                        'title' => ['Title', $company_contact->title, 'label'],
                        'phone' => ['Phone', $company_contact->phone, 'label'],
                        'cell' => ['Cell', $company_contact->cell, 'label'],
                        'email' => ['Email', $company_contact->email, 'label']
                    ];
                } elseif ($modal == 'modal-edit-job-details') {

                    $state_id = $project ? $project->state_id : '';
                    $st = "";
                    foreach ($states as $state) {
                        if ($state->id === $state_id) {
                            $st = $state_id == $state->id ? $state->name : '';
                        }
                    }

                    $role = "";
                    foreach ($roles as $rol) {
                        if ($rol->id == $project->role_id) {
                            $role = $rol->project_roles;
                        }
                    }

                    $type = $project ? $project->project_type->project_type : '';

                    $json['result'] = true;
                    $json['title'] = "Edit Job Details";
                    $json['fields'] = [
                        'job_name' => ['Job Name', $project ? $project->project_name : '', 'longtext'],
                        'job_city' => ['Job City', $project ? $project->city : ''],
                        'job_state' => ['Job State', $st, 'label'],
                        'job_address' => ['Job Address', $project->address, 'longtext'],
                        'job_zip' => ['Job Zip', $project->zip],
                        'sep' => ['', '', 'sep'],
                        'job_type' => ['Job Type', $type, 'label'],
                        'your_role' => ['Your Role', $role, 'label'],
                        //'active' => ['Status', $project ? $project->status : '', 'radio', ['1' => 'Active', '0' => 'Deactivated']],
                    ];
                } elseif ($modal == 'modal-assign-customer') {

                    /*
                                 {
                              "id": 45,
                              "user_id": 289,
                              "contact_type": null,
                              "type": "0",
                              "title": "CEO",
                              "title_other": null,
                              "first_name": "Funk1111",
                              "last_name": "Gunk22222",
                              "email": "1970@2.COM",
                              "phone": "2222222222",
                              "cell": "2223334444",
                              "created_at": "2018-07-03 18:34:30",
                              "updated_at": "2018-10-14 22:27:22"
                            },
                         */

                    $customer_values = [];
                    $contacts = MapCompanyContact::get();

                    foreach ($contacts as $contact) {

                        $company_contact_id = $contact->company_contact_id;
                        $company_contact = CompanyContact::where('id', $company_contact_id)->first();

                        if ($company_contact) {

                            $company_name = "";

                            $company = Company::where('id', $contact->company_id)->first();
                            if (isset($company->company)) {
                                $company_name = $company->company;
                            }

                            //$customer_values[] = $company_contact_id;
                            $customer_values[$contact->id] = $company_name . ' - ' . $company_contact->first_name . ' ' . $company_contact->last_name . ' (' . $company_contact->title . ')';
                        }
                    }

                    $state_dropdown_values = [];
                    foreach ($states as $state) {
                        $state_dropdown_values[$state->id] = $state->name;
                    }

                    $customer_values['new'] = [
                        "name" => "-- Add New Customer --",
                        "values" => [
                            'company_name' => ['Company Name', '', 'longtext'],
                            'address' => ['Address', '', 'longtext'],
                            'city' => ['City', ''],
                            'state' => ['State', '', 'dropdown', $state_dropdown_values],
                            'zip' => ['Zip Code', ''],
                            'cphone' => ['Phone', ''],
                            'fax' => ['Fax', ''],
                            'website' => ['Website', '', 'longtext'],
                            'customer_first' => ['Contact First Name', ''],
                            'customer_last' => ['Contact Last Name', ''],
                            'title' => ['Title', ''],
                            'phone' => ['Phone', ''],
                            'cell' => ['Cell', ''],
                            'email' => ['Email', '']
                        ]
                    ];

                    $json['result'] = true;
                    $json['title'] = "Assign Customer to Project";
                    $json['fields'] = [
                        'customer' => ['Customer', 'new', 'dropdown', $customer_values]
                    ];
                } elseif ($modal == 'modal-edit-customer') {

                    $state_dropdown_values = [];
                    foreach ($states as $state) {
                        $state_dropdown_values[$state->id] = $state->name;
                    }


                    $json['result'] = true;
                    $json['title'] = "Edit Customer Information";
                    $json['fields'] = [
                        'company_name' => ['Company', $project->customer_contract ? $project->customer_contract->company->company : '', 'longtext'],
                        'address' => ['Address', $project->customer_contract ? $project->customer_contract->contactsAd->address : '', 'longtext'],
                        'city' => ['City', $project->customer_contract ? $project->customer_contract->contactsAd->city : ''],
                        'state' => ['State', $project->customer_contract ? $project->customer_contract->contactsAd->state_id : '', 'dropdown', $state_dropdown_values],
                        'zip' => ['Zip Code', $project->customer_contract ? $project->customer_contract->contactsAd->zip : ''],
                        'cphone' => ['Phone', $project->customer_contract ? $project->customer_contract->contactsAd->phone : ''],
                        'fax' => ['Fax', $project->customer_contract ? $project->customer_contract->contactsAd->fax : ''],
                        'website' => ['Website', $project->customer_contract ? $project->customer_contract->company->website : '', 'longtext'],
                        'sep' => ['', '', 'sep'],
                        'customer_first' => ['Contact First Name', $project->customer_contract != '' ? $project->customer_contract->contacts->first_name : ''],
                        'customer_last' => ['Contact Last Name', $project->customer_contract != '' ? $project->customer_contract->contacts->last_name : ''],
                        'title' => ['Title', $project->customer_contract != '' ? $project->customer_contract->contacts->title : ''],
                        'phone' => ['Phone', $project->customer_contract != '' ? $project->customer_contract->contacts->phone : ''],
                        'cell' => ['Cell', $project->customer_contract != '' ? $project->customer_contract->contacts->cell : ''],
                        'email' => ['Email', $project->customer_contract != '' ? $project->customer_contract->contacts->email : '']
                    ];
                } elseif ($modal == 'modal-edit-job-contract') {

                    $baseAmount = (isset($contract->base_amount) && !empty($contract->base_amount) ? $contract->base_amount : 0);
                    $extraCharges = (isset($contract->extra_amount) && !empty($contract->extra_amount) ? $contract->extra_amount : 0);
                    $credits = (isset($contract->credits) && !empty($contract->credits) ? $contract->credits : 0);
                    $revised = $baseAmount + $extraCharges;
                    $totalClaimAmount = $revised - $credits;
                    // contractCreated is used to set a value on data-contract, this watches for contracts not already created
                    $contractCreated = (isset($contract) && !empty($contract) ? 'contract-exists' : 'no-contract');

                    $rec_status_values = ['Preliminary Notice', 'Lien', 'Bond', 'Collection', 'Litigation', 'Bankruptcy', 'Collected', 'Paid', 'Written Off', 'Settled'];
                    $calc_status_values = ['1' => 'Complete', '0' => 'In Process'];

                    $rec_status_values = $this->mapDropdownArray($rec_status_values);

                    $json['result'] = true;
                    $json['title'] = "Edit Job Contract Information";
                    $json['fields'] = [
                        'base_amount' => ['Base Contract Amount', number_format(
                            isset($baseAmount) && !empty($baseAmount) ? $baseAmount : '0',
                            2,
                            null,
                            ''
                        ), 'financial'],
                        'additional_cost' => ['Additional Costs', number_format(
                            isset($extraCharges) && !empty($extraCharges) ? $extraCharges : '0',
                            2,
                            null,
                            ''
                        ), 'financial'],
                        //'revised_total' => ['Revised Contract Total', number_format(isset($revised) && !empty($revised) ? $revised : '0',
                        //                                                            2, null, ''), 'financial'],
                        'payments_credits' => ['Payments and Credits', number_format(
                            isset($credits) && !empty($credits) ? $credits : '0',
                            2,
                            null,
                            ''
                        ), 'financial'],
                        //'unpaid_balance' => ['Unpaid Balance', number_format(isset($totalClaimAmount) && !empty($totalClaimAmount) ? $totalClaimAmount : '0',
                        //                                                    2, null, ''), 'financial']
                        'waiver_amount' => ['Waiver Amount', number_format((isset($contract) && $contract != '' && $contract->waiver != '') ? $contract->waiver : '0', 2), 'financial'],
                        'rec_status' => ['Recieveable Status', $contract->receivable_status, 'dropdown', $rec_status_values],
                        'deadline_status' => ['Deadline Calculation Status', $contract->calculation_status, 'dropdown', $calc_status_values],
                    ];
                } elseif ($modal == 'modal-edit-job-dates') {
                    $json['result'] = true;
                    $json['title'] = "Edit Job Date Information";
                    $json['fields'] = [];

                    foreach ($dateFields as $key => $date) {

                        if (!isset($date['date'])) {
                            $id = "new[{$key}]";
                            $val = "";
                        } else {
                            $id = $date['date'][0]['id'];
                            $val = $date['date'][0]['value'];
                        }

                        $json['fields'][$id] = [$date['name'], $val, 'datepicker'];
                    }
                } elseif ($modal == 'modal-add-job-task') {

                    $json['result'] = true;
                    $json['title'] = "Add New Job Task";

                    $json['fields'] = [
                        'name' => ['Task Name', '', 'dropdown', $this->getMappedactions()],
                        'due_date' => ['Due Date', '', 'datepicker'],
                        'completed' => ['Completed Date', '', 'datepicker'],
                        'alert' => ['Email Alert', '', 'radio', ['1' => 'Yes', '0' => 'No']],
                        'comment' => ['Comments', '', 'textarea'],
                    ];
                } elseif ($modal == 'modal-edit-job-task') {

                    $json['result'] = true;
                    $json['title'] = "Edit Job Task";

                    $task_id = $_GET['item_id'];
                    $tasks = ProjectTask::where('project_id', $project->id)->where('id', $task_id)->get();
                    $task = $tasks[0];

                    $json['fields'] = [
                        'name' => ['Task Name', $task->task_name, 'dropdown', $this->getMappedactions()],
                        'due_date' => ['Due Date', \DateTime::createFromFormat('Y-m-d', $task->due_date)->format('m/d/Y'), 'datepicker'],
                        'completed' => ['Completed Date', $task->complete_date != '' ? \DateTime::createFromFormat('Y-m-d', $task->complete_date)->format('m/d/Y') : '', 'datepicker'],
                        'alert' => ['Email Alert', $task->email_alert, 'radio', ['1' => 'Yes', '0' => 'No']],
                        'comment' => ['Comments', $task->comment, 'textarea'],
                    ];
                }

                // LOADING INFORMATION INTO TABS ON PAGE
            } elseif ($_GET['panel']) {

                $panel = $_GET['panel'];
                $query = isset($_GET['query']) ? $_GET['query'] : false;
                $query = empty($query) ? false : $query;

                // CONTACTS PANEL JSON
                if ($panel == 'contacts') {

                    // for search results only
                    $limit = 5;

                    $json['result'] = true;
                    $json['fields'] = [];
                    $json['contacts'] = [];
                    $json['limit'] = $limit;

                    $assign = isset($_GET['assign']) ? $_GET['assign'] : false;
                    $unassign = isset($_GET['unassign']) ? $_GET['unassign'] : false;
                    if ($assign) {

                        // todo: verify contact is valid for this company

                        $contactMap = new ProjectIndustryContactMap();
                        $contactMap->projectId = $project->id;
                        $contactMap->contactId = $assign;
                        $contactMap->save();
                    } elseif ($unassign) {

                        $contactMap = ProjectIndustryContactMap::where('projectId', $project->id)->where('contactId', $unassign);
                        $contactMap->delete();
                    } else {
                        $allContacts = Company::pluck('id');

                        $industryContracts = MapCompanyContact::whereIn('company_id', $allContacts)
                            ->whereHas('getContacts', function ($query) {
                                $query->where('type', '1');
                            })->with(['company', 'contacts'])->get();

                        $contactsF = (isset($project) && $project != '' ? ($project->industryContacts ? $project->industryContacts->pluck('contactId') : '') : '');


                        foreach ($industryContracts as $industryContract) {

                            $is_assigned = isset($contactsF) && $contactsF != '' && count($contactsF) > 0 ? (in_array($industryContract->id, $contactsF->toArray()) ? true : false) : false;

                            // if we have no query, we are just listing the linked contacts on page load/refresh
                            if (!$is_assigned && !$query) continue;

                            $contact = [
                                'id' => $industryContract->id,
                                'name' => $industryContract->contacts->first_name . ' ' . $industryContract->contacts->last_name,
                                'role' => $industryContract->contacts->contact_type,
                                'company' => $industryContract->company->company,
                                'address' => $industryContract->company->address,
                                'phone' => $industryContract->contacts->phone,
                                'assigned' => $is_assigned
                            ];

                            // todo: convert this to SQL instead of processing all results
                            // check if we have a query, and search all fields for the query, filter out if non matching
                            if ($query) {

                                $keep = false;

                                // loop our assoc array to filter search results
                                foreach ($contact as $key => $val) {

                                    if (count($json['contacts']) == $limit) break;

                                    if (stristr($val, $query)) {
                                        $keep = true;
                                    }

                                    // might change this, but for now, if we have a query, and the contact is already assigned, do not display them in the results
                                    if ($contact['assigned']) {
                                        $keep = false;
                                    }
                                }

                                if (!$keep) continue;
                            }

                            $json['contacts'][] = $contact;
                        }
                    }
                } // DASHBOARD HEADER JSON
                elseif ($panel == 'header') {

                    $json['result'] = true;

                    $jobInfoSheet = JobInfo::where('project_id', $project->id)->first();
                    $user = User::findOrFail($project->user_id);

                    if (isset($_GET['save'])) {
                        $save = $_GET['save'];
                        return $this->saveJobInfo($request, $project->id);
                    }

                    if (isset($_GET['upload'])) {
                        return $this->uploadFileJSON($request, $project->id, $jobInfoSheet);
                    }

                    $customer_mapped = (isset($project->customer_contract) && isset($project->customer_contract->company)) ? true : false;
                    $json['info_sheet_active'] = !$customer_mapped || (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2') ? false : true;

                    $city = $project->customer_contract ? $project->customer_contract->contactsAd->city : '';
                    $stateId = $project->customer_contract ? $project->customer_contract->contactsAd->state_id : '';
                    $state = '';

                    $citystate = $city;
                    if (!empty($city)) $citystate .= ", ";
                    $citystate .= getStateName($stateId);

                    $json['header'] = [
                        'company_name' => $project->customer_contract ? $project->customer_contract->company->company : '',
                        'address' => $project->customer_contract ? $project->customer_contract->contactsAd->address : '',
                        'citystate' => $citystate,
                        'cphone' => $project->customer_contract ? $project->customer_contract->contactsAd->phone : '',
                        'phone' => $project->customer_contract != '' ? $project->customer_contract->contacts->phone : '',
                        'name' => ($project->customer_contract != '' ? $project->customer_contract->contacts->first_name : '') . ' ' . ($project->customer_contract != '' ? $project->customer_contract->contacts->last_name : ''),
                        'project_title' => $project->project_name
                    ];
                } // DATES PANEL JSON
                elseif ($panel == 'dates' || $panel == 'deadlines') {

                    $json['result'] = true;

                    $role_id = ProjectDetail::where('id', $project->id);
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
                        $daysRemain[$key]['dates'] = ($years * 365) + ($months * 30) + ($days * 1);
                        $preliminaryDeadline = ProjectDates::select('date_value')
                            ->where('project_id', $project->id)
                            ->where('date_id', $remedyDateId)->first();
                        if ($preliminaryDeadline != null && $preliminaryDeadline->date_value != '') {
                            //return redirect()->back()->with('try-error', ' Fill-up the project dates section to get deadline');
                            $dateNew = date_create($preliminaryDeadline->date_value);
                            date_add($dateNew, date_interval_create_from_date_string($years . " years " . $months . " months " . $days . " days"));
                            //$prelim = new \DateTime($dateNew->format('m-d-Y'));
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
                        //                    if ($preliminaryDeadline == null) {
                        //                        return redirect()->back()->with('date-error', 'Please give some valid date in project date section ');
                        //                    }
                        //                    $daysRemain[$key]['deadline'] = $preliminaryDeadline->date_value;
                        //                    $daysRemain[$key]['preliminaryDates'] = date('Y-m-d', strtotime($daysRemain[$key]['deadline'] . '+' . $daysRemain[$key]['dates'] . ' days'));
                        $remedyNames[$value->getRemedy->id] = $value->getRemedy->remedy;
                    }

                    if (count($deadlines) > 0) {
                        foreach ($deadlines as $key => $deadline) {
                            if (strlen($daysRemain[$key]['preliminaryDates']) > 5) {
                                $today = date("Y-m-d");
                                $today = new \DateTime($today);
                                $prelimDead = $daysRemain[$key]['preliminaryDates'];
                                $formatPrelim = new \DateTime($prelimDead);
                                $daysUntilDeadline = date_diff($formatPrelim, $today);
                                $daysUntilDeadline = $daysUntilDeadline->format("%a");
                                $late = date_diff($formatPrelim, $today);
                                $late = $late->format("%R");
                            } else {
                                $daysUntilDeadline = 'N/A';
                                $late = 0;
                            }

                            $json['deadlines'][] = [
                                'name' => $deadline->getRemedy->remedy,
                                'desc' => $deadline->short_description,
                                'date' => $daysRemain[$key]['preliminaryDates'],
                                'days' => $daysUntilDeadline
                            ];
                        }
                    }

                    foreach ($dateFields as $date) {
                        $json['dates'][] = $date;
                    }
                } // TASKS PANEL JSON
                elseif ($panel == 'tasks') {

                    $json['result'] = true;
                    $json['tasks'] = [];
                    $tasks = ProjectTask::where('project_id', $project->id)->get();

                    foreach ($tasks as $key => $task) {
                        $json['tasks'][$key] = [
                            'id' => $task->id,
                            'name' => $task->task_name,
                            'comment' => $task->comment,
                            'due_date' => \DateTime::createFromFormat('Y-m-d', $task->due_date)->format('m/d/Y'),
                            'completed' => $task->complete_date != '' ? \DateTime::createFromFormat('Y-m-d', $task->complete_date)->format('m/d/Y') : '',
                            'alert' => $task->email_alert
                        ];
                    }
                } // CONTRACTS PANEL JSON
                elseif ($panel == 'contract') {
                    $contract = $project->project_contract;

                    $json['result'] = true;
                    $json['contracts'] = [
                        ['name' => 'Base Contract Amount', 'value' => '$' . number_format(isset($baseAmount) && !empty($baseAmount) ? $baseAmount : '0', 2)],
                        ['name' => 'Additional Costs', 'value' => '$' . number_format(isset($extraCharges) && !empty($extraCharges) ? $extraCharges : '0', 2)],
                        ['name' => 'Revised Contract Total', 'value' => '$' . number_format(isset($revised) && !empty($revised) ? $revised : '0', 2)],
                        ['name' => 'Payments and Credits', 'value' => '$' . number_format(isset($credits) && !empty($credits) ? $credits : '0', 2)],
                        ['name' => 'Unpaid Balance', 'value' => '$' . number_format(isset($totalClaimAmount) && !empty($totalClaimAmount) ? $totalClaimAmount : '0', 2)],
                        ['name' => 'Waiver Amount', 'value' => '$' . number_format((isset($contract) && $contract != '' && $contract->waiver != '') ? $contract->waiver : '0', 2)],
                        ['name' => 'Recieveable Status', 'value' => $contract->receivable_status],
                        ['name' => 'Deadline Calculation Status', 'value' => ($contract->calculation_status == "") ? '' : ($contract->calculation_status == 1 ? 'Complete' : 'In Process')],
                    ];
                } // CONTRACTS OVERVIEW PANEL JSON
                elseif ($panel == "contract-overview") {
                    $json['result'] = true;
                    $json['contracts'] = [
                        ['name' => 'Total Balance', 'value' => '$' . number_format(isset($revised) && !empty($revised) ? $revised : '0', 2)],
                        ['name' => 'Unpaid Balance', 'value' => '$' . number_format(isset($totalClaimAmount) && !empty($totalClaimAmount) ? $totalClaimAmount : '0', 2)]
                    ];
                } // SUMMARY PANEL JSON
                elseif ($panel == "summary") {

                    // where do these items update:
                    //
                    // $project->customer_contract->contactsAd
                    // map_company_contacts:
                    // -state, address, city, zip, phone, fax
                    //
                    // $project->customer_contract->contacts
                    // company_contacts:
                    // -title, first name, last name, cell, email
                    //
                    // $project->customer_contract->company
                    // companies:
                    // -company name, website

                    /*
                         *             // UPDATE: state, address, city, zip
                                        $table = MapCompanyContact::where('id', $project->customer_contract->contactsAd->id)->first();
                                        $table->city = $fields['city'];
                                        $table->state_id = $fields['state'];
                                        $table->zip = $fields['zip'];
                                        $table->address = $fields['address'];
                                        $table->phone = $fields['phone'];
                                        $table->fax = $fields['fax'];
                                        $table->update();

                                        // UPDATE: title, first name, last name, cell, email
                                        $table = Companycontact::where('id', $project->customer_contract->contacts->id)->first();
                                        $table->title = $fields['title'];
                                        $table->first_name = $fields['customer_first'];
                                        $table->last_name = $fields['customer_last'];
                                        $table->cell = $fields['cell'];
                                        $table->email = $fields['email'];
                                        $table->update();

                                        // UPDATE: company name, website
                                        $table = Company::where('id', $project->customer_contract->company->id)->first();
                                        $table->company = $fields['company_name'];
                                        $table->website = $fields['website'];
                                        $table->update();
                         */




                    $address = $project->customer_contract ? $project->customer_contract->contactsAd->address : '';

                    $city = $project->customer_contract ? $project->customer_contract->contactsAd->city : '';
                    $state = "";

                    foreach ($states as $state) {
                        if (isset($project->customer_contract) && $state->id === $project->customer_contract->contactsAd->state_id) {
                            $st = $user->details && $project->customer_contract->contactsAd->state_id == $state->id ? $state->name : '';
                        }
                    }

                    $zip = $project->customer_contract ? $project->customer_contract->contactsAd->zip : '';

                    if (!empty($city) && !empty($address)) $address .= '<br/>' . $city;
                    if (!empty($st) && !empty($address)) $address .= ", ";
                    if (!empty($st)) $address .= ' ' . $st;
                    if (!empty($zip)) $address .= ' ' . $zip;


                    $json['result'] = true;
                    $role_id = ProjectDetail::where('id', $project->id);
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
                        $daysRemain[$key]['dates'] = ($years * 365) + ($months * 30) + ($days * 1);
                        $preliminaryDeadline = ProjectDates::select('date_value')
                            ->where('project_id', $project->id)
                            ->where('date_id', $remedyDateId)->first();
                        if ($preliminaryDeadline != null && $preliminaryDeadline->date_value != '') {
                            //return redirect()->back()->with('try-error', ' Fill-up the project dates section to get deadline');
                            $dateNew = date_create($preliminaryDeadline->date_value);
                            date_add($dateNew, date_interval_create_from_date_string($years . " years " . $months . " months " . $days . " days"));
                            //$prelim = new \DateTime($dateNew->format('m-d-Y'));
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
                        //                    if ($preliminaryDeadline == null) {
                        //                        return redirect()->back()->with('date-error', 'Please give some valid date in project date section ');
                        //                    }
                        //                    $daysRemain[$key]['deadline'] = $preliminaryDeadline->date_value;
                        //                    $daysRemain[$key]['preliminaryDates'] = date('Y-m-d', strtotime($daysRemain[$key]['deadline'] . '+' . $daysRemain[$key]['dates'] . ' days'));
                        $remedyNames[$value->getRemedy->id] = $value->getRemedy->remedy;
                    }

                    if (count($deadlines) > 0) {
                        foreach ($deadlines as $key => $deadline) {
                            if (strlen($daysRemain[$key]['preliminaryDates']) > 5) {
                                $today = date("Y-m-d");
                                $today = new \DateTime($today);
                                $prelimDead = $daysRemain[$key]['preliminaryDates'];
                                $formatPrelim = new \DateTime($prelimDead);
                                $daysUntilDeadline = date_diff($formatPrelim, $today);
                                $daysUntilDeadline = $daysUntilDeadline->format("%a");
                                $late = date_diff($formatPrelim, $today);
                                $late = $late->format("%R");
                            } else {
                                $daysUntilDeadline = 'N/A';
                                $late = 0;
                            }

                            $json['deadlines'][] = [
                                'name' => $deadline->getRemedy->remedy,
                                'desc' => $deadline->short_description,
                                'date' => $daysRemain[$key]['preliminaryDates'],
                                'days' => $daysUntilDeadline
                            ];
                        }
                    }

                    foreach ($dateFields as $date) {
                        $json['dates'][] = $date;
                    }
                    $json['customer_mapped'] = (isset($project->customer_contract) && isset($project->customer_contract->company)) ? true : false;
                    if ($json['customer_mapped']) {
                        $json['customer'] = [
                            ['name' => 'Company', 'value' => $project->customer_contract ? $project->customer_contract->company->company : ''],
                            ['name' => 'Address', 'value' => $address],
                            ['name' => 'Phone', 'value' => $project->customer_contract ? $project->customer_contract->contactsAd->phone : ''],
                            ['name' => 'Fax', 'value' => $project->customer_contract ? $project->customer_contract->contactsAd->fax : ''],
                            ['name' => 'Website', 'value' => $project->customer_contract ? $project->customer_contract->company->website : ''],
                            ['name' => 'Contact Name', 'value' => ($project->customer_contract != '' ? $project->customer_contract->contacts->first_name : '') . ' ' . ($project->customer_contract != '' ? $project->customer_contract->contacts->last_name : '')],
                            ['name' => 'Title', 'value' => $project->customer_contract != '' ? ($project->customer_contract->contacts->title == 'Other' ? $project->customer_contract->contacts->title_other : $project->customer_contract->contacts->title) : ''],
                            ['name' => 'Phone', 'value' => $project->customer_contract != '' ? $project->customer_contract->contacts->phone : ''],
                            ['name' => 'Cell', 'value' => $project->customer_contract != '' ? $project->customer_contract->contacts->cell : ''],
                            ['name' => 'Email', 'value' => $project->customer_contract != '' ? $project->customer_contract->contacts->email : ''],
                        ];
                    }

                    $address = $project->address;

                    $city = $project->city;
                    $state = "";

                    foreach ($states as $state) {
                        if ($state->id === $project->state_id) {
                            $st = $project->state_id == $state->id ? $state->name : '';
                        }
                    }

                    $zip = $project->zip;

                    if (!empty($city) && !empty($address)) $address .= '<br/>' . $city;
                    if (!empty($st) && !empty($address)) $address .= ", ";
                    if (!empty($st)) $address .= ' ' . $st;
                    if (!empty($zip)) $address .= ' ' . $zip;

                    $role = "";
                    foreach ($roles as $rol) {
                        if ($rol->id == $project->role_id) {
                            $role = $rol->project_roles;
                        }
                    }

                    $json['details'] = [
                        'name' => $project ? $project->project_name : '',
                        'address' => $address,
                        'type' => $project ? $project->project_type->project_type : '',
                        'role' => $role
                    ];


                    $lien = "No liens found for this job";

                    if (count($remedyNames) > 0) {

                        $lien = "";

                        foreach ($remedyNames as $remedyKey => $name) {

                            $lien .= "<p><strong>$name</strong></p>";

                            if (count($liens) > 0) {
                                foreach ($liens as $key => $lien_info) {
                                    if ($name == $lien_info['remedy']) {
                                        $lien .= "<p>{$lien_info->description}</p>";
                                        $lien .= "Tiers: <p>{$lien_info->tier_limit}</p>";
                                    }
                                }
                            }
                        }
                    }

                    $json['lien'] = $lien;
                }
            }

            echo json_encode($json);
        } elseif (is_null($project)) {
            return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
        } else {
            return view('errors.403');
        }
    }

    public function save(Request $request)
    {
        try {
            $filename = '';
            foreach ($request->input('document', []) as $file) {
                $filename = $file;
                // $project->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document');
            }
            //$request->columns;
            //ProjectTask::
            $ldate = date('Y-m-d');
            $cdate = date('Y-m-d H:i:s');

            $insertDetails = [
                'title' => $request->name,
                'project_id' => $request->project_id,
                'date' => $ldate,
                'notes' => $request->note,
                'filename' => $filename
            ];
            $url = $request->return_url;

            DB::table('project_documents')->insert($insertDetails);
            //USer::where('id',$userid)->update(['custom' =>$request->columns]);
            /*return response()->json([
                'status' => true,
                'success' => 'Task added successfully'
            ], 200);*/
            //return redirect()->back();
            // return redirect()->route('projects.index');
            // return Redirect::to($url);
            return redirect()->away($url);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'success' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'success' => $exception->getMessage()
            ], 200);
        }

        /*$project = Project::create($request->all());
        foreach ($request->input('document', []) as $file) {
            $project->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document');
        }*/
        //return redirect()->route('projects.index');
    }
    /* public function dropDownSave(Request $request){
		echo "hello";
		print_r($request);

	} */
    public function storeMedia(Request $request)
    {
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }


    public function delete(Request $request, $id, $project_id)
    {

        try {

            // $document = DB::table('project_documents')->where('id',$id)->get();

            //       $myArray = json_decode(json_encode($document), true);
            //       $array =array();
            //       for ($i=0; $i <count($myArray) ; $i++) {
            //           $array[] = $myArray[$i]['project_id'];
            //       }
            //       print_r($array);
            $pr_id = $project_id;

            DB::table('project_documents')->where('id', $id)->delete();

            return redirect()->to('https://www.nlb711.slysandbox.com/member/project/dashboard?project_id=' . $pr_id . '&edit=true#documents');
            // return redirect()->to('/');

            return response()->json([
                'status' => true,
                'success' => 'Document deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'success' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'success' => $exception->getMessage()
            ], 200);
        }
    }



    public function jobcontract(Request $request)
    {
        $project_id = $request->project_id;

        $url1 = $request->return_url;

        $projectContract = ProjectContract::where('project_id', $project_id)->first();

        if (!$projectContract) {
            $new_contract = true;
            $projectContract = new ProjectContract();
            $projectContract->project_id = $project_id;
        }

        $projectContract->base_amount = round($request->base_amount, 2);
        $projectContract->extra_amount = round($request->additional_cost, 2);
        $projectContract->credits = round($request->payments_credits, 2);
        $projectContract->waiver = round($request->waiver_amount, 2);
        $projectContract->receivable_status = $request->receivable_status;
        $projectContract->calculation_status = $request->calculation_status;
        $projectContract->total_claim_amount = (($projectContract->base_amount + $projectContract->extra_amount) - $projectContract->credits);

        if (isset($new_contract)) {
            $projectContract->save();
        } else {
            $projectContract->update();
        }

        return redirect()->away($url1);
    }
}
