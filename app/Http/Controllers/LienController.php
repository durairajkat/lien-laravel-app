<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use PDF;
use File;
use App\User;
use Exception;
use Validator;
use Carbon\Carbon;
use App\Models\State;
use App\Models\Company;
use App\Models\JobInfo;
use App\Models\JobContract;
use App\Models\ProjectType;
use App\Models\UserDetails;
use App\Models\JobInfoFiles;
use App\Models\LienProvider;
use App\Models\LienProviderStates;
use Illuminate\Http\Request;
use App\Models\MemberLienMap;
use App\Models\ProjectDetail;
use App\Models\CompanyContact;
use App\Models\ProjectContract;
use App\Models\LienLawSlideChart;
use App\Models\MapCompanyContact;
use App\Jobs\SendJobInfoController;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendOwnerNoticeController;
use Illuminate\Database\QueryException;
use App\Models\ProjectIndustryContactMap;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LienController extends Controller
{
    /**
     * List lien provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLien()
    {
        try {
            $search = request()->get('search');
            if ($search != '') {
                $lienProviders = LienProvider::where('company', 'LIKE', '%' . $search . '%')
                    ->orWhere('firstName', 'LIKE', '%' . $search . '%')
                    ->orWhere('lastName', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orderBy('created_at', 'desc')->paginate(15);
            } else {
                $lienProviders = LienProvider::orderBy('created_at', 'desc')->paginate(15);
            }
            $states = State::all();
            $companies = Company::pluck('company', 'id');
            return view('admin.lien.lien_list', [
                'states' => $states,
                'lienProviders' => $lienProviders,
                'companies' => $companies
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * Lien provider Add Edit form submit action
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitLienProvider(Request $request)
    {
        $code = $message = $status = '';
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'email|unique:users,email,' . $request->user_id
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'The email is already taken.',
                ], 200);
            }

            $fileName = '';
            if ($request->hasFile('logo')) {
                $extension = File::extension($request->logo->getClientOriginalName());
                if (strtolower($extension) == "jpeg" || strtolower($extension) == "jpg" || strtolower($extension) == "png") {
                    $file = $request->logo;
                    $fileName = time() . '.' . $extension;
                    $path = base_path();
                    $filePath = $path . "/public/liens/";
                    $file->move($filePath, $fileName);
                }
            }

            if ($request->type == 'Add') {
                $user = new User();
                $user->name = $request->fname . ' ' . $request->lname;
                $user->email = $request->email;
                $user->user_name = $request->fname . ' ' . $request->lname;
                $user->password = $request->password;
                $user->role = '7';
                $user->status = '0';
                $user->save();

                $role_name = $request->role_name;
                if(isset($request->role_other) && !empty($request->role_other) && $role_name == 'other') {
                    $role_name = $request->role_other;
                }

                $lienProvider = new LienProvider();
                $lienProvider->company_id = $request->company;
                $lienProvider->role_name = $role_name;
                $lienProvider->company = $request->company_name;
                $lienProvider->user_id = $user->id;
                $lienProvider->companyPhone = $request->company_phone;
                $lienProvider->firstName = $request->fname;
                $lienProvider->lastName = $request->lname;
                $lienProvider->address = $request->address;
                $lienProvider->city = $request->city;
                $lienProvider->stateId = $request->state[0];
                $lienProvider->zip = $request->zip;
                $lienProvider->phone = $request->phone;
                $lienProvider->fax = $request->fax;
                $lienProvider->email = $request->email;
                $lienProvider->logo = $fileName;
                $lienProvider->save();

                if(isset($request->state)) {
                    foreach ($request->state as $key => $value) {
                        $lienProviderStates = new LienProviderStates();
                        $lienProviderStates->lien_id = $lienProvider->id;
                        $lienProviderStates->state_id = $value;
                        $lienProviderStates->save();
                    }
                }

                $userDetails = new UserDetails();
                $userDetails->user_id = $user->id;
                $userDetails->company = $request->company_name;
                $userDetails->company_id = $request->company;
                $userDetails->first_name = $request->fname;
                $userDetails->last_name = $request->lname;
                $userDetails->address = $request->address;
                $userDetails->city = $request->city;
                $userDetails->state_id = $request->state[0];
                $userDetails->zip = $request->zip;
                $userDetails->phone = $request->phone;
                $userDetails->save();
            } else {
                $user = User::findOrFail($request->user_id);
                $user->name = $request->fname . ' ' . $request->lname;
                $user->email = $request->email;
                $user->password = $request->password;
                $user->update();

                $role_name = $request->role_name;
                if(isset($request->role_other) && !empty($request->role_other) && $role_name == 'other') {
                    $role_name = $request->role_other;
                }

                $lienProvider = LienProvider::findOrFail($request->lienId);
                $lienProvider->company_id = $request->company;
                $lienProvider->role_name = $role_name;
                $lienProvider->company = $request->company_name;
                $lienProvider->companyPhone = $request->company_phone;
                $lienProvider->firstName = $request->fname;
                $lienProvider->lastName = $request->lname;
                $lienProvider->address = $request->address;
                $lienProvider->city = $request->city;
                $lienProvider->stateId = $request->state[0];
                $lienProvider->zip = $request->zip;
                $lienProvider->phone = $request->phone;
                $lienProvider->fax = $request->fax;
                $lienProvider->email = $request->email;
                $lienProvider->logo = $fileName;
                $lienProvider->update();

                LienProviderStates::where('lien_id', $lienProvider->id)->delete();
                if(isset($request->state)) {
                    foreach ($request->state as $key => $value) {
                        $lienProviderStates = new LienProviderStates();
                        $lienProviderStates->lien_id = $lienProvider->id;
                        $lienProviderStates->state_id = $value;
                        $lienProviderStates->save();
                    }
                }

                $userDetails = $user->details;
                $userDetails->company = $request->company_name;
                $userDetails->company_id = $request->company;
                $userDetails->first_name = $request->fname;
                $userDetails->last_name = $request->lname;
                $userDetails->address = $request->address;
                $userDetails->city = $request->city;
                $userDetails->state_id = $request->state[0];
                $userDetails->zip = $request->zip;
                $userDetails->phone = $request->phone;
                $userDetails->update();
            }

            $status = true;
            $message = 'Lien Provider added successfully';
            $code = 200;
        } catch (Exception $e) {
            $status = true;
            $message = $e->getMessage();
            $code = 500;
        } catch (ModelNotFoundException $e) {
            $status = true;
            $message = $e->getMessage();
            $code = 500;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }

    /**
     * Delete lien provider
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLien(Request $request)
    {
        $code = $message = $status = '';
        try {
            $lienProvider = LienProvider::findOrFail($request->id);
            $user = $lienProvider->getUser;
            $userDetails = $user->details;
            $userDetails->delete();
            $user->delete();
            $lienProvider->delete();

            $status = true;
            $message = 'Lien Provider deleted successfully';
            $code = 200;
        } catch (Exception $e) {
            $status = true;
            $message = $e->getMessage();
            $code = 500;
        } catch (ModelNotFoundException $e) {
            $status = true;
            $message = $e->getMessage();
            $code = 500;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }

    /**
     * View slide chart
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewLienLawSlideChart()
    {
        try {
            $search = request()->get('search');
            $lienLaw = LienLawSlideChart::query();

            if (isset($search) && $search != '') {
                $lienLaw->orWhereHas('state', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })->orWhereHas('projectType', function ($query) use ($search) {
                    $query->where('project_type', 'LIKE', '%' . $search . '%');
                });
            }

            $pagination = request()->get('paginate');
            if (isset($pagination)) {
                $laws = $lienLaw->paginate($pagination);
            } else {
                $laws = $lienLaw->paginate(20);
            }
            return view('remedy.lein_law_slide_chart', [
                'lienLaws' => $laws
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * Add Lien Law Slide Chart
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLienLawSlideChart(Request $request)
    {
        ini_set('memory_limit', '-1');
        $code = $message = $status = '';
        try {
            if ($request->hasFile('lien')) {
                $extension = File::extension($request->lien->getClientOriginalName());
                if (strtolower($extension) == "xlsx" || strtolower($extension) == "xls" || strtolower($extension) == "csv") {
                    $file = $request->lien;
                    $fileName = time() . '.' . $extension;
                    $filePath = public_path() . "/upload";
                    $file->move($filePath, $fileName);
                    Excel::load($filePath . '/' . $fileName, function ($reader) {
                        $results = $reader->all();
                        foreach ($results as $key => $result) {
                            if ($key == 0) {
                                LienLawSlideChart::truncate();
                            }
                            $state = State::where('short_code', $result->state)->first();
                            $projectType = ProjectType::where('project_type', $result->projecttype)->first();
                            if ($state != '' && $projectType != '') {
                                $lien = new LienLawSlideChart();
                                $lien->state_id = $state->id;
                                $lien->project_type = $projectType->id;
                                $lien->remedy = $result->remedy;
                                $lien->description = $result->description;
                                $lien->tier_limit = $result->tiers;
                                $lien->save();
                            }
                        }
                    });
                    $status = true;
                    $message = 'Lien law Slide Chart uploaded successfully';
                    $code = 200;
                } else {
                    $status = false;
                    $message = 'Upload a valid file';
                    $code = 200;
                }
            } else {
                $status = false;
                $message = 'Please upload a file';
                $code = 200;
            }
        } catch (Exception $e) {
            $status = true;
            $message = $e->getMessage();
            $code = 200;
        } catch (ModelNotFoundException $e) {
            $status = true;
            $message = $e->getMessage();
            $code = 200;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }


    /**
     * Save Job Document
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveJobDocument(Request $request)
    {
        $lien = JobInfo::where('project_id', $request->project_id)->first();
        if ($lien == '') {
            $lien = new JobInfo();
            $lien->project_id = $request->project_id;
            $lien->save();
        }
        if (isset($request->newfiles) && count($request->newfiles) > 0) {
            // dd(count($request->newfiles));
            // $oldFiles = JobInfoFiles::where('job_info_id', $lien->id)->delete();
            foreach ($request->newfiles as $file) {
                $jobFile = new JobInfoFiles();
                $jobFile->job_info_id = $lien->id;
                $jobFile->file = $file;
                $jobFile->save();
            }
        } else {
            return redirect()->back()->with('try-error', 'Please upload some file first!!!');
        }
        return redirect()->back()->with('success', 'Document save successfully');
    }

    /**
     * Save and Edit Job Info Sheet
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveJobInfo(Request $request)
    {

        ini_set('memory_limit', '-1');

        try {
			/* $compnies= Company::where('user_id',$request->customer_company_id)->first();
			$customer_company_id= $request->customer_company_id;
			$compnies->company = $request->company_name;

			$compnies->address = $request->company_address;
			$compnies->city= $request->company_city;
			$compnies->state_id = $request->company_state;
			$compnies->zip = $request->company_zip;
			$compnies->save(); */



			$jobinfo = JobInfo::where('project_id',$request->project_id)->first();
            if(!$jobinfo) {
                $jobinfo = new JobInfo();
                $jobinfo->project_id = $request->project_id;
            }
			$jobinfo->company_name = $request->company_name;
			$jobinfo->company_fname = $request->company_fname;
			$jobinfo->company_lname =$request->company_lname;
			$jobinfo->company_address = $request->company_address;
			$jobinfo->company_city =$request->company_city;
			$jobinfo->company_state = $request->company_state;
			$jobinfo->company_zip =$request->company_zip;

			$jobinfo->gc_company = $request->gc_company;
			$jobinfo->gc_address = $request->gc_address;
			$jobinfo->gc_city  = $request->gc_city;
			$jobinfo->gc_state	 =$request->gc_state;
			$jobinfo->gc_zip	 =$request->gc_zip;
			$jobinfo->gc_phone	 =$request->gc_phone;
			$jobinfo->gc_fax	 =$request->gc_fax;
			$jobinfo->gc_web	 =$request->gc_web;
			$jobinfo->gc_first_name	 =$request->gc_first_name;
			$jobinfo->gc_last_name	 =$request->gc_last_name;
			$jobinfo->gc_title	 =$request->gc_title;
			$jobinfo->gc_direct_phone	 =$request->gc_direct_phone;
			$jobinfo->gc_cell	 =$request->gc_cell;
			$jobinfo->gc_email =$request->gc_email;

			$jobinfo->save();
			$project = ProjectDetail::findOrFail($request->project_id);

			$companyExists = Company::where('user_id', $project->user_id)->first();
            if (!empty($companyExists)) {
                $companyExists = Company::where('user_id', $project->user_id)->first();
                $companyExists->company = $request->customer_company;
                $companyExists->website = $request->customer_web;
                $companyExists->address = $request->customer_address;
                $companyExists->city =$request->customer_city;
                $companyExists->state_id = $request->customer_state_id;
                $companyExists->zip = $request->customer_zip;
                $companyExists->fax = $request->customer_fax;
                //$companyExists->email =
                $companyExists->save();

            } else {
                $companyExists = new Company();
				$companyExists->user_id = $project->user_id;
                $companyExists->company = $request->customer_company;
                $companyExists->website = $request->customer_web;
                $companyExists->address = $request->customer_address;
                $companyExists->city = $request->customer_city;
                $companyExists->state_id = $request->customer_state_id;
                $companyExists->zip = $request->customer_zip;
                $companyExists->fax = $request->customer_fax;
                //$companyExists->email =
                $companyExists->save();
            }

		/* $details = UserDetails::where('user_id',$request->customer_company_id)->first();



        $details->first_name = $request->company_fname;
        $details->last_name = $request->company_lname;

        $details->save();
			 */
            // Save the contract information from the Request
            $contractBase = str_replace(',', '', (isset($request->baseContract) && !empty($request->baseContract) ? $request->baseContract : 0));
            $contractExtra = str_replace(',', '', (isset($request->extraCharges) && !empty($request->extraCharges) ? $request->extraCharges : 0));
            $contractCredits = str_replace(',', '', (isset($request->credits) && !empty($request->credits) ? $request->credits : 0));
            $contractTotal = str_replace(',', '', (isset($request->contract_amount) && !empty($request->contract_amount) ? $request->contract_amount : 0));
            // Save the contract information
            $contractExists = ProjectContract::where('project_id', $request->project_id)->first();
            if (!empty($contractExists)) {
                $customerContract = ProjectContract::where('project_id', $request->project_id)->first();
                $customerContract->base_amount = number_format($contractBase, 2, '.', '');
                $customerContract->extra_amount = number_format($contractExtra, 2, '.', '');
                $customerContract->credits = number_format($contractCredits, 2, '.', '');
                $customerContract->total_claim_amount = number_format($contractTotal, 2, '.', '');
                $customerContract->save();
            } else {
                $customerContract = new ProjectContract();
                $customerContract->project_id = $request->project_id;
                $customerContract->base_amount = number_format($contractBase, 2, '.', '');
                $customerContract->extra_amount = number_format($contractExtra, 2, '.', '');
                $customerContract->credits = number_format($contractCredits, 2, '.', '');
                $customerContract->total_claim_amount = number_format($contractTotal, 2, '.', '');
                $customerContract->save();
            }

            $lien = JobInfo::where('project_id', $request->project_id)->first();
            if ($lien == '') {
                $lien = new JobInfo();
                $lien->project_id = $request->project_id;
            }
            $lien->customer_company_id = $request->customer_company_id;
            $lien->contract_amount = $request->contract_amount;
            $lien->first_day_of_work = $request->first_day_of_work;
            $lien->is_gc = $request->is_gc;
            $lien->signature = $request->Signature;
            $lien->signature_date = $request->SignatureDate;
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
            if (isset($request->newfiles) && count($request->newfiles) > 0) {
                $oldFiles = JobInfoFiles::where('job_info_id', $lien->id)->delete();
                foreach ($request->newfiles as $file) {
                    $jobFile = new JobInfoFiles();
                    $jobFile->job_info_id = $lien->id;
                    $jobFile->file = $file;
                    $jobFile->save();
                }
            }
            //if ($request->send != 'save') {
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
                if ($jobInfoSheet != '') {
                    $user = User::findOrFail($jobInfoSheet->customer_company_id);
                } else {
                    $user = User::findOrFail($project->user_id);
                }
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
                $fileNameOld = str_replace(' ', '-', $project->project_name) . time() . '.pdf';
                $actualPath = $path;
                file_put_contents($actualPath . $fileNameOld, $output);
				$fileName = str_replace(' ', '-', $project->project_name) . time() . '.pdf';
                $user = User::findOrFail(Auth::user()->id);
                $lienProvider = [];
                $lienProviders = Auth::user()->lienProvider;
                if ($user->details != '' && $user->details->lien_status == '1') {
                    if(count($lienProviders) == 0) {
                        $nationalProvider = LienProvider::where('id', '3')->first();
                        $lienProvider[] = $nationalProvider->email;
                    } else {
                        foreach ($lienProviders as $lienProviderU) {
                            if(isset($lienProviderU->findLien->stateId) && $lienProviderU->findLien->stateId == $project->state_id) {
                                $lienProvider[] = $lienProviderU->findLien->email;
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
            if ($request->send != 'save') {
                $uploadFile = new JobInfoFiles();
                $uploadFile->file = $fileNameOld;
                $uploadFile->job_info_id = $lien->id;
                $uploadFile->save();

                $path = base_path();
                $filePath = $path . "/public/upload/";
                file_put_contents($filePath . $fileNameOld, $output);
                SendJobInfoController::dispatch($lienProvider, $admin->toArray(), $actualPath . $fileName, $files, $adminDetails, Auth::user()->email);
            }
           // }
            if (isset($_GET['view']) && $_GET['view'] === 'detailed') {
                return redirect()->route('get.job.info.sheet', ['project_id' => $request->project_id, 'view' => 'detailed', 'saved' => 'true'])->with('success', 'Job information saved');
            } elseif (isset($_GET['view']) && $_GET['view'] === 'express') {
                return redirect()->route('get.job.info.sheet', ['project_id' => $request->project_id, 'view' => 'express', 'create' => 'true'])->with('success', 'Job information saved');
            } else {
                return redirect()->route('get.job.info.sheet', ['project_id' => $request->project_id, 'edit' => 'true', 'saved' => 'true'])->with('success', 'Job information saved');
            }

            //return redirect()->route('project.document.view', ['project_id' => $request->project_id, 'view' => 'detailed'])->with('success', 'Job information savedz');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage(), $e->getCode());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }


    /**
     * Save and Edit Job Info Sheet
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveOwnerNoticeForm(Request $request)
    {
        ini_set('memory_limit', '-1');
        try {
            // Save the contract information from the Request
            $contractBase = str_replace(',', '', (isset($request->baseContract) && !empty($request->baseContract) ? $request->baseContract : 0));
            $contractExtra = str_replace(',', '', (isset($request->extraCharges) && !empty($request->extraCharges) ? $request->extraCharges : 0));
            $contractCredits = str_replace(',', '', (isset($request->credits) && !empty($request->credits) ? $request->credits : 0));
            $contractTotal = str_replace(',', '', (isset($request->contract_amount) && !empty($request->contract_amount) ? $request->contract_amount : 0));
            // Save the contract information
            $contractExists = ProjectContract::where('project_id', $request->project_id)->first();
            if (!empty($contractExists)) {
                $customerContract = ProjectContract::where('project_id', $request->project_id)->first();
                $customerContract->base_amount = number_format($contractBase, 2, '.', '');
                $customerContract->extra_amount = number_format($contractExtra, 2, '.', '');
                $customerContract->credits = number_format($contractCredits, 2, '.', '');
                $customerContract->total_claim_amount = number_format($contractTotal, 2, '.', '');
                $customerContract->save();
            } else {
                $customerContract = new ProjectContract();
                $customerContract->project_id = $request->project_id;
                $customerContract->base_amount = number_format($contractBase, 2, '.', '');
                $customerContract->extra_amount = number_format($contractExtra, 2, '.', '');
                $customerContract->credits = number_format($contractCredits, 2, '.', '');
                $customerContract->total_claim_amount = number_format($contractTotal, 2, '.', '');
                $customerContract->save();
            }
            $project = ProjectDetail::findOrFail($request->project_id);
            $lien = JobInfo::where('project_id', $request->project_id)->first();
            if ($lien == '') {
                $lien = new JobInfo();
                $lien->project_id = $request->project_id;
            }
            $lien->customer_company_id = $request->customer_company_id;
            $lien->contract_amount = $request->contract_amount;
            $lien->first_day_of_work = $request->first_day_of_work;
            $lien->is_gc = $request->is_gc;
            $lien->signature = $request->Signature;
            $lien->signature_date = $request->SignatureDate;
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
            if (isset($request->newfiles) && count($request->newfiles) > 0) {
                $oldFiles = JobInfoFiles::where('job_info_id', $lien->id)->delete();
                foreach ($request->newfiles as $file) {
                    $jobFile = new JobInfoFiles();
                    $jobFile->job_info_id = $lien->id;
                    $jobFile->file = $file;
                    $jobFile->save();
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
                if ($jobInfoSheet != '') {
                    $user = User::findOrFail($jobInfoSheet->customer_company_id);
                } else {
                    $user = User::findOrFail($project->user_id);
                }

                //$lienProviderFinder = LienProvider::where('stateId', $project->state_id)->where('role_name', 'notice_service')->first();
                $lienProviders = LienProvider::join('lien_provider_states', function ($join) {
                    $join->on('lien_provider_states.lien_id', '=', 'lien_providers.id');
                })
                ->where('state_id', $project->state_id)
                ->where('role_name', 'notice_service')->first();

                $pdf = PDF::loadView('basicUser.document.notice_to_owner_export', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'lienProvider' => $lienProviders,
                    'user' => $user,
                    'lien' => $lien,
                    'projectContacts' => $projectContacts,
                    'jobInfoSheet' => $jobInfoSheet,
                    'contract' => $customerContract
                ]);

                $pdf->setPaper('A4', 'portrait');
                $output = $pdf->output();

//                $path = public_path() . '/job_info/';
                $fileName = str_replace(' ', '-', $project->project_name) . time() . '.pdf';
//                $actualPath = env('ASSET_URL') . '/job_info/';
//                file_put_contents($actualPath . $fileName, $output);

                $path = base_path();
                $filePath = $path . "/public/upload/";
                file_put_contents($filePath.$fileName, $output);

                $jobFile = new JobInfoFiles();
                $jobFile->job_info_id = $jobInfoSheet->id;
                $jobFile->file = $fileName;
                $jobFile->save();

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
                SendOwnerNoticeController::dispatch($lienProvider, $admin->toArray(), $filePath.$fileName, $files, $adminDetails);
            }
            if (isset($_GET['view']) && $_GET['view'] === 'detailed') {
                return redirect()->route('get.owner.notice.sheet', ['project_id' => $request->project_id, 'view' => 'detailed', 'saved' => 'true'])->with('success', 'Notice to Owner saved');
            } elseif (isset($_GET['view']) && $_GET['view'] === 'express') {
                return redirect()->route('get.owner.notice.sheet', ['project_id' => $request->project_id, 'view' => 'express', 'create' => 'true'])->with('success', 'Notice to Owner saved');
            } else {
                return redirect()->route('get.owner.notice.sheet', ['project_id' => $request->project_id, 'edit' => 'true', 'saved' => 'true'])->with('success', 'Notice to Owner saved');
            }

            //return redirect()->route('project.document.view', ['project_id' => $request->project_id, 'view' => 'detailed'])->with('success', 'Job information savedz');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage(), $e->getCode());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
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

    public function exportJobInfoTest($projectId)
    {
        ini_set('memory_limit', '-1');
        try {
            $project = ProjectDetail::findOrFail($projectId);
            $jobInfo = JobInfo::where('project_id', $projectId)->firstOrFail();
            $states = State::all();

            $pdf = PDF::loadView('basicUser.document.job_test', [
                'project_id' => $project->id,
                'project' => $project,
                'job_info' => $jobInfo,
                'states' => $states
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream(str_replace(' ', '-', $project->project_name) . '.pdf');
            /*return view('basicUser.document.job_info_sheet_export', [
        'project_id' => $project->id,
        'project' => $project,
        'job_info' => $jobInfo,
        'states' => $states
    ]);*/
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Save Job Info Files
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveJobInfoFile(Request $request)
    {
        ini_set('memory_limit', '-1');
        $code = $message = $status = '';
        try {
            if ($request->hasFile('lien')) {
                $extension = \File::extension($request->lien->getClientOriginalName());
                if (strtolower($extension) != "sql") {
                    $file = $request->lien;
                    $fileName = time() . '.' . $extension;
                    $filePath = base_path()."/public/upload";
                    $file->move($filePath, $fileName);

                    return response()->json([
                        'status' => true,
                        'message' => 'Upload successful',
                        'name' => $fileName,
                        'time' => str_replace('.', '_', $fileName),
                    ], 200);
                } else {
                    $status = false;
                    $message = 'Upload a valid file';
                    $code = 200;
                }
            } else {
                $status = false;
                $message = 'Please upload a file';
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
     * Deletes Job Info Files
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeJobInfoFile(Request $request)
    {
        try {
            $file = JobInfoFiles::findOrFail($request->id);
            $file->delete();

            return response()->json([
                'status' => true,
                'message' => 'deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * View Job Info From Admin Panel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewJobInfo()
    {
        try {
            $sortFlag =  request()->get('sort');
            $searchFlag = request()->get('search');
            $jobInfo = JobInfo::query();

            if (isset($searchFlag) && $searchFlag == '1') {
                $lienProvider = request()->get('lien_provider');
                $assignedDateFrom = request()->get('assigned_date_from');
                $assignedDateTo = request()->get('assigned_date_to');
                $userName = request()->get('user_name');
                $projectName = request()->get('project_name');
                $customerName = request()->get('customer_name');
                $companyName = request()->get('company_name');
                $dateCompletedFrom = request()->get('date_completed_from');
                $dateCompletedTo = request()->get('date_completed_to');
                $status = request()->get('status');

                if ($lienProvider != '') {
                    $jobInfo->whereHas('getProject.user.lienProvider.getLien', function ($query) use ($lienProvider) {
                        $query->where('id', $lienProvider);
                        // $query->where('firstName', 'LIKE', '%'.$lienProvider.'%')->orWhere('lastName','Like','%'.$lienProvider.'%');
                    });
                }

                //if (($assignedDateFrom != '') && ($assignedDateTo!='')) {
                $dateFrom = ($assignedDateFrom != '') ? date('Y-m-d 23:59:59', strtotime($assignedDateFrom)) : date('Y-m-d H:i:s', strtotime(''));
                $dateTo = ($assignedDateTo != '') ? date('Y-m-d 23:59:59', strtotime($assignedDateTo)) : date('Y-m-d H:i:s');
                if ($dateFrom > $dateTo) {
                    return redirect()->route('admin.job.info')->with('search-error', 'Date assigned from field must not exceed date assigned to field.');
                }

                $jobInfo->whereBetween('created_at', [$dateFrom, $dateTo]);
                //}

                if ($userName != '') {
                    $userName = explode(" ", $userName);
                    if (count($userName) > 1) {
                        $jobInfo->whereHas('getProject.user.details', function ($query) use ($userName) {
                            $query->where('first_name', 'LIKE', '%' . (array_key_exists(0, $userName) ? $userName[0] : null) . '%')->orWhere('last_name', 'LIKE', '%' . (array_key_exists(1, $userName) ? $userName[1] : null) . '%');
                        });
                    } else {
                        $jobInfo->whereHas('getProject.user.details', function ($query) use ($userName) {
                            $query->where('first_name', 'LIKE', '%' . (array_key_exists(0, $userName) ? $userName[0] : null) . '%')->orWhere('last_name', 'LIKE', '%' . (array_key_exists(0, $userName) ? $userName[0] : null) . '%');
                        });
                    }
                }
                if ($companyName != '') {
                    $jobInfo->whereHas('getProject.user.details.getCompany', function ($query) use ($companyName) {
                        $query->where('id', '=', $companyName);
                    });
                    /*$jobInfo->whereHas('getProject.user.details.getCompany', function ($query) use ($companyName) {
                $query->where('company', 'LIKE', '%'.$companyName.'%');
            });*/
                }
                if ($projectName != '') {
                    $jobInfo->whereHas('getProject', function ($query) use ($projectName) {
                        $query->where('id', '=', $projectName);
                    });
                    /* $jobInfo->whereHas('getProject', function ($query) use ($projectName) {
            $query->where('project_name', 'LIKE', '%'.$projectName.'%');
        });*/
                }
                if ($customerName != '') {
                    $jobInfo->whereHas('customerContract', function ($query) use ($customerName) {
                        $query->where('company', 'LIKE', '%' . $customerName . '%');
                    });
                }
                if (($dateCompletedFrom != '') || ($dateCompletedTo != '')) {
                    if (!isset($dateCompletedFrom)) {
                        return redirect()->route('admin.job.info')->with('search-error', 'Please enter date completed from field');
                    }
                    if (!isset($dateCompletedTo)) {
                        return redirect()->route('admin.job.info')->with('search-error', 'Please enter date completed to field');
                    }
                    $dateCompletedFrom = ($dateCompletedFrom != '') ? date('Y-m-d 00:00:00', strtotime($dateCompletedFrom)) : date('Y-m-d H:i:s', strtotime(''));
                    $dateCompletedTo = ($dateCompletedTo != '') ? date('Y-m-d 23:59:59', strtotime($dateCompletedTo)) : date('Y-m-d H:i:s', strtotime(''));
                    if ($dateCompletedFrom > $dateCompletedTo) {
                        return redirect()->route('admin.job.info')->with('search-error', 'Date completed from field must not exceed date completed to field.');
                    }
                    $jobInfo->whereBetween('date_completed', [$dateCompletedFrom, $dateCompletedTo]);
                }
                if ($status != '') {
                    $jobInfo->where('status', $status);
                }
            }

            if (isset($sortFlag) && $sortFlag == '1') {
                $sortUserName = request()->get('user');
                $sortCompanyName = request()->get('company_name');
                $sortProjectName = request()->get('project_name');
                $sortDateCreated = request()->get('date_created');
                $sortLastEditedDate = request()->get('last_edited_date');

                if (isset($sortUserName) && !is_null($sortUserName) && !empty($sortUserName)) {
                    $jobInfo->join('project_details', function ($join) {
                        $join->on('job_infos.project_id', '=', 'project_details.id');
                    })->join('users', function ($join) {
                        $join->on('project_details.user_id', '=', 'users.id');
                    })->join('user_details', function ($join) {
                        $join->on('user_details.user_id', '=', 'users.id');
                    })->orderBy('user_details.first_name', $sortUserName);
                }

                if (isset($sortCompanyName) && !is_null($sortCompanyName) && !empty($sortCompanyName)) {
                    $jobInfo->join('project_details', function ($join) {
                        $join->on('job_infos.project_id', '=', 'project_details.id');
                    })->join('users', function ($join) {
                        $join->on('project_details.user_id', '=', 'users.id');
                    })->join('user_details', function ($join) {
                        $join->on('user_details.user_id', '=', 'users.id');
                    })->join('companies', function ($join) {
                        $join->on('user_details.company_id', '=', 'companies.id');
                    })->orderBy('companies.company', $sortCompanyName);
                }

                if (isset($sortProjectName) && !is_null($sortProjectName) && !empty($sortProjectName)) {
                    $jobInfo->join('project_details', function ($join) {
                        $join->on('job_infos.project_id', '=', 'project_details.id');
                    })->orderBy('project_name', $sortProjectName);
                }

                if (isset($sortDateCreated) && !is_null($sortDateCreated) && !empty($sortDateCreated)) {
                    $jobInfo->orderBy('created_at', $sortDateCreated);
                }

                if (isset($sortLastEditedDate) && !is_null($sortLastEditedDate) && !empty($sortLastEditedDate)) {
                    $jobInfo->orderBy('updated_at', $sortLastEditedDate);
                }
            }
            if (isset($sortFlag)) {
                $jobInfos = $jobInfo->paginate(15);
            } else {
                $jobInfos = $jobInfo->orderBy('created_at', 'DESC')->paginate(15);
            }

            foreach ($jobInfos as $key => $jobInfo) {
                if(!isset($jobInfo->getProject) || !isset($jobInfo->getProject['user']) ||  !isset($jobInfo->getProject['user']['email'])) {
                    unset($jobInfos[$key]);
                }
            }

            $lienList = [];
            $lienProvidersList = LienProvider::orderBy('firstName', 'ASC')->get();
            foreach ($lienProvidersList as $key => $lienPro) {
                $lienList[$key]['id'] = $lienPro->id;
                $lienList[$key]['data'] = $lienPro->firstName . " " . $lienPro->lastName . "( " . $lienPro->company . " )";
                $lienList[$key]['value'] = $lienPro->firstName . " " . $lienPro->lastName;
            }

            $projectDetails = ProjectDetail::orderBy('project_name', 'ASC')->get();
            $projects = [];
            foreach ($projectDetails as $key => $projectDetail) {
                $projects[$key]['id'] = $projectDetail->id;
                $projects[$key]['data'] = $projectDetail->project_name;
                $projects[$key]['value'] = $projectDetail->project_name;
            }

            $companyDetails = Company::orderBy('company', 'ASC')->get();
            $companies = [];
            foreach ($companyDetails as $key => $companyDetail) {
                $companies[$key]['id'] = $companyDetail->id;
                $companies[$key]['data'] = $companyDetail->company;
                $companies[$key]['value'] = $companyDetail->company;
            }
            return view('project.job_info', [
                'jobInfos' => $jobInfos,
                'lienList' => $lienList,
                'projects' => $projects,
                'companies' => $companies
            ]);
        } catch (\Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            //return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        }
    }


    /**
     * View Job Info From Lien Providers Panel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function lienJobInfoView()
    {
        try {
            $sortFlag =  request()->get('sort');
            $searchFlag = request()->get('search');
            $membersAssociated = MemberLienMap::where('lien_id', Auth::user()->lienUser->id)->pluck('user_id')->toArray();
            $projects = ProjectDetail::whereIn('user_id', $membersAssociated)->pluck('id');
            $jobInfo = JobInfo::query();
            $projectList = ProjectDetail::whereIn('user_id', $membersAssociated)->pluck('project_name', 'id');

            if (isset($searchFlag) && $searchFlag == '1') {
                $lienProvider = request()->get('lien_provider');
                $assignedDateFrom = request()->get('assigned_date_from');
                $assignedDateTo = request()->get('assigned_date_to');
                $userName = request()->get('user_name');
                $projectName = request()->get('project_name');
                $customerName = request()->get('customer_name');
                $companyName = request()->get('company_name');
                $dateCompletedFrom = request()->get('date_completed_from');
                $dateCompletedTo = request()->get('date_completed_to');
                $status = request()->get('status');

                if ($lienProvider != '') {
                    $jobInfo->whereHas('getProject.user.lienProvider.getLien', function ($query) use ($lienProvider) {
                        $query->where('id', $lienProvider);
                        // $query->where('firstName', 'LIKE', '%'.$lienProvider.'%')->orWhere('lastName','Like','%'.$lienProvider.'%');
                    });
                }

                if (($assignedDateFrom != '') && ($assignedDateTo != '')) {
                    $dateFrom = ($assignedDateFrom != '') ? date('Y-m-d 00:00:00', strtotime($assignedDateFrom)) : date('Y-m-d', strtotime(''));
                    $dateTo = ($assignedDateTo != '') ? date('Y-m-d 23:59:59', strtotime($assignedDateTo)) : date('Y-m-d');

                    $jobInfo->whereBetween('created_at', [$dateFrom, $dateTo]);
                }

                if ($userName != '') {
                    $userName = explode(" ", $userName);
                    if (count($userName) > 1) {
                        $jobInfo->whereHas('getProject.user.details', function ($query) use ($userName) {
                            $query->where('first_name', 'LIKE', '%' . (array_key_exists(0, $userName) ? $userName[0] : null) . '%')->orWhere('last_name', 'LIKE', '%' . (array_key_exists(1, $userName) ? $userName[1] : null) . '%');
                        });
                    } else {
                        $jobInfo->whereHas('getProject.user.details', function ($query) use ($userName) {
                            $query->where('first_name', 'LIKE', '%' . (array_key_exists(0, $userName) ? $userName[0] : null) . '%')->orWhere('last_name', 'LIKE', '%' . (array_key_exists(0, $userName) ? $userName[0] : null) . '%');
                        });
                    }
                }
                if ($companyName != '') {
                    $jobInfo->whereHas('getProject.user.details.getCompany', function ($query) use ($companyName) {
                        $query->where('company', 'LIKE', '%' . $companyName . '%');
                    });
                }
                if ($projectName != '') {
                    $jobInfo->whereHas('getProject', function ($query) use ($projectName) {
                        $query->where('project_name', 'LIKE', '%' . $projectName . '%');
                    });
                }
                if ($customerName != '') {
                    $jobInfo->whereHas('customerContract', function ($query) use ($customerName) {
                        $query->where('company', 'LIKE', '%' . $customerName . '%');
                    });
                }
                if (($dateCompletedFrom != '') || ($dateCompletedTo != '')) {
                    $dateCompletedFrom = ($dateCompletedFrom != '') ? date('Y-m-d 00:00:00', strtotime($dateCompletedFrom)) : date('Y-m-d', strtotime(''));
                    $dateCompletedTo = ($dateCompletedTo != '') ? date('Y-m-d 23:59:59', strtotime($dateCompletedTo)) : date('Y-m-d', strtotime(''));
                    $jobInfo->whereBetween('date_completed', [$dateCompletedFrom, $dateCompletedTo]);
                }
                if ($status != '') {
                    $jobInfo->where('status', $status);
                }
            }

            if (isset($sortFlag) && $sortFlag == '1') {
                $sortUserName = request()->get('user');
                $sortCompanyName = request()->get('company_name');
                $sortProjectName = request()->get('project_name');
                $sortDateCreated = request()->get('date_created');
                $sortLastEditedDate = request()->get('last_edited_date');

                if (isset($sortUserName) && !is_null($sortUserName) && !empty($sortUserName)) {
                    $jobInfo->join('project_details', function ($join) {
                        $join->on('job_infos.project_id', '=', 'project_details.id');
                    })->join('users', function ($join) {
                        $join->on('project_details.user_id', '=', 'users.id');
                    })->join('user_details', function ($join) {
                        $join->on('user_details.user_id', '=', 'users.id');
                    })->select('job_infos.*', 'user_details.first_name')->whereIn('project_id', $projects)->orderBy('user_details.first_name', $sortUserName);
                }

                if (isset($sortCompanyName) && !is_null($sortCompanyName) && !empty($sortCompanyName)) {
                    $jobInfo->join('project_details', function ($join) {
                        $join->on('job_infos.project_id', '=', 'project_details.id');
                    })->join('users', function ($join) {
                        $join->on('project_details.user_id', '=', 'users.id');
                    })->join('user_details', function ($join) {
                        $join->on('user_details.user_id', '=', 'users.id');
                    })->join('companies', function ($join) {
                        $join->on('user_details.company_id', '=', 'companies.id');
                    })->select('job_infos.*', 'companies.company')->whereIn('project_id', $projects)->orderBy('companies.company', $sortCompanyName);
                }

                if (isset($sortProjectName) && !is_null($sortProjectName) && !empty($sortProjectName)) {
                    $jobInfo->join('project_details', function ($join) {
                        $join->on('job_infos.project_id', '=', 'project_details.id');
                    })->select('job_infos.*', 'project_details.project_name')->whereIn('project_id', $projects)->orderBy('project_name', $sortProjectName);
                }

                if (isset($sortDateCreated) && !is_null($sortDateCreated) && !empty($sortDateCreated)) {
                    $jobInfo->whereIn('project_id', $projects)->orderBy('created_at', $sortDateCreated);
                }

                if (isset($sortLastEditedDate) && !is_null($sortLastEditedDate) && !empty($sortLastEditedDate)) {
                    $jobInfo->whereIn('project_id', $projects)->orderBy('updated_at', $sortLastEditedDate);
                }
            }
            if (isset($sortFlag)) {
                $jobInfos = $jobInfo->paginate(15);
            } else {
                $jobInfos = $jobInfo->whereIn('project_id', $projects)->orderBy('created_at', 'DESC')->paginate(15);
            }

            $lienList = [];
            $lienProvidersList = LienProvider::all();
            foreach ($lienProvidersList as $key => $lienPro) {
                $lienList[$key]['id'] = $lienPro->id;
                $lienList[$key]['data'] = $lienPro->firstName . " " . $lienPro->lastName . "( " . $lienPro->company . " )";
                $lienList[$key]['value'] = $lienPro->firstName . " " . $lienPro->lastName;
            }

            $projectDetails = ProjectDetail::all();
            $projects = [];
            foreach ($projectDetails as $key => $projectDetail) {
                $projects[$key]['id'] = $projectDetail->id;
                $projects[$key]['data'] = $projectDetail->project_name;
                $projects[$key]['value'] = $projectDetail->project_name;
            }

            //    $companyDetails = Company::all();
            $companies = [];
            foreach ($membersAssociated as $key => $member) {
                $memberUser = User::findOrFail($member);
                $memberCompany = $memberUser->details->getCompany;
                $companies[$key]['id'] = $memberCompany->id;
                $companies[$key]['data'] = $memberCompany->company;
                $companies[$key]['value'] = $memberCompany->company;
            }

            return view('lienProviders.jobInfo.view', [
                'jobInfos' => $jobInfos,
                'lienList' => $lienList,
                'projects' => $projects,
                'companies' => $companies,
                'projectList' => $projectList
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * Autocomplete job info.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoCompleteJobInfo(Request $request)
    {
        try {
            $searchKey = $request->search;
            $result = [];
            $key = $request->key;
            switch ($searchKey) {
                case 'lien_provider':
                    $lienProviders = LienProvider::where('firstName', 'LIKE', '%' . $key . '%')->orWhere('lastName', 'LIKE', '%' . $key . '%')->get(['id', 'firstName', 'lastName', 'company']);
                    foreach ($lienProviders as $key => $lienProvider) {
                        $result[$key]['id'] = $lienProvider->id;
                        $result[$key]['data'] = $lienProvider->firstName . " " . $lienProvider->lastName . "( " . $lienProvider->company . " )";
                        $result[$key]['value'] = $lienProvider->firstName . " " . $lienProvider->lastName;
                    }
                    break;

                case 'user_name':
                    $userDetails = UserDetails::where('first_name', 'LIKE', '%' . $key . '%')->orWhere('last_name', 'LIKE', '%' . $key . '%')->get(['id', 'first_name', 'last_name']);
                    foreach ($userDetails as $key => $userDetail) {
                        $result[$key]['id'] = $userDetail->id;
                        $result[$key]['data'] = $userDetail->first_name . " " . $userDetail->last_name;
                        $result[$key]['value'] = $userDetail->first_name . " " . $userDetail->last_name;
                    }
                    break;

                case 'project_name':
                    $projectDetails = ProjectDetail::where('project_name', 'LIKE', '%' . $key . '%')->get(['id', 'project_name']);
                    foreach ($projectDetails as $key => $projectDetail) {
                        $result[$key]['id'] = $projectDetail->id;
                        $result[$key]['data'] = $projectDetail->project_name;
                        $result[$key]['value'] = $projectDetail->project_name;
                    }
                    break;

                case 'customer_name':
                    $customerDetails = CompanyContact::where('type', '0')->where('first_name', 'LIKE', '%' . $key . '%')->orWhere('last_name', 'LIKE', '%' . $key . '%')->get(['id', 'first_name', 'last_name']);
                    foreach ($customerDetails as $key => $customerDetail) {
                        $result[$key]['id'] = $customerDetail->id;
                        $result[$key]['data'] = $customerDetail->first_name . ' ' . $customerDetail->last_name;
                        $result[$key]['value'] = $customerDetail->first_name . ' ' . $customerDetail->last_name;
                    }
                    break;

                case 'company_name':
                    $companyDetails = Company::where('company', 'LIKE', '%' . $key . '%')->get(['id', 'company']);
                    foreach ($companyDetails as $key => $companyDetail) {
                        $result[$key]['id'] = $companyDetail->id;
                        $result[$key]['data'] = $companyDetail->company;
                        $result[$key]['value'] = $companyDetail->company;
                    }
                    break;
            }

            return response()->json([
                'success'   => true,
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $e->getMessage()
            ], $e->getCode());
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        } catch (QueryException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        }
    }

    /**
     * Update is viewable status in job info
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeViewable(Request $request)
    {
        try {
            $jobInfo = JobInfo::findOrFail($request->id);
            if ($request->status == '0') {
                $jobInfo->is_viewable = '1';
            } else {
                $jobInfo->is_viewable = '0';
            }
            $jobInfo->update();
            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'Status changed successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $exception->getMessage()
            ], 200);
        }
    }
}
