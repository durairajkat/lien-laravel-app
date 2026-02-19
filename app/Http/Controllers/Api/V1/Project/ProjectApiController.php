<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectSaveRequest;
use App\Models\Company;
use App\Models\ProjectDates;
use App\Models\ProjectDetail;
use App\Models\ProjectIndustryContactMap;
use App\Services\Project\ProjectContractService;
use App\Services\Project\ProjectDocumentService;
use App\Services\Project\ProjectService;
use App\Services\Project\ProjectTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectApiController extends Controller
{
    public function __construct(
        protected ProjectDocumentService $projectDocumentService,
        protected ProjectService $projectService,
        protected ProjectTaskService $projectTaskService
    ) {}

    public function index(Request $request)
    {
        $baseQuery = ProjectDetail::query()
            ->where('project_details.user_id', auth()->id());

        $overallTotal = (clone $baseQuery)->count();

        $query = $baseQuery
            ->with(['tasks', 'originalCustomer', 'project_contract', 'project_date', 'documents'])
            ->join('states', 'states.id', '=', 'project_details.state_id')
            ->leftJoin('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')
            ->select(
                'project_details.*',
                'states.name as state',
                'project_contracts.base_amount'
            );

        /* -------- SEARCH -------- */
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('project_name', 'like', "%{$request->search}%")
                    ->orWhere('states.name', 'like', "%{$request->search}%")
                    ->orWhere('city', 'like', "%{$request->search}%")
                    ->orWhere('zip', 'like', "%{$request->search}%")
                    ->orWhere('project_contracts.base_amount', 'like', "%{$request->search}%");
            });
        }

        /* -------- FILTER STATUS -------- */
        if ($request->status) {
            $status = $request->status === 'active' ? '1' : '0';
            $query->where('project_details.status', $status);
        }

        /* -------- FILTER STATE -------- */
        if ($request->state_id) {
            $query->where('project_details.state_id', $request->state_id);
        }

        /* -------- SORT -------- */
        $sortBy = $request->sort_by ?? 'project_details.created_at';
        $sortDir = $request->sort_dir ?? 'desc';

        $query->orderBy($sortBy, $sortDir);

        /* -------- PAGINATION -------- */
        $projects = $query->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => $projects,
            'overall_total' => $overallTotal, // 🔥 important
        ]);
    }


    public function totalCount()
    {
        $data = ProjectDetail::where('project_details.user_id', auth()->id())
            ->selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN project_details.status = '0' THEN 1 ELSE 0 END) as inprogress,
                    SUM(CASE WHEN project_details.status = '1' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN project_details.status = '2' THEN 1 ELSE 0 END) as completed
                ")
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'Project count fetched successfully',
            'data' => $data,
        ], 200);
    }

    public function saveProject(ProjectSaveRequest $request, ProjectContractService $contractService)
    {

        DB::transaction(function () use ($request, $contractService) {
            $user_id = auth()->id();
            $ins = [
                'project_name' => $request->projectName,
                'user_id' => $user_id,
                'state_id' => $request->stateId,
                'project_type_id' => $request->projectTypeId,
                'customer_id' => $request->customerTypeId,
                'role_id' => $request->roleId,
                'start_date' => $request->startDate ?? null,
                'esitmated_end_date' => $request->endDate ?? null,
                'address' => $request->jobAddress ?? null,
                'city' => $request->jobCity ?? null,
                'zip' => $request->jobZip ?? null,
                'county_id' => $request->jobCountyId ?? null,
                'description' => $request->jobName ?? null,
            ]; // this will insert wizard project details and project job description

            $project = ProjectDetail::create($ins);
            /**  Insert in Project Dates */
            $dates = $request->furnishingDates ? json_decode($request->furnishingDates, true) : [];
            info($dates);
            if (!empty($dates) && is_array($dates)) {
                foreach ($dates as $key => $date) {
                    $data[$key] = $date;
                    ProjectDates::updateOrCreate(
                        [
                            'project_id' => $project->id,
                        ],
                        [
                            'date_id' => $key,
                            'date_value' => $date,
                        ]
                    );
                }
            }

            /** Project Contract */
            $contract_details = $contractService->saveOrUpdate($project->id, [
                'base_amount' => $request->baseContractAmount,
                'extra_amount' => $request->additionalCosts,
                'credits' => $request->paymentsCredits,
                'general_description' => $request->materialServicesDescription,
                'job_no' => $request->jobProjectNumber,
            ]);

            /** insert Project customer contacts */
            $customer_contacts = $request->customerContacts ? json_decode($request->customerContacts, true) : [];
            $selected_contacts = $request->selectedCustomerContacts ? json_decode($request->selectedCustomerContacts, true) : '';
            if (!empty($customer_contacts) && $selected_contacts) {
                foreach ($customer_contacts as $cont) {
                    if (!$cont['company']) {
                        continue;
                    }

                    $cust_ins = [
                        'website' => $cont['website'] ?? null,
                        'address' => $cont['address'] ?? null,
                        'city' => $cont['city'] ?? null,
                        'state_id' => $cont['state_id'] ?? null,
                        'zip' => $cont['zip'] ?? null,
                        'phone' => $cont['phone'] ?? null,
                        'fax' => $cont['fax'] ?? null,
                        'is_selected' => $selected_contacts['company'] == $cont['company'],
                    ];

                    $company = Company::updateOrCreate(
                        ['user_id' => $user_id, 'project_id' => $project->id, 'company' => $cont['company']],
                        $cust_ins
                    );
                    /**  insert in companyContacts */
                    $contacts = $cont['contacts'] ?? [];
                    $contact_ins = [];
                    foreach ($contacts as $contact) {
                        $contact_ins[] = [
                            'user_id' => $user_id,
                            'type' => '0',
                            'role_id' => $contact['role_id'],
                            'first_name' => $contact['firstName'] ?? null,
                            'last_name' => $contact['lastName'] ?? null,
                            'email' => $contact['email'] ?? null,
                            'phone' => $contact['directPhone'] ?? null,
                            'cell' => $contact['cell'] ?? null,
                        ];
                    }
                    $company->contacts()->createMany($contact_ins);
                }
            }

            /** insert project contacts */
            $projectContacts = $request->projectContacts ? json_decode($request->projectContacts, true) : [];
            if (!empty($projectContacts)) {
                $newContacts = collect($projectContacts)
                    ->filter(fn($data) => !empty($data['is_new']))
                    ->values(); // reset index

                if ($newContacts->isNotEmpty()) {
                    foreach ($newContacts as $contact) {
                        if (!$contact['company']) {
                            continue;
                        }
                        // insert in company
                        $con_ins = [
                            'website' => $contact['website'] ?? null,
                            'address' => $contact['address'] ?? null,
                            'city' => $contact['city'] ?? null,
                            'state_id' => $contact['state_id'] ?? null,
                            'zip' => $contact['zip'] ?? null,
                            'phone' => $contact['phone'] ?? null,
                            'fax' => $contact['fax'] ?? null,
                            'user_id' => $user_id,
                            'company' => $contact['company'],
                            'contact_type' => 'project'
                        ];
                        $company = Company::create($con_ins);

                        //insert in company contact
                        $contacts = $contact['contacts'] ?? [];
                        $project_contact_ins = [];
                        foreach ($contacts as $tmp) {
                            $project_contact_ins[] = [
                                'user_id' => $user_id,
                                'type' => '1',
                                'role_id' => $tmp['role_id'],
                                'contact_role_id' => $contact['role_id'] ?? null,
                                'first_name' => $tmp['firstName'] ?? null,
                                'last_name' => $tmp['lastName'] ?? null,
                                'email' => $tmp['email'] ?? null,
                                'phone' => $tmp['directPhone'] ?? null,
                                'cell' => $tmp['cell'] ?? null,
                            ];
                        }
                        $company->contacts()->createMany($project_contact_ins);
                    }
                }
            }
            $selectedProjectContacts = $request->selectedProjectContacts ? json_decode($request->selectedProjectContacts, true) : [];
            if (!empty($selectedProjectContacts)) {
                ProjectIndustryContactMap::where('projectId', $project->id)->delete();
                foreach ($selectedProjectContacts as $pro) {
                    $companyContacts = company::where('user_id', $user_id)->where('company', $pro['company'])
                        ->where('contact_type', 'project')->first();

                    if (!empty($companyContacts)) {
                        ProjectIndustryContactMap::create([
                            'projectId' => $project->id,
                            'company_contact_id' => $companyContacts->id
                        ]);
                    }
                }
            }
            if ($request->has('documents') && !empty($request->has('documents'))) {

                $uploaded = $this->projectDocumentService->storeDocument($request, $project->id); // returns array

            }
            /** Inser Task data */
            $this->projectTaskService->saveProjectTask($request, $project);

            /** final step */
            $this->projectService->saveJobInfo($request, $project, $contract_details);
        });

        return response()->json([
            'status' => true,
            'message' => 'Project saved successfully',
        ], 200);
    }

    public function getUsedStates()
    {
        $states = ProjectDetail::query()
            ->where('project_details.user_id', auth()->id())
            ->join('states', 'states.id', '=', 'project_details.state_id')
            ->groupBy('states.id')
            ->select('states.id', 'states.name', 'states.code')->get();

        return response()->json([
            'status' => true,
            'data' => $states,
        ], 200);
    }
}
