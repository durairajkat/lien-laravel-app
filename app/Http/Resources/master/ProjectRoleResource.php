<?php

namespace App\Http\Resources\master;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectRoleResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'project_role' => $this->project_roles,
            'description'  => $this->description,
        ];
    }
}
