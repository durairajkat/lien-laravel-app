<?php

namespace App\Services\Project;

use App\Models\Company;
use App\Models\JobInfo;
use App\Models\JobInfoFiles;
use App\Models\ProjectDocument;
use App\Models\Remedy;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\TierRemedyStep;
use App\Models\TierTable;

class ProjectService
{
    public function checkProjectRoleCustomers($request)
    {
        $remedy = Remedy::where('state_id', $request->state_id)
            ->where('project_type_id', $request->project_type_id);
        $remdey_date = RemedyDate::whereIn('remedy_id', $remedy->pluck('id'));
        $remedyStep = RemedyStep::whereIn('remedy_date_id', $remdey_date->pluck('id'));
        $tier = TierTable::where('role_id', $request->role_id);
        $tierRemedyStep = TierRemedyStep::whereIn('remedy_step_id', $remedyStep->pluck('id'))->whereIn('tier_id', $tier->pluck('id'));
        return TierTable::whereIn('id', $tierRemedyStep->pluck('tier_id'))->with('customer')->get();
    }

    public function saveJobInfo($request, $project, $contract_details)
    {
        $customerSignature = $request->customerSignature ?? '';
        $signatureDate = $request->signatureDate ?? '';

        if ($customerSignature && $signatureDate) {
            /** Get company details */
            $company = Company::where('user_id', $project->user_id)->first();
            /** Insert in Job Info */
            $ins_job = [
                'company_name' => $company->company ?? null,
                'company_address' => $company->address ?? null,
                'company_city' => $company->city ?? null,
                'company_zip' => $company->city ?? null,
                'company_state' => $company->state ?? null,
                'customer_company_id' => $project->user_id,
                'contract_amount' => $contract_details->total_claim_amount,
                'first_day_of_work' => null,
                'is_gc' => null,
                'signature' => $request->customerSignature ?? null,
                'signature_date' => $request->signatureDate ?? null,
                'status' => 1,
            ];

            $jobInfo = JobInfo::updateOrCreate(['project_id' => $project->id], $ins_job);
            if ($jobInfo && $request->has('documents') && !empty($request->has('documents'))) {
                $files = ProjectDocument::where('project_id', $project->id)->get();
                if (!empty($files)) {
                    JobInfoFiles::where('job_info_id', $jobInfo->id)->delete();
                    foreach ($files as $file) {
                        JobInfoFiles::create([
                            'job_info_id' => $jobInfo->id,
                            'file' => $file->filename
                        ]);
                    }
                }
            }
        }
    }
}
