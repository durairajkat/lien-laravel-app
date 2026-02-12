<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectSaveRequest;
use App\Models\Company;
use App\Models\ProjectDates;
use App\Models\ProjectDetail;
use App\Services\Project\ProjectContractService;
use Illuminate\Support\Facades\DB;

class ProjectApiController extends Controller
{
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
            $dates = $request->furnishingDates ?? [];
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
            $contractService->saveOrUpdate($project->id, [
                'base_amount' => $request->baseContractAmount,
                'extra_amount' => $request->additionalCosts,
                'credits' => $request->paymentsCredits,
                'general_description' => $request->materialServicesDescription,
                'job_no' => $request->jobProjectNumber,
            ]);

            /** insert Project customer contacts */
            $customer_contacts = $request->customerContacts ?? [];
            $selected_contacts = $request->selectedCustomerContacts ?? '';
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

        });


        return response()->json([
            'status' => true,
            'message' => 'Project saved successfully',
        ], 200);
    }
}
