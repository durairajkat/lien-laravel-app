<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LienProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        $contact = optional($this->customer_contract?->getContacts?->first());

        return [
            'id' => $this->id,
            'project_name' => $this->project_name,
            'company_name' => $this->city,
            'state' => $this->state,
            'project_contract' => $this->base_amount,
            'status' => $this->status,
            'project_type' => $this->project_type?->project_type,
            'customer_name' => $contact->first_nmae ?? null .' '.$contact->last_name ?? null,
            'created_at' => $this->created_at,
            'lien_provider' => Auth::user()->lienUser->firstName ?? '' . ' '.Auth::user()->lienUser->lastName,
            'user_id' => $this->user_id
        ];
    }
}