<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'user_name'      => $this->user_name,

            // details
            'phone'      => optional($this->details)->phone,
            'office_phone'      => optional($this->details)->office_phone,
            'zip'        => optional($this->details)->zip,
            'state'      => optional(optional($this->details)->state)->name,
            'city'      => optional($this->details)->city,
            'country'    => optional($this->details)->country,
            'state_id'  => optional($this->details)->state_id,
            'address'  => optional($this->details)->address,
            'first_name'  => optional($this->details)->first_name,
            'last_name'  => optional($this->details)->last_name,
            'company_id'  => optional($this->details)->company_id,

            'company_name' => optional($this->details?->getCompany)->company,
            'website' => optional($this->details?->getCompany)->website,

            // meta
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
