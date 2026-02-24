<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'task_action_id' => $this->task_action_id,
            'task_name' => $this->task_name,
            'complete_date' => $this->complete_date,
            'due_date' => $this->due_date,
            'comment' => $this->comment,
            'email_alert' => $this->email_alert,
            'assigned_to' => $this->assigned_to,
            'assigned_by' => $this->assigned_by,
            'assigned_at' => $this->assigned_at,
            'status' => $this->status,
            'actionType' => $this->actionType?->name,
        ];
    }
}
