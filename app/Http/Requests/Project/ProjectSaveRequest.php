<?php

namespace App\Http\Requests\Project;

use App\Models\ProjectDetail;
use App\Traits\CommonValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class ProjectSaveRequest extends FormRequest
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
            'projectName' => 'required|string|max:255',
            'projectTypeId' => 'required|exists:project_types,id',
            'countryId' => 'required|exists:countries,id',
            'stateId' => 'required|exists:states,id',
            'roleId' => 'required|exists:project_roles,id',
            'customerTypeId' => 'required|exists:customer_codes,id',
            'county_id' => 'nullable|exists:counties,id',
            'jobZip' => CommonValidationRules::zipCode(false),
            
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $exists = ProjectDetail::where('project_name', $this->projectName)
                ->where('user_id', auth()->id())
                ->where('state_id', $this->stateId)
                ->where('project_type_id', $this->projectTypeId)
                ->where('customer_id', $this->customerTypeId)
                ->where('role_id', $this->roleId)
                ->when($this->projectId, function ($query) {
                    $query->where('id', '!=', $this->projectId);
                })
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'projectError',
                    'Project already created with the same details.'
                );
            }
        });
    }
}
