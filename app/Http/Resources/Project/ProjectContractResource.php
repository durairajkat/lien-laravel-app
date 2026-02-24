<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'baseContractAmount' => $this->base_amount,
            'additionalCosts' => $this->extra_amount,
            'paymentsCredits' => $this->credits,
            'waiver' => $this->waiver,
            'total_claim_amount' => $this->total_claim_amount,
            'jobProjectNumber' => $this->job_no,
            'materialServicesDescription' => $this->general_description,
        ];
    }
}
