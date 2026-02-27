<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\ProjectDocumentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDocumentDetailCollectionResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'project_id' => $this->id,
            'project_name' => $this->project_name,
            'customer_name' => $this->customer_name ?? null,
            'created_at' => $this->created_at,

            'documents' => ProjectDocumentResource::collection(
                $this->whenLoaded('documents')
            ),
        ];
    }
}
