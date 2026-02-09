<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use App\Models\State;
use App\Models\Remedy;
use App\Models\Contact;
use App\Models\TierTable;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\ProjectRole;
use App\Models\ProjectTask;
use App\Models\ProjectType;
use App\Models\CustomerCode;
use App\Models\LienProvider;
use App\Models\ProjectDates;
use App\Models\ProjectEmail;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Models\ProjectDetail;
use App\Models\CompanyContact;
use App\Models\JobRequestLabel;
use App\Models\CreditApplication;
use App\Models\JobRequestDeadline;
use Illuminate\Support\Facades\Auth;
use App\Models\JobRequestCombination;
use App\Models\UnconditionalWaiverFinal;
use App\Models\ClaimFormProjectDataSheet;
use App\Models\JointPaymentAuthorization;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ProjectManagementController for project management from member
 * @package App\Http\Controllers
 */
class ProjectManagementController extends Controller
{
    /**
     * Get Project Management Page for manage Role and Type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getProjectManagement()
    {
        try {
            $customers = CustomerCode::all();
            $searchRole = request()->get('projectRole');
            //dd($searchRole);
            $queryRole = ProjectRole::orderBy('project_roles', 'ASC');
            //dd($queryRole->get());
            if (is_null($searchRole)) {
                $roles = $queryRole->paginate(5, ['*'], 'rolePage');
            } else {
                $roles = $queryRole->where('project_roles', 'LIKE', '%' . $searchRole . '%')
                    ->paginate(5, ['*'], 'rolePage');
            }
            $searchType = request()->get('projectType');
            $queryType = ProjectType::orderBy('project_type', 'ASC');
            if (is_null($searchType)) {
                $types = $queryType->paginate(5, ['*'], 'typePage');
            } else {
                $types = $queryType->where('project_type', 'LIKE', '%' . $searchType . '%')
                    ->paginate(5, ['*'], 'typePage');
            }
            $searchTier = request()->get('projectTier');
            $queryTier = TierTable::orderBy('tier_code', 'ASC');
            if (is_null($searchTier)) {
                $tier = $queryTier->paginate(5, ['*'], 'tierPage');
            } else {
                $tier = $queryTier->where('tier_limit', 'LIKE', '%' . $searchTier . '%')
                    ->orWhere('tier_code', 'LIKE', '%' . $searchTier . '%')
                    ->paginate(5, ['*'], 'tierPage');
            }
            return view('project.management', [
                'roles' => $roles,
                'types' => $types,
                'tiers' => $tier,
                'customers' => $customers
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Action for add & edit roles & type
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionProjectManagement(Request $request)
    {
        try {
            if ($request->type == 'addRole') {
                $role = ProjectRole::where('project_roles', $request->name)->count();
                if ($role == 0) {
                    $role = new ProjectRole();
                    $role->project_roles = $request->name;
                    $role->save();
                } else {
                    return response()->json([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'Role already exists'
                    ], 200);
                }
            } elseif ($request->type == 'addType') {
                $type = ProjectType::where('project_type', $request->name)->count();
                if ($type == 0) {
                    $type = new ProjectType();
                    $type->project_type = $request->name;
                    $type->save();
                } else {
                    return response()->json([
                        'status' => false,
                        'type' => 'error',
                        'message' => 'Type already exists'
                    ], 200);
                }
            } elseif ($request->type == 'editRole') {
                $role = ProjectRole::findOrFail($request->id);
                $role->project_roles = $request->name;
                $role->update();
            } else {
                $type = ProjectType::findOrFail($request->id);
                $type->project_type = $request->name;
                $type->update();
            }
            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'Action success'
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

    /**
     * Delete Project Management
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProjectManagement(Request $request)
    {
        try {
            if ($request->type == 'type') {
                $type = ProjectType::findOrFail($request->id);
                $type->delete();
            } elseif ($request->type == 'role') {
                $role = ProjectRole::findOrFail($request->id);
                $role->delete();
            } elseif ($request->type == 'tier') {
                $tier = TierTable::findOrFail($request->id);
                $tier->delete();
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Type not found'
                ], 200);
            }
            return response()->json([
                'status' => true,
                'message' => 'Deleted successfully'
            ], 200);
        } catch (Exception $e) {
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
     * Job request form view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getJobRequestForm()
    {
        try {
            $states = State::all();
            $roles = TierTable::all();
            $types = ProjectType::all();
            $property_types = PropertyType::all();
            return view('project.multistep_job_request', [
                'states' => $states,
                'roles' => $roles,
                'types' => $types,
                'property_types' => $property_types
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Job request form action
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postJobRequestForm(Request $request)
    {
        try {
            $combination = new JobRequestCombination();
            $combination->state_id = $request->state;
            $combination->role_id = $request->role;
            $combination->type_id = $request->type;
            $combination->property_type_id = $request->property_type;
            $combination->save();

            foreach ($request->label as $label) {
                $new_label = new JobRequestLabel();
                $new_label->label = $label;
                $new_label->combination_id = $combination->id;
                $new_label->save();
            }

            foreach ($request->deadline_name as $key => $deadline) {
                $label_id = JobRequestLabel::where('combination_id', $combination->id)
                    ->where('label', $request->label_select[$key])->firstOrFail();
                $new_deadline = new JobRequestDeadline();
                $new_deadline->combination_id = $combination->id;
                $new_deadline->days = $request->deadline_days[$key];
                $new_deadline->months = $request->deadline_months[$key];
                $new_deadline->years = $request->deadline_years[$key];
                $new_deadline->name = $deadline;
                $new_deadline->label_id = $label_id->id;
                $new_deadline->save();
            }
            return redirect()->route('job.request.list');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Combination Formula
     * @param $arrays
     * @return array
     */
    public function get_combinations($arrays)
    {
        $result = [];
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array) {
            $size = $size * sizeof($array);
        }
        for ($i = 0; $i < $size; $i++) {
            $result[$i] = [];
            for ($j = 0; $j < $sizeIn; $j++) {
                array_push($result[$i], current($arrays[$j]));
            }
            for ($j = ($sizeIn - 1); $j >= 0; $j--) {
                if (next($arrays[$j])) {
                    break;
                } elseif (isset($arrays[$j])) {
                    reset($arrays[$j]);
                }
            }
        }
        return $result;
    }

    /**
     * Get list of the job request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function jobRequestList()
    {
        try {
            $combinations = JobRequestCombination::orderBy('created_at', 'DESC')->paginate(15);
            return view('project.list', [
                'combinations' => $combinations
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Edit a job request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getEditJobRequestForm($id)
    {
        try {
            $states = State::all();
            $roles = TierTable::all();
            $types = ProjectType::all();
            $property_types = PropertyType::all();
            $job = JobRequestCombination::findOrFail($id);
            return view('project.edit', [
                'states' => $states,
                'roles' => $roles,
                'types' => $types,
                'job' => $job,
                'property_types' => $property_types
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Delete a job request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteJobRequest(Request $request)
    {
        try {
            $combination = JobRequestCombination::findOrFail($request->id);
            $combination->delete();
            return response()->json([
                'status' => true,
                'message' => 'Deleted successfully'
            ], 200);
        } catch (Exception $e) {
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
     * Clone a job request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cloneJobRequest(Request $request)
    {
        try {
            $combination = JobRequestCombination::findOrFail($request->id);

            $newCombination = new JobRequestCombination();
            $newCombination->state_id = $combination->state_id;
            $newCombination->role_id = $combination->role_id;
            $newCombination->type_id = $combination->type_id;
            $newCombination->property_type_id = $combination->property_type_id;
            $newCombination->created_at = date("Y-m-d H:i:s", strtotime($combination->created_at));
            $newCombination->save();

            foreach ($combination->CombinationLabel as $label) {
                $newLabel = new JobRequestLabel();
                $newLabel->combination_id = $newCombination->id;
                $newLabel->label = $label->label;
                $newLabel->save();
            }

            foreach ($combination->deadline as $deadline) {
                $labelId = JobRequestLabel::where('label', $deadline->deadlineLabel->label)
                    ->where('combination_id', $newCombination->id)->firstOrFail();

                $newDeadline = new JobRequestDeadline();
                $newDeadline->name = $deadline->name;
                $newDeadline->days = $deadline->days;
                $newDeadline->months = $deadline->months;
                $newDeadline->years = $deadline->years;
                $newDeadline->combination_id = $newCombination->id;
                $newDeadline->label_id = $labelId->id;
                $newDeadline->save();
            }
            return response()->json([
                'status' => true,
                'message' => 'cloned successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
            $formattedDate = $date->date_value;
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Action of Job Request For,
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actionEditJobRequestForm(Request $request)
    {
        try {
            $combination = JobRequestCombination::findOrFail($request->job_id);
            $combination->state_id = $request->state;
            $combination->role_id = $request->role;
            $combination->type_id = $request->type;
            $combination->property_type_id = $request->property_type;
            $combination->update();

            $deleteLabel = JobRequestLabel::where('combination_id', $request->job_id)->delete();
            $deleteDeadline = JobRequestDeadline::where('combination_id', $request->job_id)->delete();

            foreach ($request->label as $label) {
                $new_label = new JobRequestLabel();
                $new_label->label = $label;
                $new_label->combination_id = $combination->id;
                $new_label->save();
            }

            foreach ($request->deadline_name as $key => $deadline) {
                $label_id = JobRequestLabel::where('combination_id', $combination->id)
                    ->where('label', $request->label_select[$key])->firstOrFail();
                $new_deadline = new JobRequestDeadline();
                $new_deadline->combination_id = $combination->id;
                $new_deadline->days = $request->deadline_days[$key];
                $new_deadline->months = $request->deadline_months[$key];
                $new_deadline->years = $request->deadline_years[$key];
                $new_deadline->name = $deadline;
                $new_deadline->label_id = $label_id->id;
                $new_deadline->save();
            }
            return redirect()->route('job.request.list');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Job Request check
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkJobRequest(Request $request)
    {
        try {
            if ($request->time == 'create') {
                $combination = JobRequestCombination::where('state_id', $request->state)
                    ->where('role_id', $request->role)
                    ->where('type_id', $request->type)
                    ->where('property_type_id', $request->property_type)->firstOrFail();
            } else {
                $combination = JobRequestCombination::where('state_id', $request->state)
                    ->where('role_id', $request->role)
                    ->where('type_id', $request->type)
                    ->where('property_type_id', $request->property_type)
                    ->where('id', '!=', $request->id)->firstOrFail();
            }
            return response()->json([
                'status' => true,
                'message' => 'record found',
                'data' => $combination->id
            ], 200);
        } catch (Exception $e) {
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
     * Action of Tier Form
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tierAction(Request $request)
    {
        try {
            if ($request->type == 'edit') {
                $this->validate($request, [
                    'role_id' => 'required',
                    'tierLimit' => 'required',
                    'tierCode' => 'required|unique:tier_tables,tier_code,' . $request->id,
                ]);
                $tier = TierTable::findOrFail($request->id);
            } else {
                $this->validate($request, [
                    'role_id' => 'required',
                    'tierLimit' => 'required',
                    'tierCode' => 'required|unique:tier_tables,tier_code',
                ]);
                $tier = new TierTable();
            }
            $tier->role_id = $request->role_id;
            $tier->customer_id = $request->customer_id;
            $tier->tier_limit = $request->tierLimit;
            $tier->tier_code = $request->tierCode;
            if ($request->type == 'edit') {
                $tier->update();
            } else {
                $tier->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'tier created'
            ], 201);
        } catch (Exception $e) {
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
     * Return Projects list in admin panel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewProjects(Request $request)
    {
        try {
            $projects = ProjectDetail::query();
            $sortBy = $request->get('sortBy');
            $sortWith = $request->get('sortWith');
            if (isset($sortBy) && isset($sortWith)) {
                $projects->orderBy($sortWith, $sortBy);
            } else {
                $projects->orderBy('updated_at', 'DESC');
            }
            $projects = ProjectDetail::query();
            $projectTypes = ProjectType::pluck('project_type', 'id')->toArray();
            $states = State::pluck('name', 'id')->toArray();
            $customers = $this->createCustomerList();
            $subUsers = $this->createMemberList();
            $dateTypes = $this->getRemedyDate();
            $projectNames = ProjectDetail::pluck('project_name', 'id')->toArray();
            $lienProviders = $this->createLienList();
            $projects->join('users', function($join) {
                $join->on('project_details.user_id', '=', 'users.id');
            });

            $projects = !is_null($this->searchFilter($request, $projects)) ? $this->searchFilter($request, $projects) : $projects;
            $queryParams = $this->getQueryParams($request);

            /*$projectName = Input::get('project_name');
            $companyName = Input::get('company_name');
            $projectUser = Input::get('project_user');
            $projectState = Input::get('project_state');
            $projectType = Input::get('project_type');
            $projectRole = Input::get('project_role');
            $projectCustomer = Input::get('project_customer');
            if(isset($projectName) && !is_null($projectName) && !empty($projectName)) {
                $projects->orderBy('project_name', $projectName );
            }
            if(isset($companyName) && !is_null($companyName) && !empty($companyName)) {
                $projects->join('users', function($join) {
                                        $join->on('project_details.user_id', '=', 'users.id');
                                    })->join('user_details', function($join) {
                                            $join->on('users.id', '=', 'user_details.user_id');
                                    })->orderBy('user_details.company',$companyName);

            }
            if(isset($projectUser) && !is_null($projectUser) && !empty($projectUser)) {
                $projects->join('users', function($join) {
                                        $join->on('project_details.user_id', '=', 'users.id');
                                    })->orderBy('users.email',$projectUser);

            }
            if(isset($projectState) && !is_null($projectState) && !empty($projectState)) {
                $projects->join('states', function($join) {
                    $join->on('project_details.state_id', '=', 'states.id');
                })->orderBy('states.name',$projectState);
            }
            if(isset($projectType) && !is_null($projectType) && !empty($projectType)) {
                $projects->join('project_types', function($join) {
                    $join->on('project_details.project_type_id', '=', 'project_types.id');
                })->select('project_details.*')->addSelect('project_types.project_type as p_type')->orderBy('project_type',$projectType);
            }
            if(isset($projectRole) && !is_null($projectRole) && !empty($projectRole)) {
                $projects->join('project_roles', function($join) {
                    $join->on('project_details.role_id', '=', 'project_roles.id');
                })->orderBy('project_roles',$projectRole);
            }
            if(isset($projectCustomer) && !is_null($projectCustomer) && !empty($projectCustomer)) {
                $projects->join('tier_tables', function($join) {
                    $join->on('project_details.customer_id', '=', 'tier_tables.id');
                })->join('customer_codes', function($join) {
                    $join->on('tier_tables.customer_id', '=', 'customer_codes.id');
                })->orderBy('customer_codes.name',$projectCustomer);
            }*/
            //dd($projects->toSql());
            $projects = $projects->paginate(15);
            return view('project.project_view', [
                'projects' => $projects,
                'projectTypes' => $projectTypes,
                'states' => $states,
                'projectNames' => $projectNames,
                'customers' => $customers,
                'subUsers' => $subUsers,
                'lienProviders' => $lienProviders,
                'queryParams' => $queryParams,
                'dateTypes' => $dateTypes
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Creates the customer dropdown list for project list page
     * @param $customers
     * @return array
     */
    protected function createCustomerList()
    {
        $customers = CompanyContact::where('type', '0')->get();
        // echo "<pre>";
        // print_r($customers);
        // die();
        $customerArray = [];
        foreach ($customers as $customer) {
//             $customerArray[$customer->id] = (!is_null($customer->getCompany) ? $customer->getCompany->company : 'N/A').' ( '.$customer->first_name.' '.$customer->last_name.' )';
            $customerArray[$customer->id] = (!is_null($customer->mapContactCompany) ? $customer->mapContactCompany->company->company : 'N/A') .' ( '.$customer->first_name.' '.$customer->last_name.' )';
        }
//        dd($customerArray);
        return $customerArray;
    }

    /**
     * Creates the member and submember list for project list page
     * @param $customers
     * @return array
     */
    protected function createMemberList()
    {
        $users = User::whereIn('role', ['5', '6'])->get();
        $userArray = [];
        foreach ($users as $user) {
            $userArray[$user->id] = !is_null($user->details) ? $user->details->first_name . ' ' . $user->details->last_name : 'N/A';
        }
        return $userArray;
    }

    /**
     * Creates the member and submember list for project list page
     * @param $customers
     * @return array
     */
    protected function createLienList()
    {
        $lienProviders = LienProvider::all();
        $lienProvidersArray = [];
        foreach ($lienProviders as $lienProvider) {
            $lienProvidersArray[$lienProvider->id] = $lienProvider->company . '( ' . $lienProvider->firstName . ' ' . $lienProvider->lastName . ' )';
        }
        return $lienProvidersArray;
    }

    /**
     * Creates the member and submember list for project list page
     * @param $customers
     * @return array
     */
    protected function getRemedyDate()
    {
        $remedies = Remedy::query();
        $remedies->distinct('remedy');
        $remediesArray = [];
        foreach ($remedies->get() as $remedy) {
            $remediesArray[$remedy->remedy] = $remedy->remedy;
        }
        return $remediesArray;
    }

    /**
     * Search filter function.
     * @param $request
     * @param null $projects
     * @return null
     */
    protected function searchFilter($request, $projects = null)
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
                    $query->whereBetween('base_amount', [$lowerRange, $upperRange]);
                });
            } else {
                /* $projects->whereHas('project_contract', function ($query) use ($claimAmount) {
                     $query->where('total_claim_amount','>=',$claimAmount);
                 });*/
                $projects->whereHas('project_contract', function ($query) use ($claimAmount) {
                    $query->where('base_amount', '>=', $claimAmount);
                });
            }
        }
        if (isset($customer) && $customer != 'all') {
            $projects->whereHas('customer_contract.contacts', function ($query) use ($customer) {
                $query->where('id', '=', $customer);
            });
        }
        if (isset($projectName) && $projectName != '') {
            $projects->where('project_name', 'LIKE', '%' . $projectName . '%');
        }
        if (isset($user) && $user != 'all') {
            $projects->whereHas('user', function ($query) use ($user) {
                $query->where('id', '=', $user);
            });
        }
        if (isset($complianceManagement) && $complianceManagement != 'default') {
        }
//        if (isset($dateType) && $dateType != 'all') {
//            $projects->whereHas('project_date.remedyDate.remedy', function ($query) use ($dateType) {
//                $query->where('remedy', 'LIKE', '%' . $dateType . '%');
//            });
//        }
        $fromDate = (isset($from) && $from != '') ? date('Y-m-d', strtotime($from)) : date('Y-m-d', strtotime(''));
        $toDate = (isset($to) && $to != '') ? date('Y-m-d', strtotime($to)) : date('Y-m-d');
        if (isset($from) && isset($to)) {
            if (isset($dateType) && $dateType != 'all') {
                if (isset($dateType) && $dateType == 'EntryDate') {
                    $projects->whereBetween('project_details.created_at', [$fromDate, $toDate]);
                } else if (isset($dateType) && $dateType == 'NextActionDate') {
                    $projects->whereBetween('project_details.updated_at', [$fromDate, $toDate]);
                }
            }
        }
//        $fromDate = (isset($from) && $from != '') ? date('Y-m-d', strtotime($from)) : date('Y-m-d', strtotime(''));
//        $toDate = (isset($to) && $to != '') ? date('Y-m-d', strtotime($to)) : date('Y-m-d');
//        if (isset($from) && isset($to)) {
//            $projects->whereHas('project_date', function ($query) use ($fromDate, $toDate) {
//                $query->whereBetween('date_value', [$fromDate, $toDate]);
//            });
//        }

        if (isset($complianceProvider) && $complianceProvider != 'all') {
            $projects->whereHas('user.lienProvider.findLien', function ($query) use ($complianceProvider) {
                $query->where('id', '=', $complianceProvider);
            });
        }
        return $projects;
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
        ];
    }

    /**
     * Delete project
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProjects(Request $request)
    {
        try {
            $project = ProjectDetail::findOrFail($request->id);
            $project->delete();

            return response()->json([
                'status' => true,
                'message' => 'project deleted'
            ], 201);
        } catch (Exception $e) {
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
     * view project details
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function projectShowDetails($project_id)
    {
        try {
            $project = ProjectDetail::find($project_id);
            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $tasks = ProjectTask::where('project_id', $project_id)->get();
            $remedyDate = RemedyDate::whereIn('remedy_id', $remedy->pluck('id'))->get();
            $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->first();
            $project_document_credit_application = CreditApplication::where('project_id', $project_id)->first();
            $project_document_joint = JointPaymentAuthorization::where('project_id', $project_id)->first();
            $project_document_waver = UnconditionalWaiverFinal::where('project_id', $project_id)->first();
            $project_documents[0]['name'] = 'Claim form and project data sheet';
            $project_documents[0]['data'] = $project_document_view_claim_form;
            $project_documents[1]['name'] = 'Credit Application';
            $project_documents[1]['data'] = $project_document_credit_application;
            $project_documents[2]['name'] = 'joint payment authorization';
            $project_documents[2]['data'] = $project_document_joint;
            $project_documents[3]['name'] = 'Waver progress';
            $project_documents[3]['data'] = $project_document_waver;
            $remedy1 = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $remedyDate1 = RemedyDate::whereIn('remedy_id', $remedy1->pluck('id'));
            $deadline = RemedyStep::whereIn('remedy_date_id', $remedyDate1->pluck('id'))
                ->whereIn('remedy_id', $remedy->pluck('id'))->get();
            $emails = ProjectEmail::select('project_emails')
                ->where('project_id', $project->id)->get();
            foreach ($deadline as $key => $value) {
                $years = $value->years;
                $months = $value->months;
                $days = $value->days;
                $remedyDateId = $value->remedy_date_id;
                $daysRemain[$key]['dates'] = ($years * 365) + ($months * 30) + ($days * 1);
                $preliminaryDeadline = ProjectDates::select('date_value')
                    ->where('project_id', $project->id)
                    ->where('date_id', $remedyDateId)->first();
                if ($preliminaryDeadline != null) {
                    $daysRemain[$key]['deadline'] = $preliminaryDeadline->date_value;
                    $daysRemain[$key]['preliminaryDates'] = date('Y-m-d', strtotime($daysRemain[$key]['deadline'] . '+' . $daysRemain[$key]['dates'] . ' days'));
                    //return redirect()->back()->with('date-error', 'Please give some valid date in project date section ');
                } else {
                    $daysRemain[$key]['deadline'] = '';
                    $daysRemain[$key]['preliminaryDates'] = date('Y-m-d', strtotime($daysRemain[$key]['dates'] . ' days'));
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
            $states = State::all();
            $roles = ProjectRole::all();
            $types = ProjectType::all();
            $property = PropertyType::all();
            $customerContract = Contact::where('type', '0')
                ->where('user_id', Auth::user()->id)->get();
            $industryContract = Contact::where('type', '1')
                ->where('user_id', Auth::user()->id)->get();
            $project_document_view_claim_form = ClaimFormProjectDataSheet::where('project_id', $project_id)->get();
            return view('project.project_details', [
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
                'flag' => 0,
                'project_documents' => $project_documents,
                'project_document' => $project_document_view_claim_form,
                'deadlines' => $deadline,
                'project_id' => $project->id,
                'daysRemain' => $daysRemain,
                'remedyNames' => array_unique($remedyNames),
                'project_emails' => $emails
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }
    /**
     * Update project Status from Admin
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function projectStatus(Request $request)
    {
        try {
            $projects = ProjectDetail::findOrFail($request->id);
            if ($request->status == '1') {
                $projects->status = '0';
            } else {
                $projects->status = '1';
            }
            $projects->update();
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
    public function projectStatusUpdate(Request $request)
    {
        try {
            $projects = ProjectDetail::findOrFail($request->id);
            if ($request->status == '1') {
                $projects->status = '0';
            } else {
                $projects->status = '1';
            }
            $projects->update();
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
