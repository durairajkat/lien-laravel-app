<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class RemedyDatesResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'remedy_id' => $this->remedy_id,
            'name' => $this->date_name,
            'date_order' => $this->date_order,
            'date_number' => $this->date_number,
            'recurring' => $this->recurring,
            'status' => $this->status,
        ];
    }
}
