<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AllTypeContactResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->contacts?->email,
            'company' => $this->company?->company,
            "website" =>  $this->company?->company,
            "address" =>  $this->company?->company,
            "city" =>  $this->company?->company,
            "state_id" =>  $this->company?->state_id,
            "state" =>  $this->company?->state?->name,
            "zip" =>  $this->company?->zip,
            "phone" =>  $this->company?->phone,
            "fax" =>  $this->company?->fax,
            "companyId" => $this->company?->id,
            "created_at" => $this->contacts?->created_at,
            "contacts" => [
                [
                    "role_id" => $this->contacts?->contact_role_id,
                    "firstName" => $this->contacts?->first_name,
                    "lastName" => $this->contacts?->last_name,
                    "email" => $this->contacts?->email,
                    "directPhone" => $this->contacts?->phone,
                    "cell" => $this->contacts?->cell,
                    "id" => $this->contacts?->id,
                ]
            ],
        ];
    }
}
