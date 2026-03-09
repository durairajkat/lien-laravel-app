<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerContactResource extends JsonResource
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
            'first_name' => $this->contacts->first_name,
            'last_name' => $this->contacts->last_name,
            'direct_phone' => $this->contacts->phone,
            'contact_id' => $this->contacts->id,
            'cell' => $this->contacts->cell,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->contacts->email,
            'company_name' => $this->company->company,
            'website' => $this->website,
            'title' => $this->title,
            'city' => $this->city,
            'state' => $this->state->name,
            'zip' => $this->zip,
            'address' => $this->address,
            'companyId' => $this->company_id ?? null,
            'role_id' => $this->contacts?->role_id ?? null,
            'state_id' => $this->state_id ?? null,
            'title' => $this->contacts->contactRole ? $this->contacts->contactRole->name : null,
        ];
    }
}
