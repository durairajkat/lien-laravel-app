<?php

namespace App\Http\Requests\Auth;

use App\Traits\CommonValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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

     public function rules(): array
    {
        return [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => CommonValidationRules::password(),
        ];
    }

    /**
     * Optional: custom messages
     */
    public function messages(): array
    {
        return [
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
