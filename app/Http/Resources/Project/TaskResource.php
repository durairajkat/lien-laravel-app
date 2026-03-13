<?php

namespace App\Http\Resources\Project;

use Carbon\Carbon;
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
        $priority = 'normal';

        if ($this->complete_date) {
            $priority = 'completed';
        } elseif ($this->due_date) {

            $dueDate = Carbon::parse($this->due_date);
            $today = Carbon::today();

            if ($dueDate->lt($today)) {
                $priority = 'overdue';
            } elseif ($dueDate->diffInDays($today) <= 3) {
                $priority = 'near_due';
            }
        }
        
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
            'status' => ucwords(str_replace('_', ' ', $this->status)),
            'actionType' => $this->actionType?->name,
            'assigned_to_user' => $this->assignedToUser?->name,
            'priority' => $priority
        ];
    }
}
