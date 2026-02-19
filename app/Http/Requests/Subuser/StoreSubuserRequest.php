<?php

namespace App\Http\Requests\Subuser;

use App\Traits\CommonValidationRules;
use C;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubuserRequest extends FormRequest
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
            // 'company_id' => CommonValidationRules::companyId(),

            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],

            'address' => ['nullable', 'string', 'max:255'],
            'city'    => ['nullable', 'string', 'max:100'],
            'state_id' => CommonValidationRules::stateId(),
            'zip_code' => CommonValidationRules::zipCode(),
            'phone' => CommonValidationRules::phone(),

            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            // 'user_name' => ['required', 'string', 'max:100', 'unique:users,user_name'],

            'password' => CommonValidationRules::password(),
        ];
    }
}
