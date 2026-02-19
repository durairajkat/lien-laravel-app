<?php

namespace App\Services\Project;

use App\Models\ProjectContract;

class ProjectContractService
{
    public function saveOrUpdate(int $projectId, array $data)
    {
        $contractData = collect([
            'base_amount' => $data['base_amount'] ?? null,
            'extra_amount' => $data['extra_amount'] ?? null,
            'credits' => $data['credits'] ?? null,
            'general_description' => $data['general_description'] ?? null,
            'job_no' => $data['job_no'] ?? null,
        ])->filter(fn ($v) => filled($v));

        if ($contractData->isEmpty()) {
            return;
        }

        $base    = (float) ($data['base_amount'] ?? 0);
        $extra  = (float) ($data['extra_amount'] ?? 0);
        $credit = (float) ($data['credits'] ?? 0);

        return ProjectContract::updateOrCreate(
            ['project_id' => $projectId],
            array_merge(
                $contractData->toArray(),
                [
                    'base_amount' => $base,
                    'extra_amount' => $extra,
                    'credits' => $credit,
                    'total_claim_amount' => ($base + $extra) - $credit,
                ]
            )
        );
    }
}