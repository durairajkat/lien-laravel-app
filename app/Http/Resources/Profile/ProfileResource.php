<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user' => [
                'id'    => $this->id,
                'name'  => $this->name,
                'email' => $this->email,
            ],

            'user_details' => $this->whenLoaded('details', function () {
                return [
                    'id'           => $this->details->id,
                    'first_name'   => $this->details->first_name,
                    'last_name'    => $this->details->last_name,
                    'phone'        => $this->details->phone,
                    'office_phone' => $this->details->office_phone,
                    'address'      => $this->details->address,
                    'city'         => $this->details->city,
                    'state_id'     => $this->details->state_id,
                    'zip_code'     => $this->details->zip_code,
                    'country'      => $this->details->country,
                    'image'        => $this->details->image
                        ? Storage::disk('public')->url($this->details->image)
                        : null,
                ];
            }),

            'company' => $this->whenLoaded('company', function () {
                return [
                    'id'       => $this->company->id,
                    'company'  => $this->company->company,
                    'website'  => $this->company->website,
                    'phone'    => $this->company->phone,
                    'address'  => $this->company->address,
                    'city'     => $this->company->city,
                    'state_id' => $this->company->state_id,
                    'zip'      => $this->company->zip,
                ];
            }),
        ];
    }
}
