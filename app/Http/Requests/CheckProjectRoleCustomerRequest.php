<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckProjectRoleCustomerRequest extends FormRequest
{
   
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
            'state_id' => 'required|exists:states,id',
            'project_type_id' => 'required|exists:project_types,id',
            'role_id' => 'required|exists:project_roles,id'
        ];
    }
}
