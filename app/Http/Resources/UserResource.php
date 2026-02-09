<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'user_name'  => $this->user_name ?? null,
            'role' => $this->role ?? null,
            'actual_plan' => $this->actual_plan ?? null,
            'custom' => $this->custom ?? null,
            'parent_id' => $this->parent_id ?? null,
            'trial_ends_at' => $this->trial_ends_at ?? null,
        ];
    }
}
