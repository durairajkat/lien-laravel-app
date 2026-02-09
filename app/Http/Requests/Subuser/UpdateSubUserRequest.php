<?php

namespace App\Http\Requests\Subuser;

use App\Traits\CommonValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('sub_user'); // route model binding OR ID
        return [
            'company_id' => CommonValidationRules::companyId(),

            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],

            'address' => ['required', 'string', 'max:255'],
            'city'    => ['required', 'string', 'max:100'],
            'state_id' => CommonValidationRules::stateId(),
            'zip_code' => CommonValidationRules::zipCode(),
            'phone' => CommonValidationRules::phone(),

            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'user_name' => ['required', 'string', 'max:100', 'unique:users,user_name,' . $user->id],

            // password optional on update
            'password' => CommonValidationRules::password(false),
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone number must be exactly 10 digits.',
        ];
    }
}
