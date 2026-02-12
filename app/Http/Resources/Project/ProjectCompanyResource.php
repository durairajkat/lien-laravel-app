<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCompanyResource extends JsonResource
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
            'user_id' => $this->user_id,
            'role_id' => optional($this->contacts->first())->contact_role_id,
            'company' => $this->company,
            'website' => $this->website,
            'address' => $this->address,
            'city' => $this->city,
            'state_id' => $this->state_id,
            'zip' => $this->zip,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'contacts'  => ProjectContactResource::collection(
                $this->whenLoaded('contacts')
            ),
            
        ];
    }
}
