<?php

namespace App\Http\Requests\Profile;

use App\Traits\CommonValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'phone' => CommonValidationRules::phone(),
            'office_phone' => CommonValidationRules::phone(false),
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:50'],
            'state_id' => CommonValidationRules::stateId(),
            'zip_code' => CommonValidationRules::zipCode(),
            'country' => ['nullable', 'string', 'max:50'],
            'company_id' => CommonValidationRules::companyId(false),
            'company_name' => ['required', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'url', 'max:100'],
            'image' => CommonValidationRules::imageRules(false),
        ];
    }
}
