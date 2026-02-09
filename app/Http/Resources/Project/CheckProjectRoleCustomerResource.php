<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckProjectRoleCustomerResource extends JsonResource
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

            'tier_code' => $this->tier_code,
            'tier_limit' => $this->tier_limit,

            'customer' => [
                'id' => $this->customer?->id,
                'name' => $this->customer?->name,
                'description' => $this->customer?->description,
            ],
        ];
    }
}
