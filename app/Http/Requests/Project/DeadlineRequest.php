<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class DeadlineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required|exists:project_details,id',
            'state_id' => 'required|exists:states,id',
            'project_type_id' => 'required|exists:project_types,id',
            'role_id' => 'required|exists:project_roles,id',
            'customer_id' => 'required',
        ];
    }
}
