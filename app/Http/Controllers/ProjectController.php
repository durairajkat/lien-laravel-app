<?php

namespace App\Http\Controllers;

use App\Models\JobInfoFiles;
use App\User;
use File;
use Validator;
use GuzzleHttp;
use App\Models\State;
use App\Models\Remedy;
use App\Models\Company;
use App\Models\Contact;
use App\Models\JobInfo;
use App\Models\TierTable;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\ProjectRole;
use App\Models\ProjectTask;
use App\Models\ProjectType;
use App\Models\UserDetails;
use App\Models\CustomerCode;
use App\Models\ProjectDates;
use App\Models\ProjectEmail;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Models\ProjectDetail;
use App\Models\CompanyContact;
use App\Models\RemedyQuestion;
use App\Models\TierRemedyStep;
use App\Models\ProjectContract;
use App\Models\ProjectDeadline;
use App\Models\UserPreferences;
use App\Models\CreditApplication;
use App\Models\LienLawSlideChart;
use App\Models\MapCompanyContact;
use App\Models\ProjectTaskOther;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use App\Models\ClaimFormProjectDataSheet;
use App\Models\JointPaymentAuthorization;
use App\Models\ProjectIndustryContactMap;
use App\Models\UnconditionalWaiverProgress;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ProjectController for Project System
 * @package App\Http\Controllers
 */
class ProjectController extends Controller
{
    public function searchProjectRecord(Request $request)
    {
        $project_id = 388;
        $user = Auth::user()->id;

        $project = ProjectDetail::where('user_id', $user)->where('id', $project_id)->first();
        $states = State::all();
        $liens = '';
        $remedyNames = [];

        if (isset($_GET['edit'])) {
            $liens = LienLawSlideChart::where('state_id', $project->state_id)
                ->where('project_type', $project->project_type_id)->get();
            $remedyNames = [];
            foreach ($liens as $lien) {
                $remedyNames[] = $lien->remedy;
            }
        }

        return view('basicUser.projects.record_search', [
            // 'project' => $project,
            // 'selected_project' => $project,
            'states' => $states,
            'remedyNames' => array_unique($remedyNames),
            'liens' => $liens
        ]);
    }
    /**
     * Get Project Listing page in member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProject(Request $request)
    {
        try {
            // dd($request);
            $projectTypes = ProjectType::pluck('project_type', 'id')->toArray();
            $states = State::pluck('name', 'id')->toArray();
            $customers = !is_null(Auth::user()->checkCustomer()) ? $this->createCustomerList(Auth::user()->checkCustomer()->get()) : [];

            $subUsers = !is_null(Auth::user()->subUsers()->count() > 0) ? Auth::user()->subUsers()->pluck('name', 'id') : [];
            $dateTypes = $this->getRemedyDate(auth()->user());
            $projectNames = !is_null(Auth::user()->projects()) ? Auth::user()->projects()->pluck('project_name', 'id') : [];
            $lienProviders = !is_null(Auth::user()->lienProvider()) ? $this->createLienList(Auth::user()->lienProvider) : [];
            $case = $request->get('case');
            $projectDetails = $request->get('projectDetails');
            $sortBy = $request->get('sortBy');
            $sortWith = $request->get('sortWith');
            $paginate = $request->get('paginate');

            $projects = Auth::user()->projects()->where('status', '1')->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*, project_contracts.total_claim_amount AS unpaid_balance'));
            $active_projects = $projects->get();

            if ($case == 'active') {
                //$projects = ProjectDetail::where('user_id', Auth::user()->id)->where('status', '1');
                // $projects = Auth::user()->projects()->where('status', '1')->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*, project_contracts.total_claim_amount AS unpaid_balance'));
            } else {
                $projects = Auth::user()->projects()->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance'));
                //$projects = ProjectDetail::where('user_id', Auth::user()->id);
            }
            $test = Auth::user()->projects()->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.id,  project_contracts.total_claim_amount AS unpaid_balance'))->get();
            if (isset($projectDetails)) {
                $projects->where(function ($query1) use ($projectDetails) {
                    $query1->Where('project_name', 'LIKE', '%' . $projectDetails . '%')
                        ->orWhere('project_details.created_at', 'LIKE', '%' . date("Y-m-d H:i:s", strtotime($projectDetails)) . '%')
                        ->orWhereHas('state', function ($query) use ($projectDetails) {
                            $query->where('name', 'LIKE', '%' . $projectDetails . '%');
                        })
                        ->orWhereHas('project_type', function ($query) use ($projectDetails) {
                            $query->where('project_type', 'LIKE', '%' . $projectDetails . '%');
                        })
                        ->orWhereHas('customer_contract.getContacts', function ($query) use ($projectDetails) {
                            $query->where('type', '0')->whereHas('mapContactCompany.company', function ($query) use ($projectDetails) {
                                $query->where('company', 'LIKE', '%' . $projectDetails . '%');
                            });
                        })->orWhereHas('customer_contract.getContacts', function ($query) use ($projectDetails) {
                            $query->where('first_name', 'LIKE', '%' . $projectDetails . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $projectDetails . '%')
                                ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', '%' . $projectDetails . '%');
                        });
                });
            }
            if (isset($sortBy) && isset($sortWith)) {
                switch ($sortWith) {
                    case 'customer_company_name':
                        $projects->leftJoin('map_company_contacts', function ($join) {
                            $join->on('project_details.customer_contract_id', '=', 'map_company_contacts.id');
                        })->leftJoin('companies', function ($join) {
                            $join->on('map_company_contacts.company_id', '=', 'companies.id');
                        })->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance, companies.company'))->orderBy('companies.company', $sortBy);

                        break;

                    case 'customer_name':
                        $projects->leftJoin('map_company_contacts', function ($join) {
                            $join->on('project_details.customer_contract_id', '=', 'map_company_contacts.id');
                        })->leftJoin('company_contacts', function ($join) {
                            $join->on('map_company_contacts.company_contact_id', '=', 'company_contacts.id');
                        })->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance, company_contacts.first_name'))->orderBy('company_contacts.first_name', $sortBy);

                        break;

                    case 'customer_phone_number':
                        $projects->leftJoin('map_company_contacts', function ($join) {
                            $join->on('project_details.customer_contract_id', '=', 'map_company_contacts.id');
                        })->leftJoin('company_contacts', function ($join) {
                            $join->on('map_company_contacts.company_contact_id', '=', 'company_contacts.id');
                        })->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance, company_contacts.phone'))->orderBy('company_contacts.phone', $sortBy);

                        break;


                    default:
                        $projects->orderBy($sortWith, $sortBy);
                        break;
                }
            }
            $projects = !is_null($this->searchFilter($request, $projects)) ? $this->searchFilter($request, $projects) : $projects;
            $queryParams = $this->getQueryParams($request);

            if (isset($sortWith)) {
                //$projects->orderBy($sortWith, $sortBy);
            } else {
                $projects->orderBy('project_details.updated_at', 'DESC');
            }

            if (isset($paginate)) {
                if ($paginate <= 100) {
                    $projectssearch = $projects->paginate($paginate);
                } else {
                    $projectssearch = $projects->paginate($projects->count());
                }
            } else {
                $projectssearch = $projects->paginate(15);
            }
            if (isset($queryParams) == true) {
                return view('basicUser.projects.list', [
                    'projects' => $projectssearch,
                    'projectTypes' => $projectTypes,
                    'active_projects' => count($active_projects),
                    'states' => $states,
                    'customers' => $customers,
                    'subUsers' => $subUsers,
                    'dateTypes' => $dateTypes,
                    'queryParams' => $queryParams,
                    'projectNames' => $projectNames,
                    'lienProviders' => $lienProviders,
                    'unpaid' => $test
                ]);
            } else {
                return view('basicUser.projects.list', [
                    'projects' => $projectssearch,
                    'active_projects' => $active_projects,
                    'projectTypes' => $projectTypes,
                    'states' => $states,
                    'customers' => $customers,
                    'subUsers' => $subUsers,
                    'dateTypes' => $dateTypes,
                    'projectNames' => $projectNames,
                    'lienProviders' => $lienProviders,
                    'unpaid' => $test
                ]);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (QueryException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Search filter options.
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function searchFilter($request, $projects = null)
    {
        // This doesn't seem like it is needed, just duplicating what is already done in getProject
        //$projects = $this->searchProjectsThroughSearchBar($projects);
        $projects = $this->searchProjectsThroughAdvancedFilter($request, $projects);
        return $projects;
    }

    protected function searchProjectsThroughAdvancedFilter($request, $projects = null)
    {
        $projectStatus = $request->input('project_status');
        $jobinfoStatus = $request->input('job_info_status');
        $projectType = $request->input('project_type');
        $projectState = $request->input('project_state');
        $receivableStatus = $request->input('receivable_status');
        $claimAmount = $request->input('claim_amount');
        $customer = $request->input('customers');
        $projectName = $request->input('project_name');
        $user = $request->input('user');
        $complianceManagement = $request->input('compliance_management');
        $dateType = $request->input('date_type');
        $from = $request->input('from');
        $to = $request->input('to');
        $complianceProvider = $request->input('compliance_provider');
        $deadlineCalc = $request->input('calculation_status');

        if (isset($projectStatus) && $projectStatus != 'all') {
        }

        if (isset($jobinfoStatus) && $jobinfoStatus != 'all') {
            $projects->whereHas('jobInfo', function ($query) use ($jobinfoStatus) {
                $query->where('status', '=', $jobinfoStatus);
            });
        }

        if (isset($projectType) && $projectType != 'all') {
            $projects->whereHas('project_type', function ($query) use ($projectType) {
                $query->where('id', '=', $projectType);
            });
        }

        if (isset($projectState) && $projectState != 'all') {
            $projects->whereHas('state', function ($query) use ($projectState) {
                $query->where('id', '=', $projectState);
            });
        }

        if (isset($receivableStatus) && $receivableStatus != 'all') {
            $projects->whereHas('project_contract', function ($query) use ($receivableStatus) {
                $query->where('receivable_status', 'LIKE', '%' . $receivableStatus . '%');
            });
        }

        if (isset($claimAmount) && $claimAmount != 'all') {
            if ($claimAmount != '500000+') {
                $ranges = explode('-', $claimAmount);
                $lowerRange = $ranges[0];
                $upperRange = $ranges[1];
                /*$projects->whereHas('project_contract', function ($query) use ($lowerRange,$upperRange) {
                $query->whereBetween('total_claim_amount',[$lowerRange,$upperRange]);
            });*/
                $projects->whereHas('project_contract', function ($query) use ($lowerRange, $upperRange) {
                    $query->whereBetween('total_claim_amount', [$lowerRange, $upperRange]);
                });
            } else {
                $projects->whereHas('project_contract', function ($query) use ($claimAmount) {
                    $query->where('total_claim_amount', '>=', $claimAmount);
                });
            }
        }

        if (isset($customer) && $customer != 'all') {
            $projects->whereHas('customer_contract.contacts', function ($query) use ($customer) {
                $query->where('id', '=', $customer);
            });
        }

        if (isset($projectName) && $projectName != '') {
            $projects->where('project_details.id', '=', $projectName);
        }

        if (isset($user) && $user != 'all') {
        }

        if (isset($complianceManagement) && $complianceManagement != 'default') {
        }

        if (isset($dateType) && $dateType != 'all') {
            $projects->whereHas('project_date.remedyDate.remedy', function ($query) use ($dateType) {
                $query->where('remedy', 'LIKE', '%' . $dateType . '%');
            });
        }

        $fromDate = (isset($from) && $from != '') ? date('Y-m-d', strtotime($from)) : date('Y-m-d', strtotime(''));
        $toDate = (isset($to) && $to != '') ? date('Y-m-d', strtotime($to)) : date('Y-m-d');
        if (isset($from) && isset($to)) {
            // $projects->whereHas('project_detail', function ($query) use ($fromDate,$toDate) {
            //     $query->whereBetween('created_at', [$fromDate,$toDate]);
            // });
            $projects->whereBetween('project_details.created_at', [$fromDate, $toDate]);
        }

        if (isset($complianceProvider) && $complianceProvider != 'all') {
            $projects->whereHas('user.lienProvider.findLien', function ($query) use ($complianceProvider) {
                $query->where('id', '=', $complianceProvider);
            });
        }
        return $projects;
    }

    /**
     * Search option through searchbar.
     * @param $projects$projectStatus
     */
    /**
     * Not 100% sure if this is being used anywhere else for some reason, but
     * it is not needed for the getProjects method. It duplicates the already
     * created functionality within the getProjects method. Leaving it in until
     * I can verify it is not used somewhere else.
     */
    protected function searchProjectsThroughSearchBar($projects)
    {
        $projectDetails = request()->get('projectDetails');
        $sortBy = request()->get('sortBy');
        $sortWith = request()->get('sortWith');
        if (isset($projectDetails)) {
            $projects->where(function ($query1) use ($projectDetails) {
                $query1->Where('project_name', 'LIKE', '%' . $projectDetails . '%')
                    ->orWhere('project_details.created_at', 'LIKE', '%' . date("Y-m-d H:i:s", strtotime($projectDetails)) . '%')
                    ->orWhereHas('state', function ($query) use ($projectDetails) {
                        $query->where('name', 'LIKE', '%' . $projectDetails . '%');
                    })
                    ->orWhereHas('project_type', function ($query) use ($projectDetails) {
                        $query->where('project_type', 'LIKE', '%' . $projectDetails . '%');
                    })
                    ->orWhereHas('customer_contract.getContacts', function ($query) use ($projectDetails) {
                        $query->where('type', '0')->whereHas('mapContactCompany.company', function ($query) use ($projectDetails) {
                            $query->where('company', 'LIKE', '%' . $projectDetails . '%');
                        });
                    })->orWhereHas('customer_contract.getContacts', function ($query) use ($projectDetails) {
                        $query->where('first_name', 'LIKE', '%' . $projectDetails . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $projectDetails . '%')
                            ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', '%' . $projectDetails . '%');
                    });
            });
        }
        if (isset($sortBy) && isset($sortWith)) {
            $projects->orderBy($sortWith, $sortBy);
        } else {
            $projects->orderBy('project_details.created_at', 'DESC');
        }
        return $projects;
    }

    /**
     * Creates the customer dropdown list for project list page
     * @param $customers
     * @return array
     */
    protected function createCustomerList($customers)
    {
        $customerArray = [];
        // foreach ($customers as $customer) {
        //     $customerArray[$customer->id]   =   (!is_null($customer->getCompany) ? $customer->getCompany->company : 'N/A').' ( '.$customer->first_name.' '.$customer->last_name.' )';
        // }
        // return $customerArray;
        $allContacts = Company::pluck('id');

        $customers = MapCompanyContact::whereIn('company_id', $allContacts)
            ->whereHas('getContacts', function ($query) {
                $query->where('type', '0');
            })->with('company')->get();

        foreach ($customers as $customer) {
            $customerArray[$customer->contacts->id]   =   (!is_null($customer->company) ? $customer->company->company : 'N/A') . ' ( ' . $customer->contacts->first_name . ' ' . $customer->contacts->last_name . ' )';
        }
        // dd($customerArray);
        return $customerArray;
    }

    protected function createLienList($lienProviders)
    {
        $lienProvidersArray = [];
        foreach ($lienProviders as $lienProvider) {
            $lienProvidersArray[$lienProvider->findLien->id]   =  (!is_null($lienProvider->findLien) ? $lienProvider->findLien->company : 'N/A') . ' ( ' . $lienProvider->findLien->firstName . ' ' . $lienProvider->findLien->lastName . ' )';
        }
        return $lienProvidersArray;
    }

    /**
     * Gets the remedy date list for the user
     * @param User $user
     * @return array|\Illuminate\Http\RedirectResponse
     */
    protected function getRemedyDate(User $user)
    {
        try {
            $remedyDateArray = [];
            $projects = $user->projects;
            if (!is_null($projects)) {
                foreach ($projects as $project) {
                    $projectDates = $project->project_date;
                    if (!is_null($projectDates)) {
                        foreach ($projectDates as $projectDate) {
                            $remedyDate = $projectDate->remedyDate;
                            if (!is_null($remedyDate)) {
                                $remedy = $remedyDate->remedy;
                                if (!is_null($remedy)) {
                                    $remedyDateArray[$remedy->remedy] = $remedy->remedy;
                                }
                            }
                        }
                    }
                }
                return $remedyDateArray;
            } else {
                return $remedyDateArray;
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (QueryException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Fetch Query parameters to display in advanced search
     * @param Request $request
     * @return array
     */
    protected function getQueryParams(Request $request)
    {
        $projectStatus = $request->input('project_status');
        $jobinfoStatus = $request->input('job_info_status');
        $projectType = $request->input('project_type');
        $projectState = $request->input('project_state');
        $receivableStatus = $request->input('receivable_status');
        $claimAmount = $request->input('claim_amount');
        $customer = $request->input('customers');
        $projectName = $request->input('project_name');
        $user = $request->input('user');
        $complianceManagement = $request->input('compliance_management');
        $dateType = $request->input('date_type');
        $from = $request->input('from');
        $to = $request->input('to');
        $complianceProvider = $request->input('compliance_provider');
        $deadlineCalc = $request->input('calculation_status');

        return [
            'projectStatus'    => (isset($projectStatus) && $projectStatus != '') ? $projectStatus : 'all',
            'jobinfoStatus'    => (isset($jobinfoStatus) && $jobinfoStatus != '') ? $jobinfoStatus : 'all',
            'projectType'   => (isset($projectType) && $projectType != '') ? $projectType : 'all',
            'projectState'  => (isset($projectState) && $projectState != '') ? $projectState : 'all',
            'receivableStatus' => (isset($receivableStatus) && $receivableStatus != '') ? $receivableStatus : 'all',
            'claimAmount'    => (isset($claimAmount) && $claimAmount != '') ? $claimAmount : 'all',
            'customer'     => (isset($customer) && $customer != '') ? $customer : 'all',
            'projectName' => (isset($projectName) && $projectName != '') ? $projectName : '',
            'user' => (isset($user) && $user != '') ? $user : 'all',
            'complianceManagement' => (isset($complianceManagement) && $complianceManagement != '') ? $complianceManagement : 'default',
            'dateType' => (isset($dateType) && $dateType != '') ? $dateType : 'all',
            'from' => (isset($from) && $from != '') ? $from : '',
            'to' => (isset($to) && $to != '') ? $to : '',
            'complianceProvider' => (isset($complianceProvider) && $complianceProvider != '') ? $complianceProvider : 'all',
            'calculation_status' => (isset($deadlineCalc) && !empty($deadlineCalc)) ? $deadlineCalc : 'all'
        ];
    }

    /**
     * Project name autocomplete section.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoComplete(Request $request)
    {
        try {
            $keyword = $request->keyword;
            $datas  = Auth::user()->projects()->where('project_name', 'LIKE', '%' . $keyword . '%')->get(['id', 'project_name'])->take(5);
            $result = [];
            foreach ($datas as $key => $data) {
                $result[$key]['id'] = $data->id;
                $result[$key]['project_name'] = $data->project_name;
            }
            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'data fetch success'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'data'  =>  null,
                'message' => $exception->getMessage()
            ], $exception->getCode());
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success' => false,
                'data'  =>  null,
                'message' => $exception->getMessage()
            ], $exception->getCode());
        } catch (QueryException $exception) {
            return response()->json([
                'success' => false,
                'data'  =>  null,
                'message' => $exception->getMessage()
            ], $exception->getCode());
        }
    }

    /**();
     * Get Create Project Page in member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createProject(Request $request)
    {
        //forget('key');
        $check = UserDetails::where('user_id', Auth::user()->id)->first();

        if (!$check->state_id) {
            //return redirect()->back()->with('error','Please update your profile.');
            echo "<script>alert('Please update your profile.');window.location.href = 'https://app.lienmanager.com/member';</script>";

            //return redirect()->back();
        }
        try {
            $companies = Company::pluck('company', 'id');
            $firstNames = UserDetails::pluck('first_name', 'id');
            $project_id = $request->get('project_id');

            if ($project_id > 0 || $project_id != '') {
                $project = ProjectDetail::find($project_id);
            } else {
                $project = '';
                $project_id = 0;
            }
            $states = $this->getUserStates();

            $roles = ProjectRole::all();
            // $projects = ProjectDetail::where('user_id',Auth::user()->id);
            $types = ProjectType::all();
            $property = PropertyType::all();
            /* $customerContract = Company::where('user_id',Auth::user()->id)
            ->whereHas('getMapCompanyCustomer.getContacts', function ($query) {
            $query->where('type','0');
    })->get();*/

            $allContacts = Company::pluck('id');

            $customerContract = MapCompanyContact::whereIn('company_id', $allContacts)
                ->whereHas('getContacts', function ($query) {
                    $query->where('type', '0');
                })->with('company')->get();


            $industryContract = MapCompanyContact::whereIn('company_id', $allContacts)
                ->whereHas('getContacts', function ($query) {
                    $query->where('type', '1');
                })->with(['company', 'contacts'])->get();
            /* $customerContract = Contact::where('type', '0')
            ->where('user_id', Auth::user()->id)->get();
            $industryContract = Contact::where('type', '1')
            ->where('user_id', Auth::user()->id)->get();*/
            $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->get();
            // $project_dviewProjectocument_view_claim_form_2=ClaimDataForm::where('project_id',$project_id)->get();
            // $data1[]=$project_document_view_claim_form_1->toArray();
            // $data2[]=$project_document_view_claim_form_2->toArray();
            // $claim_form[]=array_merge($data1,$data2);
            //dd($claim_form);
            $userPreferences = UserPreferences::where('user_id', Auth::user()->id)->first();
            $customerCodes = CustomerCode::get();
            $liens = '';
            $remedyNames = [];
            if (isset($_GET['edit'])) {
                $liens = LienLawSlideChart::where('state_id', $project->state_id)
                    ->where('project_type', $project->project_type_id)->get();
                $remedyNames = [];
                foreach ($liens as $lien) {
                    $remedyNames[] = $lien->remedy;
                }
            }

            if (empty($project) || $project->user_id == Auth::id()) {
                return view('basicUser.projects.project_details', [
                    // return view('basicUser.projects.test_project_details', [
                    'states' => $states,
                    'roles' => $roles,
                    'types' => $types,
                    'properties' => $property,
                    'customerContracts' => $customerContract,
                    'industryContracts' => $industryContract,
                    'project' => $project,
                    'selected_project' => $project,
                    'project_id' => $project_id,
                    'project_document' => $project_document_view_claim_form,
                    'companies' => $companies,
                    'first_names' => $firstNames,
                    'preferences' => $userPreferences,
                    'customerCodes' => $customerCodes,
                    'remedyNames' => array_unique($remedyNames),
                    'liens' => $liens
                ]);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            //return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }
    public function createExpressProject(Request $request)
    {
        $check = UserDetails::where('user_id', Auth::user()->id)->first();

        if (!$check->state_id) {
            //return redirect()->back()->with('error','Please update your profile.');
            echo "<script>alert('Please update your profile.');window.location.href = 'https://app.lienmanager.com/member';</script>";

            //return redirect()->back();
        }
        try {
            $companies = Company::pluck('company', 'id');
            $firstNames = UserDetails::pluck('first_name', 'id');
            $project_id = $request->get('project_id');

            if ($project_id > 0 || $project_id != '') {
                $project = ProjectDetail::find($project_id);
            } else {
                $project = '';
                $project_id = 0;
            }
            $states = $this->getUserStates();

            $roles = ProjectRole::all();
            $projects = ProjectDetail::where('user_id', Auth::user()->id);
            $types = ProjectType::all();
            $property = PropertyType::all();
            /* $customerContract = Company::where('user_id',Auth::user()->id)
            ->whereHas('getMapCompanyCustomer.getContacts', function ($query) {
            $query->where('type','0');
    })->get();*/

            $allContacts = Company::pluck('id');

            $customerContract = MapCompanyContact::whereIn('company_id', $allContacts)
                ->whereHas('getContacts', function ($query) {
                    $query->where('type', '0');
                })->with('company')->get();


            $industryContract = MapCompanyContact::whereIn('company_id', $allContacts)
                ->whereHas('getContacts', function ($query) {
                    $query->where('type', '1');
                })->with(['company', 'contacts'])->get();
            /* $customerContract = Contact::where('type', '0')
            ->where('user_id', Auth::user()->id)->get();
            $industryContract = Contact::where('type', '1')
            ->where('user_id', Auth::user()->id)->get();*/
            $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->get();
            // $project_dviewProjectocument_view_claim_form_2=ClaimDataForm::where('project_id',$project_id)->get();
            // $data1[]=$project_document_view_claim_form_1->toArray();
            // $data2[]=$project_document_view_claim_form_2->toArray();
            // $claim_form[]=array_merge($data1,$data2);
            //dd($claim_form);
            $userPreferences = UserPreferences::where('user_id', Auth::user()->id)->first();
            $customerCodes = CustomerCode::get();
            $liens = '';
            $remedyNames = [];
            if (isset($_GET['edit'])) {
                $liens = LienLawSlideChart::where('state_id', $project->state_id)
                    ->where('project_type', $project->project_type_id)->get();
                $remedyNames = [];
                foreach ($liens as $lien) {
                    $remedyNames[] = $lien->remedy;
                }
            }

            if (empty($project) || $project->user_id == Auth::id()) {


                return view('basicUser.projects.express_project_details', [
                    // return view('basicUser.projects.test_project_details', [
                    'states' => $states,
                    'roles' => $roles,
                    'types' => $types,
                    'properties' => $property,
                    'customerContracts' => $customerContract,
                    'industryContracts' => $industryContract,
                    'project' => $project,
                    'selected_project' => $project,
                    'project_id' => $project_id,
                    'project_document' => $project_document_view_claim_form,
                    'companies' => $companies,
                    'first_names' => $firstNames,
                    'preferences' => $userPreferences,
                    'customerCodes' => $customerCodes,
                    'remedyNames' => array_unique($remedyNames),
                    'liens' => $liens
                ]);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            //return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }
    public function expressDashboard()
    {
        Session::put('express', 123);
        return view('basicUser.projects.express_dashboard');
    }

    public function projectContacts($project_id)
    {
        try {
            $companies = Company::pluck('company', 'id');
            $firstNames = UserDetails::pluck('first_name', 'id');
            $project_id = $project_id;

            if ($project_id > 0 || $project_id != '') {
                $project = ProjectDetail::find($project_id);
            } else {
                $project = '';
                $project_id = 0;
            }
            // $states = State::all();
            $states = $this->getUserStates();
            $roles = ProjectRole::all();
            // $projects = ProjectDetail::where('user_id',Auth::user()->id);
            $types = ProjectType::all();
            $property = PropertyType::all();
            /* $customerContract = Company::where('user_id',Auth::user()->id)
            ->whereHas('getMapCompanyCustomer.getContacts', function ($query) {
            $query->where('type','0');
    })->get();*/

            $allContacts = Company::pluck('id');

            $customerContract = MapCompanyContact::where('user_id', Auth::user()->id)
                ->whereHas('getContacts', function ($query) {
                    $query->where('type', '0');
                })->with('company')->get();

            $industryContract = MapCompanyContact::where('user_id', Auth::user()->id)
                ->whereHas('getContacts', function ($query) {
                    $query->where('type', '1');
                })->with(['company', 'contacts'])->get();


            /* $customerContract = Contact::where('type', '0')
            ->where('user_id', Auth::user()->id)->get();
            $industryContract = Contact::where('type', '1')
            ->where('user_id', Auth::user()->id)->get();*/
            $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->get();
            // $project_dviewProjectocument_view_claim_form_2=ClaimDataForm::where('project_id',$project_id)->get();
            // $data1[]=$project_document_view_claim_form_1->toArray();
            // $data2[]=$project_document_view_claim_form_2->toArray();
            // $claim_form[]=array_merge($data1,$data2);
            //dd($claim_form);
            $userPreferences = UserPreferences::where('user_id', Auth::user()->id)->first();
            $customerCodes = CustomerCode::get();
            $liens = '';
            $remedyNames = [];
            if (isset($_GET['edit'])) {
                $liens = LienLawSlideChart::where('state_id', $project->state_id)
                    ->where('project_type', $project->project_type_id)->get();
                $remedyNames = [];
                foreach ($liens as $lien) {
                    $remedyNames[] = $lien->remedy;
                }
            }

            if (empty($project) || $project->user_id == Auth::id()) {
                return view('basicUser.projects.project_contacts', [
                    'states' => $states,
                    'roles' => $roles,
                    'types' => $types,
                    'properties' => $property,
                    'customerContracts' => $customerContract,
                    'industryContracts' => $industryContract,
                    'project' => $project,
                    'project_id' => $project_id,
                    'selected_project' => $project,
                    'project_document' => $project_document_view_claim_form,
                    'companies' => $companies,
                    'first_names' => $firstNames,
                    'preferences' => $userPreferences,
                    'customerCodes' => $customerCodes,
                    'remedyNames' => array_unique($remedyNames),
                    'liens' => $liens
                ]);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            //return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    public function projectSaveSession(Request $request)
    {
        $code = $message = $status = 0;
        try {
            $request->session()->put('projectName', $request->project_name);
            $request->session()->put('role', $request->role);
            $request->session()->put('projectType', $request->type);
            $request->session()->put('customer', $request->customer);
            $request->session()->put('state', $request->state);
            if ($request->customer != '') {
                $customer = CustomerCode::findOrFail($request->customer);
                if ($customer != '') {
                    $request->session()->put('customer_name', $customer->name);
                }
            }
            $request->session()->save();
            $code = 200;
            $message = 'Saved in session';
            $status = true;
        } catch (\Exception $e) {
            $code = 500;
            $message = $e->getMessage();
            $status = false;
        } catch (ModelNotFoundException $ex) {
            $code = 500;
            $message = $ex->getMessage();
            $status = false;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }

    /**
     * Get view Project Page in member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewProject()
    {
        try {
            $project_id = request()->get('project_id');
            $flag = 0;

            if ($project_id > 0 || $project_id != '') {
                $project = ProjectDetail::find($project_id);
                $remedy = Remedy::where('state_id', $project->state_id)
                    ->where('project_type_id', $project->project_type_id);
                $tiers = TierTable::where('role_id', $project->role_id)
                    ->where('customer_id', $project->customer_id)->firstOrFail();
                $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
                $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
                    ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
                    ->whereIn('answer1', [$project->answer1, ''])
                    ->whereIn('answer2', [$project->answer2, '']);
                $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
                $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();

                $tasks = ProjectTask::where('project_id', $project_id)->get();
                $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->first();
                $project_document_credit_application = CreditApplication::where('project_id', $project_id)->first();
                $project_document_joint = JointPaymentAuthorization::where('project_id', $project_id)->first();
                $project_document_waver = UnconditionalWaiverProgress::where('project_id', $project_id)->first();
                $project_document_job_info = JobInfo::where('project_id', $project_id)->first();
                $project_documents[0]['name'] = 'job info sheet';
                $project_documents[0]['data'] = $project_document_job_info;
                $project_documents[1]['name'] = 'Claim form and project data sheet';
                $project_documents[1]['data'] = $project_document_view_claim_form;
                $project_documents[2]['name'] = 'Credit Application';
                $project_documents[2]['data'] = $project_document_credit_application;
                $project_documents[3]['name'] = 'joint payment authorization';
                $project_documents[3]['data'] = $project_document_joint;
                $project_documents[4]['name'] = 'Waver progress';
                $project_documents[4]['data'] = $project_document_waver;
                $remedy1 = Remedy::where('state_id', $project->state_id)
                    ->where('project_type_id', $project->project_type_id);
                $remedyDate1 = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy1->pluck('id'));
                $role_id = ProjectDetail::where('id', $project_id);
                $answer = $role_id->first()->answer1;
                if ($answer == 'Yes' || $answer == 'Commercial') {
                    $flag = 1;
                } elseif ($answer == 'No' || $answer == 'Residential') {
                    $flag = 2;
                }
                if ($flag == 0) {
                    $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                    $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                    $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                        ->whereIn('remedy_id', $remedy->pluck('id'));
                    $deadline = $deadline1->whereIn('id', $tierRem->pluck('remedy_step_id'))->get();
                    $emails = ProjectEmail::select('project_emails')
                        ->where('project_id', $project->id)->get();
                } elseif ($flag == 1) {
                    $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                    $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                    if ($answer == 'Yes') {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'Yes')
                                ->orWhere('answer1', '');
                        });
                    } else {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'Commercial')
                                ->orWhere('answer1', '');
                        });
                    }
                    $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                        ->whereIn('remedy_id', $remedy->pluck('id'));
                    $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                    $emails = ProjectEmail::select('project_emails')
                        ->where('project_id', $project->id)->get();
                } elseif ($flag == 2) {
                    $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                    $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                    if ($answer == 'No') {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'No')
                                ->orWhere('answer1', '');
                        });
                    } else {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'Residential')
                                ->orWhere('answer1', '');
                        });
                    }
                    $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                        ->whereIn('remedy_id', $remedy->pluck('id'));

                    $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                    $emails = ProjectEmail::select('project_emails')
                        ->where('project_id', $project->id)->get();
                }
                // dd($deadline);
                foreach ($deadline as $key => $value) {
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
                $projectDates = [];
                foreach ($project->project_date as $date) {
                    if ($date->date_value != '') {
                        $dateFormat = \DateTime::createFromFormat('Y-m-d', $date->date_value);
                        $formattedDate = $dateFormat->format('m/d/Y');
                    } else {
                        $formattedDate = $date->date_value;
                    }

                    $projectDates[$date->date_id] = $formattedDate;
                }

                $projectContactsCompany = ProjectIndustryContactMap::where('projectId', $project_id)->pluck('contactId');
                $companyContacts = MapCompanyContact::whereIn('id', $projectContactsCompany)->get();
                $count = 0;
                $projectContacts = [];
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
            } else {
                $project = '';
                $project_id = 0;
                $projectContacts = [];
            }
            // $states = State::all();
            $states = $this->getUserStates();

            $roles = ProjectRole::all();
            $types = ProjectType::all();
            $property = PropertyType::all();
            $customerContract = Contact::where('type', '0')
                ->where('user_id', Auth::user()->id)->get();
            $industryContract = Contact::where('type', '1')
                ->where('user_id', Auth::user()->id)->get();
            $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->get();
            $liens = LienLawSlideChart::where('state_id', $project->state_id)
                ->where('project_type', $project->project_type_id)->get();
            $remedyNames = [];
            foreach ($liens as $lien) {
                $remedyNames[] = $lien->remedy;
            }
            $projectDet = ProjectDetail::where('id', $project_id);
            $tierQ = TierTable::where('role_id', $projectDet->pluck('role_id'))->where('customer_id', $projectDet->pluck('customer_id'))->pluck('id');
            $projectQuestion = RemedyQuestion::whereIn('state_id', $projectDet->pluck('state_id'))
                ->where('tier_id', $tierQ)
                ->get();
            $user = User::findOrFail(Auth::user()->id);
            $projectDatesStored = [];
            $projectDateID = [];
            $enteredDates = [];
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
                $projectDatesStored[$date->id] = $formattedDate;
                $projectDateID[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id, 'value' => $formattedDate];
                $enteredDates[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id];
            }
            foreach ($remedyDate as $date) {
                $dateFields[$date->id] = ['id' => $date->id, 'name' => $date->date_name, 'recurring' => $date->recurring, 'dates' => []];
                foreach ($datesEntered as $value) {
                    if ($value['remedy'] == $date->id) {
                        $dateFields[$date->id]['dates'] += [$value['id'] => ['id' => $value['id'], 'remedy' => $value['remedy'], 'value' => $value['value'], 'recurring' => $date->recurring]];
                    }
                }
            }
            return view('basicUser.projects.project_view', [
                'projectDateField' => $dateFields,
                'dateId' => $datesEntered,
                'states' => $states,
                'roles' => $roles,
                'types' => $types,
                'dates' => $remedyDate,
                'properties' => $property,
                'customerContracts' => $customerContract,
                'industryContracts' => $industryContract,
                'contract' => $project->project_contract,
                'project' => $project,
                'tasks' => $tasks,
                'projectDates' => $projectDates,
                'project_id' => $project_id,
                'flag' => $flag,
                'project_documents' => $project_documents,
                'project_document' => $project_document_view_claim_form,
                'deadlines' => $deadline,
                'daysRemain' => $daysRemain,
                'remedyNames' => array_unique($remedyNames),
                'project_emails' => $emails,
                'liens' => $liens,
                'user' => $user,
                'projectContacts' => $projectContacts,
                'question' => $projectQuestion
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }
    /**
     * Returns project information and view for mobile
     * @param  int $projectId the project id number
     * @return Illuminate\View\View
     */
    public function viewProjectMobile($projectId)
    {
        $id = $projectId;
        $projectDetails = ProjectDetail::where('id', $id)->get();
        $state = State::where('id', $projectDetails->pluck('state_id'))->get();
        $type = ProjectType::where('id', $projectDetails->pluck('project_type_id'))->first();
        return view('basicUser.projects.project_overview_mobile', [
            'id' => $id,
            'project' => $projectDetails,
            'state' => $state,
            'type' => $type
        ]);
    }

    /**
     * Get view Project Page in member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewLienProject()
    {
        try {
            $project_id = request()->get('project_id');
            $flag = 0;

            if ($project_id > 0 || $project_id != '') {
                $project = ProjectDetail::find($project_id);
                $remedy = Remedy::where('state_id', $project->state_id)
                    ->where('project_type_id', $project->project_type_id);
                $tasks = ProjectTask::where('project_id', $project_id)->get();
                foreach ($tasks as $key=>$value) {
                    if(isset($value->job_file_id) && !empty($value->job_file_id)) {
                        $JobInfoFiles = JobInfoFiles::where('id',$value->job_file_id)->first();
                        $tasks[$key]['file_link'] = $JobInfoFiles->file;
                    }
                }
                $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->get();

                $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->first();
                $project_document_credit_application = CreditApplication::where('project_id', $project_id)->first();
                $project_document_joint = JointPaymentAuthorization::where('project_id', $project_id)->first();
                $project_document_waver = UnconditionalWaiverProgress::where('project_id', $project_id)->first();
                $project_document_job_info = JobInfo::where('project_id', $project_id)->first();
                $project_documents[0]['name'] = 'job info sheet';
                $project_documents[0]['data'] = $project_document_job_info;
                $project_documents[1]['name'] = 'Claim form and project data sheet';
                $project_documents[1]['data'] = $project_document_view_claim_form;
                $project_documents[2]['name'] = 'Credit Application';
                $project_documents[2]['data'] = $project_document_credit_application;
                $project_documents[3]['name'] = 'joint payment authorization';
                $project_documents[3]['data'] = $project_document_joint;
                $project_documents[4]['name'] = 'Waver progress';
                $project_documents[4]['data'] = $project_document_waver;
                $remedy1 = Remedy::where('state_id', $project->state_id)
                    ->where('project_type_id', $project->project_type_id);
                $remedyDate1 = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy1->pluck('id'));
                $role_id = ProjectDetail::where('id', $project_id);
                $answer = $role_id->first()->answer1;
                if ($answer == 'Yes' || $answer == 'Commercial') {
                    $flag = 1;
                } elseif ($answer == 'No' || $answer == 'Residential') {
                    $flag = 2;
                }
                if ($flag == 0) {
                    $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                    $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                    $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                        ->whereIn('remedy_id', $remedy->pluck('id'));
                    $deadline = $deadline1->whereIn('id', $tierRem->pluck('remedy_step_id'))->get();
                    $emails = ProjectEmail::select('project_emails')
                        ->where('project_id', $project->id)->get();
                } elseif ($flag == 1) {
                    $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                    $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                    if ($answer == 'Yes') {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'Yes')
                                ->orWhere('answer1', '');
                        });
                    } else {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'Commercial')
                                ->orWhere('answer1', '');
                        });
                    }
                    $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                        ->whereIn('remedy_id', $remedy->pluck('id'));
                    $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                    $emails = ProjectEmail::select('project_emails')
                        ->where('project_id', $project->id)->get();
                } elseif ($flag == 2) {
                    $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                    $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                    if ($answer == 'No') {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'No')
                                ->orWhere('answer1', '');
                        });
                    } else {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'Residential')
                                ->orWhere('answer1', '');
                        });
                    }
                    $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                        ->whereIn('remedy_id', $remedy->pluck('id'));
                    $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                    $emails = ProjectEmail::select('project_emails')
                        ->where('project_id', $project->id)->get();
                }
                // dd($deadline);
                $daysRemain = [];
                $remedyNames = [];

                foreach ($deadline as $key => $value) {
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
                        $daysRemain[$key]['deadline'] = $preliminaryDeadline->date_value;
                        $daysRemain[$key]['preliminaryDates'] = date('Y-m-d', strtotime($daysRemain[$key]['deadline'] . '+' . $daysRemain[$key]['dates'] . ' days'));
                    } else {
                        $daysRemain[$key]['deadline'] = 'N/A';
                        $daysRemain[$key]['preliminaryDates'] = 'N/A';
                    }
                    $remedyNames[$value->getRemedy->id] = $value->getRemedy->remedy;
                }
                $projectDates = [];
                foreach ($project->project_date as $date) {
                    if ($date->date_value != '') {
                        $dateFormat = \DateTime::createFromFormat('Y-m-d', $date->date_value);
                        $formattedDate = $dateFormat->format('m/d/Y');
                    } else {
                        $formattedDate = $date->date_value;
                    }

                    $projectDates[$date->date_id] = $formattedDate;
                }

                $projectContactsCompany = ProjectIndustryContactMap::where('projectId', $project_id)->pluck('contactId');
                $companyContacts = MapCompanyContact::whereIn('id', $projectContactsCompany)->get();
                $count = 0;
                $projectContacts = [];
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
            } else {
                $project = '';
                $project_id = 0;
                $projectContacts = [];
            }
            // $states = State::all();
            $states = $this->getUserStates();
            $contacts = [];

            $roles = ProjectRole::all();
            $types = ProjectType::all();
            $property = PropertyType::all();
            $customerContract = Contact::where('type', '0')
                ->where('user_id', Auth::user()->id)->get();
            $industryContract = Contact::where('type', '1')
                ->where('user_id', Auth::user()->id)->get();
            $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->get();
            $liens = LienLawSlideChart::where('state_id', $project->state_id)
                ->where('project_type', $project->project_type_id)->get();
            $remedyLien = [];
            foreach ($liens as $lien) {
                $remedyLien[] = $lien->remedy;
            }
            $tierQ = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
            $tierQID = $tierQ->pluck('id')->first();
//            $tierId = TierRemedyStep::where('tier_id', $tierQID)->first();
            $state_id = $remedy->pluck('state_id');
            $projectQuestion = RemedyQuestion::whereIn('state_id', $state_id)
                ->where('tier_id', $tierQID)
                ->get()->toArray();
            $user = User::findOrFail(Auth::user()->id);
            $projectOwner = User::findOrFail($project->user_id);
            $projectOwnerDetails = UserDetails::where('user_id', $project->user_id)->first();
            $projectOwnerDetailsState = null;
            if(isset($projectOwnerDetails->state_id) && !empty($projectOwnerDetails->state_id)) {
                $projectOwnerDetailsState = State::where('id', $projectOwnerDetails->state_id)->first();
            }


            return view('lienProviders.projects.project_view', [
                'states' => $states,
                'roles' => $roles,
                'types' => $types,
                'dates' => $remedyDate,
                'properties' => $property,
                'customerContracts' => $customerContract,
                'industryContracts' => $industryContract,
                'contract' => $project->project_contract,
                'project' => $project,
                'projectOwner' => $projectOwner,
                'projectOwnerDetails' => $projectOwnerDetails,
                'projectOwnerDetailsState' => $projectOwnerDetailsState,
                'tasks' => $tasks,
                'projectDates' => $projectDates,
                'project_id' => $project_id,
                'flag' => $flag,
                'project_documents' => $project_documents,
                'project_document' => $project_document_view_claim_form,
                'deadlines' => $deadline,
                'daysRemain' => $daysRemain,
                'remedyNames' => array_unique($remedyNames),
                'remedyLien' => array_unique($remedyLien),
                'project_emails' => $emails,
                'liens' => $liens,
                'user' => $user,
                'projectContacts' => $projectContacts,
                'question' => array_unique($projectQuestion)
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }


    /**
     * Get Tier combination for selected state,project type an  d role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkProjectRoleOld(Request $request)
    {
        $remedy = Remedy::where('state_id', $request->state)
            ->where('project_type_id', $request->project_type);
        $remedyStep = RemedyStep::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'));
        $tierRemedyStep = TierRemedyStep::whereIn('remedy_step_id', $remedyStep->pluck('id'))
            ->whereIn('tier_id', $tier->pluck('id'));
        $customers = TierTable::whereIn('id', $tierRemedyStep->pluck('tier_id'))->with('customer')->get();
        if (count($customers) > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Role Found',
                'data' => $customers
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Role Found',
            ], 200);
        }
    }

    /**
     * Get Tier combination for selected state,project type and role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkProjectName(Request $request)
    {
        try {
            $all = ProjectDetail::pluck('project_name')->toArray();
            $projectname = $request->projectname;

            if (in_array($projectname, $all)) {
                $number = 1;

                echo $this->checkProjects($projectname, $number);
            } else {
                echo $projectname;
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Get Tier combination for selected state,project type and role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function checkProjects($projectname, $number)
    {
        while ($number >= 1) {
            $number = $number + 1;
            $all = ProjectDetail::pluck('project_name')->toArray();
            $projectnamen = $projectname . "(" . $number . ")";
            Log::info($projectnamen);
            if (!in_array($projectnamen, $all)) {
                return $projectnamen;
            }
        }
    }

    /**
     * Get Tier combination for selected state,project type and role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function checkProjectRole(Request $request)
    {
        //$remedy = Remedy::all();
        $remedy = Remedy::where('state_id', $request->state)
            ->where('project_type_id', $request->project_type);
        $remdey_date = RemedyDate::whereIn('remedy_id', $remedy->pluck('id'));
        $remedyStep = RemedyStep::whereIn('remedy_date_id', $remdey_date->pluck('id'));
        $tier = TierTable::where('role_id', $request->role);
        $tierRemedyStep = TierRemedyStep::whereIn('remedy_step_id', $remedyStep->pluck('id'))->whereIn('tier_id', $tier->pluck('id'));
        $customers = TierTable::whereIn('id', $tierRemedyStep->pluck('tier_id'))->with('customer')->get();
        $state = State::where('id', $request->state)->first();
        $type = ProjectType::where('id', $request->project_type)->first();
        $role = ProjectRole::where('id', $request->role)->first();
        if (count($customers) > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Role Found',
                'data' => $customers
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Customer Found for state: ' . $state->name . ' project type: ' . $type->project_type . ' and for the role of ' . $role->project_roles . '.',
            ], 200);
        }
    }


    /**
     * Get all The Date fields for a project in member
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function searchProjectConstructionMonitor(Request $request)
    {
        $url = "https://api.constructionmonitor.com/v2/permits/?term=" . $request->search_term . "&pageLimit=100";
        // dd($url);
        $token = env('CONSTRUCTION_MONITOR');
        $client = new GuzzleHttp\Client(['headers' => ['Authorization' => $token]]);
        $response = $client->get($url);

        $results = $response->getBody();
        $results = json_decode($results);

        return response()->json([
            'status' => true,
            'data' => $results->hits->hits,
            'message' => 'Projects Found ',
            'count' => count($results->hits->hits),
        ], 200);
        // dd(($results->hits->hits));
    }

    private function createCompany($company, $link_company, $projectDetail, $state)
    {
        if ($company == null) {
            $company = new Company();
            if ($link_company != null) {
                $company->user_id = $projectDetail->user_id;
                $company->company = $link_company['name'];
                $company->website = isset($link_company['web']) ? $link_company['web'] : '';
                $company->address = isset($link_company['address']) ? $link_company['address'] : '';
                $company->city = isset($link_company['city']) ? $link_company['city'] : '';
                $company->state_id = $state->id;
                $company->zip = isset($link_company['zip']) ? $link_company['zip'] : '';
                $company->phone = isset($link_company['phone']) ? $link_company['phone'] : '';
                $company->fax = isset($link_company['fax']) ? $link_company['fax'] : null;
                $company->save();
            }
        }
        return $company;
    }

    private function createContact($contact, $company, $contactType, $link, $projectDetail)
    {
        if ($contactType == 'industry') {
            $link_contact = $link['contact'];
        } else {
            $link_contact = $link['person'];
        }

        if ($link_contact !=  null) {
            if ($contact == null) {
                $contact = new CompanyContact();
                $contact->user_id = $projectDetail->user_id;
                if ($contactType == 'industry') {
                    $contact->contact_type = $link['companylinktype'];
                    $contact->type = '1';
                } else {
                    $contact->type = '0';
                    $contact->contact_type = $link['personlinktype'];
                }
                $contact->title = '';
                // $contact->title_other = $link_contact['title_other'];
                $contact->first_name = $link_contact['firstname'];
                $contact->last_name = $link_contact['lastname'];
                $contact->email = $link_contact['email'];
                $contact->phone = $link_contact['phone'];
                // $contact->cell = $link_contact['cell'];
                $contact->save();

                $map = MapCompanyContact::where('company_contact_id', $contact->id)->where('company_id', $company->id)->first();
                if (!isset($map)) {
                    $map = new MapCompanyContact();
                }
                $map->company_contact_id = $contact->id;
                $map->company_id = $company->id;
                $map->address = $company->address;
                $map->city = $company->city;
                $map->state_id = $company->state_id;
                $map->zip = $company->zip;
                $map->phone = $company->phone;
                $map->fax = $company->fax;
                $map->save();
                if ($contactType == 'industry') {
                    $projectContact = new ProjectIndustryContactMap();
                    $projectContact->projectId = $projectDetail->id;
                    $projectContact->contactId = $map->id;
                    $projectContact->save();
                } else {
                    $project = ProjectDetail::findOrFail($projectDetail->id);
                    $project->customer_contract_id = $map->id;
                    $project->save();
                }
            } else {
                // $map = MapCompanyContact::where('company_contact_id', $contact->id)->where->('company_id',$company->id)->first();
                $map = MapCompanyContact::where('company_contact_id', $contact->id)->where('company_id', $company->id)->first();

                if (!isset($map)) {
                    $map = new MapCompanyContact();
                }
                $map->company_contact_id = $contact->id;
                $map->company_id = $company->id;
                $map->address = $company->address;
                $map->city = $company->city;
                $map->state_id = $company->state_id;
                $map->zip = $company->zip;
                $map->phone = $company->phone;
                $map->fax = $company->fax;
                $map->save();
                if ($contactType == 'industry') {
                    $projectContact = new ProjectIndustryContactMap();
                    $projectContact->projectId = $projectDetail->id;
                    $projectContact->contactId = $map->id;
                    $projectContact->save();
                } else {
                    $project = ProjectDetail::findOrFail($projectDetail->id);
                    $project->customer_contract_id = $map->id;
                    $project->save();
                }
            }
        }
        return $contact;
    }


    /**
     * Get all The Date fields for a project in member
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function createProjectConstructionMonitor(Request $request)
    {
        $source = $request->data['_source'];
        $county = $source['county'];
        $projectDetail = ProjectDetail::findOrFail($request->project_id);

        $state = State::where('short_code', $source['state'])->first();
        $projectDetail->state_id = $state->id;
        $projectDetail->project_type_id = 1;
        $projectDetail->city = $source['city'];
        $projectDetail->zip = $source['zip'];
        $projectDetail->address = $source['siteaddresspartial'];
        $projectDetail->county = $county['county_name'];

        $project_id = $projectDetail->update();

        $companyLinks = isset($source['companylinks']) ? $source['companylinks'] : [];
        $personLinks = isset($source['personlinks']) ? $source['personlinks'] : [];
        foreach ($companyLinks as $link) {
            $link_company = $link['company'];
            $link_contact = $link['contact'];
            $company = Company::where('company', $link_company['name'])->first();
            // dd($link);
            $contact = null;
            if ($link_contact != null) {
                $contact = CompanyContact::where('first_name', $link_contact['firstname'])->where('last_name', $link_contact['lastname'])->first();
            }
            $company = $this->createCompany($company, $link_company, $projectDetail, $state);
            $contact = $this->createContact($contact, $company, 'industry', $link, $projectDetail);
        }

        $customerCompany = Company::findOrFail(18)->first();
        foreach ($personLinks as $pLink) {
            $personDetail = $pLink['person'];

            $person = CompanyContact::where('first_name', $personDetail['firstname'])->where('last_name', $personDetail['lastname'])->first();

            $contact = $this->createContact($person, $customerCompany, 'customer', $pLink, $projectDetail);
        }

        if ($projectDetail->id) {
            return response()->json([
                'status' => true,
                'data' => $projectDetail->id,
                'message' => 'Projects Updated'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'data' => 'nothing',
                'message' => 'Projects not saved'
            ], 200);
        }
    }

    /**
     * Get all The Date fields for a project in member
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */

    public function getProjectDate(Request $request)
    {
        try {
            $remedy = Remedy::where('state_id', $request->state)
                ->where('project_type_id', $request->project_type);

            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();

            if (count($remedyDate) > 0) {
                return view('basicUser.ajax.projectDate', [
                    'dates' => $remedyDate
                ]);
            } else {
                return 'No Date Found';
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Find Question and Answer for a particular combination.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function projectCheckQuestion(Request $request)
    {
        $remedy = Remedy::where('state_id', $request->state)
            ->where('project_type_id', $request->project_type);
        $tier = TierTable::where('role_id', $request->role)
            ->where('customer_id', $request->customer)->firstOrFail();
        $remedyQuestions = RemedyQuestion::whereIn('state_id', $remedy->pluck('state_id'))
            ->where('project_type_id', $request->project_type)
            ->where('tier_id', $tier->id)
            ->orderBy('question_order')->get();
        $questions = [];
        if (count($remedyQuestions) > 0) {
            foreach ($remedyQuestions as $key => $remedyQuestion) {
                if ($remedyQuestion->answer != '') {
                    $answer = explode('|', $remedyQuestion->answer);
                    $questions[$key]['question'] = $remedyQuestion->question;
                    $questions[$key]['answer'] = $answer;
                    $questions[$key]['id'] = $remedyQuestion->id;
                }
            }
        }
        if (count($questions) > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Question Found',
                'data' => $questions
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Question',
            ], 200);
        }
    }

    public function submitProjectContacts(Request $request)
    {
        try {
            if ($request->form_type == 'add') {
                $projectDetail = new ProjectDetail();
                $projectDetail->user_id = Auth::user()->id;
            } else {
                $projectDetail = ProjectDetail::findOrFail($request->project_id);
            }

            if ($request->form_type === 'contactOnly') {
                $projectDetail->customer_contract_id = $request->customer_contract;
                $projectDetail->update();
            }

            Session::forget('projectName');
            Session::forget('role');
            Session::forget('projectType');
            Session::forget('customer');
            Session::forget('state');
            Session::forget('customer_name');

            if (isset($request->industry_contract) && count($request->industry_contract)) {
                if (!empty($request->industry_contract)) {
                    ProjectIndustryContactMap::where('projectId', $projectDetail->id)->delete();
                    foreach ($request->industry_contract as $contacts) {
                        if (ProjectIndustryContactMap::where('projectId', $projectDetail->id)->where('contactId', $contacts)->first()) {
                            $newContacts = ProjectIndustryContactMap::where('projectId', $projectDetail->id)->where('contactId', $contacts)->first();
                            $newContacts->projectId = $projectDetail->id;
                            $newContacts->contactId = $contacts;
                            $newContacts->update();
                        } else {
                            $newContacts = new ProjectIndustryContactMap();
                            $newContacts->projectId = $projectDetail->id;
                            $newContacts->contactId = $contacts;
                            $newContacts->save();
                        }
                    }
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Details Saved successfully',
                'project_id' => $projectDetail->id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Submit Project Details Form step 1 for add or edit Project Details
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function submitProjectDetails(Request $request)
    {
        if(!isset($request->project_name) || empty($request->project_name)) {
            return response()->json([
                'status' => false,
                'message' => 'The field Project Name cannot be empty!'
            ], 200);
        }
        if(!isset($request->state) || empty($request->state)) {
            return response()->json([
                'status' => false,
                'message' => 'The field Location By State cannot be empty!'
            ], 200);
        }
        if(!isset($request->type) || empty($request->type)) {
            return response()->json([
                'status' => false,
                'message' => 'The field Project Type cannot be empty!'
            ], 200);
        }
        if(!isset($request->role) || empty($request->role)) {
            return response()->json([
                'status' => false,
                'message' => 'The field Role cannot be empty!'
            ], 200);
        }
        if(!isset($request->customer) || empty($request->customer)) {
            return response()->json([
                'status' => false,
                'message' => 'The field Customer cannot be empty!'
            ], 200);
        }

        try {
            if ($request->preferences === 'saveSettings') {
                $userSettings = UserPreferences::where('user_id', Auth::user()->id)->first();
                if ($userSettings === null) {
                    $saveSettings = new UserPreferences();
                    $saveSettings->user_id = Auth::user()->id;
                    $saveSettings->project_name = $request->project_name;
                    $saveSettings->state_id = $request->state;
                    $saveSettings->project_type_id = $request->type;
                    $saveSettings->role_id = $request->role;
                    $saveSettings->customer_id = $request->customer;
                    if (isset($request->answer) && count($request->answer) > 0) {
                        foreach ($request->answer as $key => $value) {
                            $question = RemedyQuestion::find($key);
                            if ($question->question_order == 1) {
                                $saveSettings->answer1 = $value;
                            } else {
                                $saveSettings->answer2 = $value;
                            }
                        }
                    } else {
                        $saveSettings->answer1 = null;
                        $saveSettings->answer2 = null;
                    }
                    $saveSettings->save();
                } else {
                    $userSettings->user_id = Auth::user()->id;
                    $userSettings->project_name = $request->project_name;
                    $userSettings->state_id = $request->state;
                    $userSettings->project_type_id = $request->type;
                    $userSettings->role_id = $request->role;
                    $userSettings->customer_id = $request->customer;
                    if (isset($request->answer) && count($request->answer) > 0) {
                        foreach ($request->answer as $key => $value) {
                            $question = RemedyQuestion::find($key);
                            if ($question->question_order == 1) {
                                $userSettings->answer1 = $value;
                            } else {
                                $userSettings->answer2 = $value;
                            }
                        }
                    } else {
                        $userSettings->answer1 = null;
                        $userSettings->answer2 = null;
                    }
                    $userSettings->update();
                }
            }
            if ($request->form_type == 'add') {
                $projectDetail = new ProjectDetail();
                $projectDetail->user_id = Auth::user()->id;
            } else {
                $projectDetail = ProjectDetail::findOrFail($request->project_id);
            }

            if ($request->form_type === 'contactOnly') {
                $projectDetail->customer_contract_id = $request->customer_contract;
                $projectDetail->update();
            } else {
                $projectDetail->project_name = $request->project_name;
                $projectDetail->state_id = $request->state;
                $projectDetail->project_type_id = $request->type;
                $projectDetail->role_id = $request->role;
                $projectDetail->customer_id = $request->customer;
                if (!empty($request->customer_contract)) {
                    $projectDetail->customer_contract_id = $request->customer_contract;
                }
                //$projectDetail->industry_contract_id = $request->industry_contract;
                if (!empty($request->address)) {
                    $projectDetail->address = $request->address;
                }
                if (!empty($request->city)) {
                    $projectDetail->city = $request->city;
                }
                if (!empty($request->zip)) {
                    $projectDetail->zip = $request->zip;
                }
                $projectDetail->country = $request->county;
                $projectDetail->company_work = $request->company_work;
                if (isset($request->answer) && count($request->answer) > 0) {
                    foreach ($request->answer as $key => $value) {
                        $question = RemedyQuestion::find($key);
                        if ($question->question_order == 1) {
                            $projectDetail->answer1 = $value;
                        } else {
                            $projectDetail->answer2 = $value;
                        }
                    }
                }
                if ($request->form_type == 'add') {
                    $projectDetail->save();

                    // create a new contract
                    $projectContract = new ProjectContract();
                    $projectContract->project_id = $projectDetail->id;

                    $projectContract->base_amount = 0;
                    $projectContract->extra_amount = 0;
                    $projectContract->credits = 0;

                    $projectContract->total_claim_amount = (($projectContract->base_amount + $projectContract->extra_amount) - $projectContract->credits);

                    $projectContract->save();
                    $projectContract->update();
                } else {
                    $projectDetail->update();
                }
            }

            Session::forget('projectName');
            Session::forget('role');
            Session::forget('projectType');
            Session::forget('customer');
            Session::forget('state');
            Session::forget('customer_name');

            if (isset($request->industry_contract) && count($request->industry_contract)) {
                if (!empty($request->industry_contract)) {
                    ProjectIndustryContactMap::where('projectId', $projectDetail->id)->delete();
                    foreach ($request->industry_contract as $contacts) {
                        if (ProjectIndustryContactMap::where('projectId', $projectDetail->id)->where('contactId', $contacts)->first()) {
                            $newContacts = ProjectIndustryContactMap::where('projectId', $projectDetail->id)->where('contactId', $contacts)->first();
                            $newContacts->projectId = $projectDetail->id;
                            $newContacts->contactId = $contacts;
                            $newContacts->update();
                        } else {
                            $newContacts = new ProjectIndustryContactMap();
                            $newContacts->projectId = $projectDetail->id;
                            $newContacts->contactId = $contacts;
                            $newContacts->save();
                        }
                    }
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Details Saved successfully',
                'project_id' => $projectDetail->id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * View Project Contract Form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function viewProjectContract()
    {
        try {
            $id = request()->get('project_id');
            $tab_view = request()->get('view');
            $project = ProjectDetail::find($id);
            $liens = '';
            $remedyNames = [];
            if (isset($_GET['edit'])) {
                $liens = LienLawSlideChart::where('state_id', $project->state_id)
                    ->where('project_type', $project->project_type_id)->get();
                $remedyNames = [];
                foreach ($liens as $lien) {
                    $remedyNames[] = $lien->remedy;
                }
            }

            if ($id != '' || $id != 0) {
                if ($project->user_id == Auth::id()) {
                    return view('basicUser.projects.project_contract', [
                        'project_id' => $id,
                        'contract' => $project->project_contract,
                        'project' => $project,
                        'selected_project' => $project,
                        'tab_view' => $tab_view,
                        'remedyNames' => array_unique($remedyNames),
                        'liens' => $liens
                    ]);
                } else {
                    return view('errors.403');
                }
            } else {
                return view('errors.404');
                //return redirect()->back()->with('try-error', 'Project not found');
            }
        } catch (\Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            //return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Submit project Contract form -> step 2
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function submitProjectContract(Request $request)
    {
        try {
            if ($request->form_type == 'add') {
                $projectContract = new ProjectContract();
                $projectContract->project_id = $request->project_id;
            } else {
                $projectContract = ProjectContract::where('project_id', $request->project_id)->firstOrFail();
            }
            $projectContract->base_amount = $request->base_amount;
            $projectContract->extra_amount = $request->extra_amount;
            $projectContract->credits = $request->payment;
            $projectContract->total_claim_amount = (($request->base_amount + $request->extra_amount) - $request->payment);
            $projectContract->general_description = $request->general_description;
            $projectContract->job_no = $request->job_no;
            if ($request->form_type == 'add') {
                $projectContract->save();
            } else {
                $projectContract->update();
            }

            return response()->json([
                'status' => true,
                'message' => 'Contract Saved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    public function updateProjectContract(Request $request)
    {
        try {
            $projectContract = ProjectContract::where('project_id', $request->project_id)->first();
            $bool = false;
            if ($projectContract == null) {
                $bool = true;
                $projectContract = new ProjectContract();
                $projectContract->project_id = $request->project_id;
            }

            $projectContract->waiver = $request->waiver_amount;
            $projectContract->receivable_status = $request->receivable_status;
            $projectContract->calculation_status = $request->calculation_status;
            if ($bool == true) {
                $projectContract->save();
            } else {
                $projectContract->update();
            }

            return response()->json([
                'status' => true,
                'message' => 'Contract Saved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * View Project Date form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewProjectDate()
    {
        try {
            $project_id = request()->get('project_id');
            $tab_view = request()->get('view');
            $project = ProjectDetail::find($project_id);

            if (!is_null($project) && $project->user_id == Auth::id()) {
                if ($project_id != '' || $project_id != 0) {
                    //$project = ProjectDetail::findOrFail($project_id);
                    $remedy = Remedy::where('state_id', $project->state_id)
                        ->where('project_type_id', $project->project_type_id);
                    $tiers = TierTable::where('role_id', $project->role_id)
                        ->where('customer_id', $project->customer_id)->firstOrFail();
                    $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
                    $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
                        ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
                        ->whereIn('answer1', [$project->answer1, ''])
                        ->whereIn('answer2', [$project->answer2, '']);
                    $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
                    $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();
                    $projectDates = [];
                    $projectDateID = [];
                    $enteredDates = [];
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
                        $projectDates[$date->id] = $formattedDate;
                        $projectDateID[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id, 'value' => $formattedDate];
                        $enteredDates[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id];
                    }
                    foreach ($remedyDate as $date) {
                        $dateFields[$date->id] = ['id' => $date->id, 'name' => $date->date_name, 'recurring' => $date->recurring, 'dates' => []];
                        foreach ($datesEntered as $value) {
                            if ($value['remedy'] == $date->id) {
                                $dateFields[$date->id]['dates'] += [$value['id'] => ['id' => $value['id'], 'remedy' => $value['remedy'], 'value' => $value['value'], 'recurring' => $date->recurring]];
                            }
                        }
                    }
                    if (count($remedyDate) > 0) {
                        return view('basicUser.projects.project_dates', [
                            'dates' => $remedyDate,
                            'project_id' => $project_id,
                            'projectDates' => $dateFields,
                            'dateId' => $datesEntered,
                            'storedDates' => $enteredDates,
                            'project' => $project,
                            'tab_view' => $tab_view
                        ]);
                    } else {
                        return view('errors.custom_error', ['title' => 'Error', 'message' => 'No Date Found']);
                        //   return redirect()->back()->with('try-error', 'No Date Found');
                    }
                } else {
                    return view('errors.custom_error', ['title' => '404', 'message' => 'Project not found']);
                    //return redirect()->back()->with('try-error', 'Project not found');
                }
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404!', 'message' => 'Project not found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            //return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function viewDeadline()
    {
        try {
            $id = request()->get('project_id');
            $tab_view = request()->get('view');
            $flag = 0;
            $checkDataAlert = 0;
            $user = Auth::user()->email;
            $project = ProjectDetail::findOrFail($id);
            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'));
            $role_id = ProjectDetail::where('id', $id);
            $answer = $role_id->first()->answer1;
            if ($answer == 'Yes' || $answer == 'Commercial') {
                $flag = 1;
            } elseif ($answer == 'No' || $answer == 'Residential') {
                $flag = 2;
            }
            if ($flag == 0) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem->pluck('remedy_step_id'))->get();
                $emails = ProjectEmail::select('project_emails')
                    ->where('project_id', $project->id)->get();
            } elseif ($flag == 1) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                if ($answer == 'Yes') {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Yes')
                            ->orWhere('answer1', '');
                    });
                } else {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Commercial')
                            ->orWhere('answer1', '');
                    });
                }
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                $emails = ProjectEmail::select('project_emails')
                    ->where('project_id', $project->id)->get();
            } elseif ($flag == 2) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                if ($answer == 'No') {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'No')
                            ->orWhere('answer1', '');
                    });
                } else {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Residential')
                            ->orWhere('answer1', '');
                    });
                }
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                $emails = ProjectEmail::select('project_emails')
                    ->where('project_id', $project->id)->get();
            }
            if (count($deadline) > 0) {
                foreach ($deadline as $key => $value) {
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
                        $daysRemain[$key]['deadline'] = $preliminaryDeadline->date_value;
                        $daysRemain[$key]['preliminaryDates'] = date('Y-m-d', strtotime($daysRemain[$key]['deadline'] . '+' . $daysRemain[$key]['dates'] . ' days'));
                    } else {
                        $daysRemain[$key]['deadline'] = 'N/A';
                        $daysRemain[$key]['preliminaryDates'] = 'N/A';
                    }
                    $remedyNames[$value->getRemedy->id] = $value->getRemedy->remedy;
                }
                return view('basicUser.projects.view_project_deadline', [
                    'deadlines' => $deadline,
                    'project_id' => $project->id,
                    'remedyNames' => array_unique($remedyNames),
                    'tab_view' => $tab_view,
                    'daysRemain' => $daysRemain,
                    'check_alert' => $checkDataAlert
                ]);
            } else {
                return redirect()->back()->with('try-error', 'No Deadline For That Project');
            }
        } catch (\Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
        }
    }

    /**
     * Updates Existing date fields in the DB
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProjectDates(Request $request)
    {
        try {
            foreach ($request->remedyDates as $key => $date) {
                $projectDate = ProjectDates::find($request->date_id);
                if ($date != '') {
                    $dateFormat = \DateTime::createFromFormat('m/d/Y', $date);
                    $projectDate->date_value = $dateFormat->format('Y-m-d');
                    $projectDate->save();
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Contract Saved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Submit Project Dates form -> step-3
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function submitProjectDates(Request $request)
    {
        try {
            //$oldDates = ProjectDates::where('project_id', $request->project_id)->delete();
            foreach ($request->remedyDates as $key => $date) {
                $projectDate = new ProjectDates();
                $projectDate->project_id = $request->project_id;
                $projectDate->date_id = $key;
                if ($date != '') {
                    $dateFormat = \DateTime::createFromFormat('m/d/Y', $date);
                    $projectDate->date_value = $dateFormat->format('Y-m-d');
                }
                if (!is_null($date)) {
                    $projectDate->save();
                }
            }
            if (count($request->remedyDates) === 1) {
                return response()->json([
                    'status' => true,
                    'message' => 'Contract Saved successfully',
                    'id' => $projectDate->id,
                    'date' => $projectDate->date_value
                ], 200);
            }
            return response()->json([
                'status' => true,
                'message' => 'Contract Saved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }
    /**
     * View Project Documents
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewProjectDocument()
    {
        try {
            $flag = 0;
            $project_documents = [];
            $project_id = request()->get('project_id');
            $tab_view = request()->get('view');
            $project = ProjectDetail::find($project_id);

            if (!is_null($project) && $project->user_id == Auth::id()) {
                if ($project_id != '' || $project_id != 0) {

                    // $project = ProjectDetail::findOrFail($project_id);
                    $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->first();
                    $project_document_credit_application = CreditApplication::where('project_id', $project_id)->first();
                    $project_document_joint = JointPaymentAuthorization::where('project_id', $project_id)->first();
                    $project_document_waver = UnconditionalWaiverProgress::where('project_id', $project_id)->first();
                    $project_document_job_info = JobInfo::where('project_id', $project_id)->first();
                    $project_documents[0]['name'] = 'job info sheet';
                    $project_documents[0]['data'] = $project_document_job_info;
                    $project_documents[1]['name'] = 'Claim form and project data sheet';
                    $project_documents[1]['data'] = $project_document_view_claim_form;
                    $project_documents[2]['name'] = 'Credit Application';
                    $project_documents[2]['data'] = $project_document_credit_application;
                    $project_documents[3]['name'] = 'joint payment authorization';
                    $project_documents[3]['data'] = $project_document_joint;
                    $project_documents[4]['name'] = 'Waver progress';
                    $project_documents[4]['data'] = $project_document_waver;

                    return view('basicUser.projects.project_documents', [
                        'project' => $project,
                        'project_id' => $project_id,
                        'project_documents' => $project_documents,
                        'flag' => $flag,
                        'tab_view' => $tab_view
                    ]);
                } else {
                    return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
                    //return redirect()->back()->with('try-error', 'Project not found');
                }
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            //return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * View Project task form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewProjectTask($id)
    {
        try {
//            $project_id = request()->get('project_id');
            $project = ProjectDetail::find($id);
            $liens = '';
            $remedyNames = [];
            if (isset($_GET['edit'])) {
                $liens = LienLawSlideChart::where('state_id', $project->state_id)
                    ->where('project_type', $project->project_type_id)->get();
                $remedyNames = [];
                foreach ($liens as $lien) {
                    $remedyNames[] = $lien->remedy;
                }
            }
            if (!is_null($project)  && $project->user_id == Auth::id()) {
                if ($id != '' || $id != 0) {
                    //  $project = ProjectDetail::findOrFail($project_id);
                    $tasks = ProjectTask::where('project_id', $id)->get();
                    $projectOther = [];
                    $projectOther = ProjectTaskOther::where('user_id', Auth::id())->pluck('task_other');
                    return view('basicUser.projects.tasks', [
                        'project' => $project,
                        'project_other' => $projectOther,
                        'contract' => $project->project_contract,
                        'project_id' => $id,
                        'selected_project' => $id,
                        'tasks' => $tasks,
                        'remedyNames' => array_unique($remedyNames),
                        'liens' => $liens
                    ]);
                } else {
                    return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
                    //return redirect()->back()->with('try-error', 'Project not found');
                }
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            //return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Add a project task form step-5
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProjectTask(Request $request)
    {
        $this->validate($request, [
            'action' => 'required',
            'due_date' => 'required',
            'email_alert' => 'required'
        ]);
        try {
            $project = ProjectDetail::findOrFail($request->project_id);
            $task = new ProjectTask();
            $task->project_id = $request->project_id;
            $task->task_name = $request->action;
            // dd($request->due_date);
            $dateFormat = \DateTime::createFromFormat('m/d/Y', $request->due_date);
            $task->due_date = $dateFormat->format('Y-m-d');
            $task->email_alert = $request->email_alert;
            $task->comment = $request->comment;
            $task->save();

            if(isset($request->other_comment) && !empty($request->other_comment)) {
                $check = ProjectTaskOther::where('user_id', Auth::id())->where('task_other', $request->other_comment)->first();
                if(!$check) {
                    $projectTasksOther = new ProjectTaskOther();
                    $projectTasksOther->task_id = $task->id;
                    $projectTasksOther->user_id = Auth::id();
                    $projectTasksOther->task_other = $request->other_comment;
                    $projectTasksOther->save();
                }
            }

            return redirect()->back()->with('success', 'Task added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Delete a project
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProject(Request $request)
    {
        try {
            $project = ProjectDetail::where('id', $request->project_id)
                ->where('user_id', $request->user_id)->firstOrFail();
            $project->delete();
            return response()->json([
                'status' => true,
                'success' => 'Project deleted successfully'
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

    /**
     * Delete a Task
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTask(Request $request)
    {
        try {
            $task = ProjectTask::findOrFail($request->task_id);
            $task->delete();
            return response()->json([
                'status' => true,
                'success' => 'Task deleted successfully'
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

    /**
     * Update a Task
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTask(Request $request)
    {
        try {
            $task = ProjectTask::findOrFail($request->task_id);
            $task->task_name = $request->action;
            $dateFormat = \DateTime::createFromFormat('m/d/Y', $request->due_date);

            $fileName = '';
            if ($request->hasFile('file')) {
                $extension = File::extension($request->file->getClientOriginalName());
                if (strtolower($extension) == "pdf") {
                    $file = $request->file;
                    $fileName = time() . '.' . $extension;
                    $path = base_path();
                    $filePath = $path . "/public/upload/";
                    $file->move($filePath, $fileName);

                    $JobInfo = JobInfo::where('project_id', $task->project_id)->first();
                    if(!$JobInfo) {
                        $JobInfo = new JobInfo();
                        $JobInfo->project_id = $task->project_id;
                        $JobInfo->save();
                    }
                    $jobFile = new JobInfoFiles();
                    $jobFile->job_info_id = $JobInfo->id;
                    $jobFile->file = $fileName;
                    $jobFile->save();
                }

                $task->job_file_id = $jobFile->id;
                $dateComplete = (new \DateTime())->format('Y-m-d');
                $task->complete_date = $dateComplete;
            }

            $task->due_date = $dateFormat->format('Y-m-d');
            if ($request->complete_date != '') {
                $completeDate = \DateTime::createFromFormat('m/d/Y', $request->complete_date);
                $task->complete_date = $completeDate->format('Y-m-d');
            }
            $task->email_alert = $request->email;
            $task->comment = $request->comment;
            $task->update();
            return response()->json([
                'status' => true,
                'success' => 'Task updated successfully'
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

    /**
     * Get project deadlines
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function projectDeadline($id)
    {
        try {
            $flag = 0;
            $checkDataAlert = 0;
            $user = Auth::user()->email;
            $project = ProjectDetail::findOrFail($id);
            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'));
            $role_id = ProjectDetail::where('id', $id);
            $answer = $role_id->first()->answer;
            if ($answer == 'Yes' || $answer == 'Commercial') {
                $flag = 1;
            } elseif ($answer == 'No' || $answer == 'Residential') {
                $flag = 2;
            }
            if ($flag == 0) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem->pluck('remedy_step_id'))->get();
                $emails = ProjectEmail::select('project_emails')
                    ->where('project_id', $project->id)->get();
            } elseif ($flag == 1) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                if ($answer == 'Yes') {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Yes')
                            ->orWhere('answer1', '');
                    });
                } else {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Commercial')
                            ->orWhere('answer1', '');
                    });
                }
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                $emails = ProjectEmail::select('project_emails')
                    ->where('project_id', $project->id)->get();
            } elseif ($flag == 2) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                if ($answer == 'No') {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'No')
                            ->orWhere('answer1', '');
                    });
                } else {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Residential')
                            ->orWhere('answer1', '');
                    });
                }
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                $emails = ProjectEmail::select('project_emails')
                    ->where('project_id', $project->id)->get();
            }
            if (count($deadline) > 0) {
                foreach ($deadline as $key => $value) {
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
                        $daysRemain[$key]['deadline'] = $preliminaryDeadline->date_value;
                        $daysRemain[$key]['preliminaryDates'] = date('Y-m-d', strtotime($daysRemain[$key]['deadline'] . '+' . $daysRemain[$key]['dates'] . ' days'));
                    } else {
                        $daysRemain[$key]['deadline'] = 'N/A';
                        $daysRemain[$key]['preliminaryDates'] = 'N/A';
                    }
                    //                    if ($preliminaryDeadline == null) {
                    //                        return redirect()->back()->with('deadline-error', ' Fill-up the project dates section to get deadline');
                    //                    }
                    //                    $daysRemain[$key]['deadline'] = $preliminaryDeadline->date_value;
                    //                    $daysRemain[$key]['preliminaryDates'] = date('Y-m-d', strtotime($daysRemain[$key]['deadline'] . '+' . $daysRemain[$key]['dates'] . ' days'));
                    $remedyNames[$value->getRemedy->id] = $value->getRemedy->remedy;
                }
                $statusEmail = [];

                $data = ProjectDeadline::where('project_id', $id)->get();
                foreach ($data as $key => $value) {
                    $statusEmail[] = $value->status;
                }
                foreach ($deadline as $key => $value) {
                    $data = ProjectDeadline::where('project_id', $id)->first();
                    if ($data) {
                        $data->delete();
                    }
                }
                foreach ($deadline as $key => $value) {
                    $project_deadline = new projectDeadline();
                    $project_deadline->project_id = $id;
                    $project_deadline->remedy_step_id = $value->id;
                    $project_deadline->status = isset($statusEmail[$key]) ? $statusEmail[$key] : 1;
                    if ($daysRemain[$key]['preliminaryDates'] != 'N/A') {
                        $project_deadline->preliminary_deadline = $daysRemain[$key]['preliminaryDates'];
                    }
                    $project_deadline->save();
                }
                $deadline_data = ProjectDeadline::where('project_id', $id)->get();
                return view('basicUser.projects.project_deadline', [
                    'deadlines' => $deadline,
                    'project_id' => $project->id,
                    'daysRemain' => $daysRemain,
                    'remedyNames' => array_unique($remedyNames),
                    'project_emails' => $emails,
                    'user' => $user,
                    'check_alert' => $checkDataAlert,
                    'deadline_data' => $deadline_data
                ]);
            } else {
                return redirect()->back()->with('try-error', 'No Deadline For That Project');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Add email for project remedy notification
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProjectEmails(Request $request, $id)
    {
        try {
            $project = ProjectDetail::findOrFail($id);
            if (!empty($request->emailRecipient)) {
                $emailRecipient = array_filter($request->emailRecipient);
            } else {
                return redirect()->back()->with('try-error', 'Please enter some email address');
            }
            if (!empty($emailRecipient)) {
                foreach ($emailRecipient as $key => $email) {
                    $validator = Validator::make($request->all(), [
                        'emailRecipient.*' => 'required|email',
                        'date-legal.*' => 'nullable|date'
                    ]);
                    if ($validator->fails()) {
                        return redirect()->back()->with('try-error', 'Invalid email address');
                    }
                    $emails = new ProjectEmail();
                    $emails->project_id = $project->id;
                    $emails->project_emails = $email;
                    $emails->save();
                }
                return redirect()->back()->with('success', 'Emails stored successfully');
            } else {
                return redirect()->back()->with('try-error', 'Email required');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Add email for project remedy notification
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAllEmails($id)
    {
        try {
            $project = ProjectDetail::findOrFail($id);
            if (count($project) > 0) {
                $email_id = ProjectEmail::where('project_id', $id)->get();
                $date_dealine = ProjectDates::where('project_id', $id)->get();
                if ($email_id) {
                    $email = [];
                    foreach ($email_id as $key => $value) {
                        $email[$key] = $value->project_emails;
                    }
                }
                if ($date_dealine) {
                    $date = [];
                    foreach ($date_dealine as $key => $value) {
                        $date[$key] = $value->date_value;
                    }
                }
            }
            return $date;
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Check alert email change
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAlertChange(Request $request)
    {
        //$remedy = Remedy::all();

        try {
            $deadline = ProjectDeadline::where('project_id', $request->project_id)->where('remedy_step_id', $request->id)->first();
            $deadline->status = $request->alert;
            $deadline->update();
            return response()->json([
                'status' => true,
                'success' => 'Alert updated successfully'
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

    public function viewProjectLien()
    {
        try {
            $companies = Company::pluck('company', 'id');
            $firstNames = UserDetails::pluck('first_name', 'id');
            $project_id = request()->get('project_id');
            if ($project_id != '' || $project_id != 0) {
                $project = ProjectDetail::findOrFail($project_id);
                if ($project->user_id == Auth::id()) {
                    $projectContactsCompany = ProjectIndustryContactMap::where('projectId', $project_id)->pluck('contactId');
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
                    $liens = LienLawSlideChart::where('state_id', $project->state_id)
                        ->where('project_type', $project->project_type_id)->get();
                    $remedyNames = [];
                    foreach ($liens as $lien) {
                        $remedyNames[] = $lien->remedy;
                    }
                    $user = User::findOrFail(Auth::user()->id);
                    // $states = State::all();
                    $states = $this->getUserStates();
                    return view('basicUser.projects.lien', [
                        'project' => $project,
                        'project_id' => $project_id,
                        'liens' => $liens,
                        'remedyNames' => array_unique($remedyNames),
                        'user' => $user,
                        'states' => $states,
                        'projectContacts' => $projectContacts,
                        'companies' => $companies,
                        'first_names' => $firstNames
                    ]);
                } else {
                    return view('errors.403');
                }
            } else {
                return redirect()->back()->with('try-error', 'Project not found');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (QueryException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    public function reviewJobDescription($project_id)
    {
        $user = Auth::user()->id;

        $project = ProjectDetail::where('user_id', $user)->where('id', $project_id)->first();
        $states = State::all();
        $liens = '';
        $remedyNames = [];

        if (isset($_GET['edit'])) {
            $liens = LienLawSlideChart::where('state_id', $project->state_id)
                ->where('project_type', $project->project_type_id)->get();
            $remedyNames = [];
            foreach ($liens as $lien) {
                $remedyNames[] = $lien->remedy;
            }
        }

        return view('basicUser.projects.job_description', [
            'project' => $project,
            'selected_project' => $project,
            'states' => $states,
            'remedyNames' => array_unique($remedyNames),
            'liens' => $liens
        ]);
    }

    public function editJobDescription(Request $request)
    {
        try {
            $project = ProjectDetail::where('id', $request->project_id)->first();
            $project->address = $request->job_address;
            $project->city = $request->job_city;
            $project->zip = $request->job_zip;
            $project->county = $request->county;
            $project->project_name = $request->job_name;
            if (isset($request->job_state)) {
                $project->state_id = $request->job_state;
            }
            $project->update();
            return response()->json([
                'status' => true,
                'message' => 'Job Description updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * createRemedyDates is used during Step 2 of project creation, adding Remedy Dates
     * @param  int $projectId Project ID of project being created
     * @return void
     */
    public function createRemedyDates($projectId)
    {

        // This needs to be cleaned up copy/paste from JobInfo just to get rolling
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
        $liens = '';
        $remedyNames = [];
        if (isset($_GET['edit'])) {
            $liens = LienLawSlideChart::where('state_id', $project->state_id)
                ->where('project_type', $project->project_type_id)->get();
            $remedyNames = [];
            foreach ($liens as $lien) {
                $remedyNames[] = $lien->remedy;
            }
        }
        return view('basicUser.projects.remedydates', [
            'project' => $project,
            'selected_project' => $project,
            'project_id' => $projectId,
            'projectDates' => $dateFields,
            'remedyNames' => array_unique($remedyNames),
            'liens' => $liens
        ]);
    }

    /**
     * createDeadlines shows preliminary deadlines based on entered Remedy Dates
     * @param  int $projectId Project ID of project being created
     * @return void
     */

    public function createDeadlines($projectId)
    {
        $flag = 0;
        $project_id = $projectId;
        $project = ProjectDetail::find($project_id);
        $remedy = Remedy::where('state_id', $project->state_id)
            ->where('project_type_id', $project->project_type_id);
        $tiers = TierTable::where('role_id', $project->role_id)
            ->where('customer_id', $project->customer_id)->firstOrFail();
        $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
        $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
            ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
            ->whereIn('answer1', [$project->answer1, ''])
            ->whereIn('answer2', [$project->answer2, '']);
        $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
        $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();
        $role_id = ProjectDetail::where('id', $project_id);
        $answer = $role_id->first()->answer1;
        if ($answer == 'Yes' || $answer == 'Commercial') {
            $flag = 1;
        } elseif ($answer == 'No' || $answer == 'Residential') {
            $flag = 2;
        }
        if ($flag == 0) {
            $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
            $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
            $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                ->whereIn('remedy_id', $remedy->pluck('id'));
            $deadline = $deadline1->whereIn('id', $tierRem->pluck('remedy_step_id'))->get();
            $emails = ProjectEmail::select('project_emails')
                ->where('project_id', $project->id)->get();
        } elseif ($flag == 1) {
            $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
            $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
            if ($answer == 'Yes') {
                $tierRem1 = $tierRem->where(function ($query) {
                    $query->where('answer1', 'Yes')
                        ->orWhere('answer1', '');
                });
            } else {
                $tierRem1 = $tierRem->where(function ($query) {
                    $query->where('answer1', 'Commercial')
                        ->orWhere('answer1', '');
                });
            }
            $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                ->whereIn('remedy_id', $remedy->pluck('id'));
            $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
            $emails = ProjectEmail::select('project_emails')
                ->where('project_id', $project->id)->get();
        } elseif ($flag == 2) {
            $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
            $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
            if ($answer == 'No') {
                $tierRem1 = $tierRem->where(function ($query) {
                    $query->where('answer1', 'No')
                        ->orWhere('answer1', '');
                });
            } else {
                $tierRem1 = $tierRem->where(function ($query) {
                    $query->where('answer1', 'Residential')
                        ->orWhere('answer1', '');
                });
            }
            $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                ->whereIn('remedy_id', $remedy->pluck('id'));

            $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
            $emails = ProjectEmail::select('project_emails')
                ->where('project_id', $project->id)->get();
        }
//         dd($deadline);
        $daysRemain = [];
        foreach ($deadline as $key => $value) {
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

//        dd($daysRemain);
        $liens = '';
        $remedyNames = [];
        if (isset($_GET['edit'])) {
            $liens = LienLawSlideChart::where('state_id', $project->state_id)
                ->where('project_type', $project->project_type_id)->get();
            $remedyNames = [];
            foreach ($liens as $lien) {
                $remedyNames[] = $lien->remedy;
            }
        }
        return view('basicUser.projects.viewdeadlines', [
            'project' => $project,
            'selected_project' => $project,
            'project_id' => $projectId,
            'deadlines' => $deadline,
            'daysRemain' => $daysRemain,
            'remedyNames' => array_unique($remedyNames),
            'liens' => $liens
        ]);
    }

    public function blankJobInfo()
    {
        try {
            $preferences = UserPreferences::where('user_id', Auth::user()->id)->first();
            $project = new ProjectDetail();
            $project->user_id = Auth::user()->id;
            $project->project_name = $preferences->project_name;
            $project->state_id = $preferences->state_id;
            $project->project_type_id = $preferences->project_type_id;
            $project->role_id = $preferences->role_id;
            $project->customer_id = $preferences->customer_id;
            if (!empty($preferences->answer1)) {
                $project->answer1 = $preferences->answer1;
            }
            if (!empty($preferences->answer2)) {
                $project->answer2 = $preferences->answer2;
            }

            $project->save();
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'project_id' => $project->id,
            ], 200);
        } catch (\Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
        }
    }

    public function viewProjectSummary($projectId)
    {
        try {
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
            if (!is_null($project) && $project->user_id == Auth::id()) {
                // $states = State::all();
                $states = $this->getUserStates();
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

                // deadlines
                $flag = 0;
                $project_id = $projectId;
                $project = ProjectDetail::find($project_id);

                $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));

                $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
                $role_id = ProjectDetail::where('id', $project_id);
                $answer = $role_id->first()->answer1;
                if ($answer == 'Yes' || $answer == 'Commercial') {
                    $flag = 1;
                } elseif ($answer == 'No' || $answer == 'Residential') {
                    $flag = 2;
                }
                if ($flag == 0) {
                    $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                    $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                    $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                        ->whereIn('remedy_id', $remedy->pluck('id'));
                    $deadline = $deadline1->whereIn('id', $tierRem->pluck('remedy_step_id'))->get();
                    $emails = ProjectEmail::select('project_emails')
                        ->where('project_id', $project->id)->get();
                } elseif ($flag == 1) {
                    $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                    $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                    if ($answer == 'Yes') {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'Yes')
                                ->orWhere('answer1', '');
                        });
                    } else {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'Commercial')
                                ->orWhere('answer1', '');
                        });
                    }
                    $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                        ->whereIn('remedy_id', $remedy->pluck('id'));
                    $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                    $emails = ProjectEmail::select('project_emails')
                        ->where('project_id', $project->id)->get();
                } elseif ($flag == 2) {
                    $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                    $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                    if ($answer == 'No') {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'No')
                                ->orWhere('answer1', '');
                        });
                    } else {
                        $tierRem1 = $tierRem->where(function ($query) {
                            $query->where('answer1', 'Residential')
                                ->orWhere('answer1', '');
                        });
                    }
                    $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                        ->whereIn('remedy_id', $remedy->pluck('id'));

                    $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
                    $emails = ProjectEmail::select('project_emails')
                        ->where('project_id', $project->id)->get();
                }
                // dd($deadline);
                foreach ($deadline as $key => $value) {
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
                    $remedyNames[$value->getRemedy->id] = $value->getRemedy->remedy;
                }
                $liens = '';
                $remedyNames = [];
                if (isset($_GET['edit'])) {
                    $liens = LienLawSlideChart::where('state_id', $project->state_id)
                        ->where('project_type', $project->project_type_id)->get();
                    $remedyNames = [];
                    foreach ($liens as $lien) {
                        $remedyNames[] = $lien->remedy;
                    }
                }

                $allContacts = Company::pluck('id');

                $customerContracts = MapCompanyContact::whereIn('company_id', $allContacts)
                    ->whereHas('getContacts', function ($query) {
                        $query->where('type', '0');
                    })->with('company')->get();

                $customerContractInfo = [];
                foreach ($customerContracts as $customerContract) {
                    if (isset($project) && $project != '' && $project->customer_contract_id == $customerContract->id) {
                        // $customerContractInfo = $customerContractInfo;
                        array_push($customerContractInfo, $customerContract);

                        //$customerContract->contacts->first_name.' '.$customerContract->contacts->last_name.' ( '.$customerContract->company->company.' )';
                        // break;
                    }
                }

                // dd($customerContractInfo);

                $industryContracts = MapCompanyContact::whereIn('company_id', $allContacts)
                    ->whereHas('getContacts', function ($query) {
                        $query->where('type', '1');
                    })->with(['company', 'contacts'])->get();

                $contactsF = isset($project) && $project != '' ? ($project->industryContacts ? $project->industryContacts->pluck('contactId') : '') : '';
                $contact_list = array();

                foreach ($industryContracts as $industryContract) {
                    if (isset($contactsF) && $contactsF != '' && count($contactsF) > 0) {
                        if (in_array($industryContract->id, $contactsF->toArray())) {
                            // $contact = new \stdClass();
                            // $contact = $industryContract->contacts->first_name.' '.$industryContract->contacts->last_name.' : ' .
                            //             $industryContract->contacts->contact_type.' ( '.$industryContract->company->company.' ) - ' .
                            //             $industryContract->contacts->email . ',' . $industryContract->contacts->phone;
                            array_push($contact_list, $industryContract);
                        }
                    }
                }

                $contract = $project->project_contract;
                if (isset($contract->base_amount) && ($contract->base_amount != '')) {
                    $base_amount = $contract->base_amount;
                } else {
                    $base_amount = 0;
                }

                if (isset($contract->extra_amount) && ($contract->extra_amount != '')) {
                    $extra_amount = $contract->extra_amount;
                } else {
                    $extra_amount = 0;
                }

                if (isset($contract->credits) && ($contract->credits != '')) {
                    $credits = $contract->credits;
                } else {
                    $credits = 0;
                }

                $contract_amount = $base_amount + $extra_amount - $credits;
                // die($project);
                $unpaid = Auth::user()->projects()->where('project_details.id', $project->id)->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')
                    ->select(DB::raw('project_details.id,  project_contracts.total_claim_amount AS unpaid_balance'))->get();
                $totalUnpaid = 0;
                // die($unpaid);
                foreach ($unpaid as $key => $p) {
                    $totalUnpaid += $p->unpaid_balance;
                }

                return view('basicUser.projects.viewsummary', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'selected_project' => $project,
                    'contract_amount' => $contract_amount,
                    'unpaid' => $totalUnpaid,
                    'user' => $user,
                    'states' => $states,
                    'customerContracts' => $customerContractInfo,
                    'industryContracts' => $contact_list,
                    'projectContacts' => $projectContacts,
                    'jobInfoSheet' => $jobInfoSheet,
                    'companies' => $companies,
                    'first_names' => $firstNames,
                    'contactInfo' => $companyData,
                    'contract' => $contract,
                    'projectDates' => $dateFields,
                    'deadlines' => $deadline,
                    'daysRemain' => $daysRemain,
                    'remedyNames' => array_unique($remedyNames),
                    'liens' => $liens
                ]);
            } elseif (is_null($project)) {
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

    public function getSlideChart(Request $request) {
        if ($request->header('Authorization') != config('services.EXTERNAL_API_KEY')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        if (!isset($request->state) || !isset($request->project_type_id)) {
            return response()->json([
                'status' => false,
                'message' => 'State and Project Type ID are required'
            ], 400);
        }

        $state = State::where('name', $request->state)->first();
        if (!$state) {
            return response()->json([
                'status' => false,
                'message' => 'State is invalid'
            ], 400);
        }

        if ($request->project_type_id < 1 || $request->project_type_id > 3) {
            return response()->json([
                'status' => false,
                'message' => 'Project Type ID is invalid'
            ], 400);
        }

        $liens = LienLawSlideChart::where('state_id', $state->id)
            ->where('project_type', $request->project_type_id)->get();
        $charts = [];
        foreach ($liens as $lien) {
            $charts[] = [
                'id' => $lien->id,
                'remedy' => $lien->remedy,
                'description' => $lien->description,
                'tier_limit' => $lien->tier_limit,
            ];
        }

        return response()->json([
            'charts' => $charts,
            'status' => true,
            'message' => 'Chart fetched successfully',
        ], 200);
    }

    public function getUserStates()
    {
        $subscription = $this->getUserSubscription();
        $user = Auth::user();
        if ($subscription == 'basic') {
            $userDetails = $user->details()->firstOrFail();
            $states = array();
            $st = State::findOrFail($userDetails->state_id);
            array_push($states, $st);
            // $states = State::all();
        } else {
            $states = State::all();
        }
        return $states;
    }

    public function getUserSubscription()
    {
        $subscription = '';
        $memberSubscription = Auth::user()->subscriptions()->whereNull('ends_at')->first();

        if (Auth::user()->actual_plan == 'basic' && isset($memberSubscription)) {
            $subscription = 'basic';
        }

        if (Auth::user()->actual_plan == 'pro' && isset($memberSubscription)) {
            $subscription = 'pro';
        }
        return $subscription;
    }
}
