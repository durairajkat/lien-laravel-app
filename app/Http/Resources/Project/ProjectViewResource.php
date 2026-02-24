<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\master\CountyResource;
use App\Http\Resources\master\ProjectRoleResource;
use App\Http\Resources\master\ProjectTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectViewResource extends JsonResource
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
            'projectName' => $this->project_name,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'stateId' => $this->state_id,
            'countryId' => $this->county_id,
            'projectTypeId' => $this->project_type_id,
            'customerTypeId' =>  $this->customer_id,
            'roleId' => $this->role_id,
            'jobName' => $this->description,
            'jobAddress' => $this->address,
            'jobCity' => $this->city,
            'jobZip' => $this->zip,
            'jobCountyId' => $this->county_id,
            'startDate' => $this->start_date,
            'endDate' => $this->esitmated_end_date,
            'created_at' => $this->created_at,
            'tasks' => TaskResource::collection($this->tasks),
            'documents' => $this->whenLoaded('documents'),
            'contracts' => new ProjectContractResource(
                $this->whenLoaded('project_contract')
            ),
            'projectType' => new ProjectTypeResource(
                $this->whenLoaded('project_type')
            ),
            'projectRole' => new ProjectRoleResource(
                $this->whenLoaded('role')
            ),
            'customerType' => $this->whenLoaded(
                'originalCustomer',
                fn() => $this->originalCustomer?->name
            ),
            'jobCounty' => new CountyResource(
                $this->whenLoaded('countyInfo')
            ),
            'country' => $this->whenLoaded(
                'state',
                fn() => $this->state?->country?->name
            ),
            'state' => $this->whenLoaded(
                'state',
                fn() => $this->state?->name
            ),
            'signatureDate' => $this->whenLoaded(
                'jobInfo',
                fn() => $this->jobInfo?->signature_date
            ),
            'signature' => $this->whenLoaded(
                'jobInfo',
                fn() => $this->jobInfo?->signature
            ),
        ];
    }
}
