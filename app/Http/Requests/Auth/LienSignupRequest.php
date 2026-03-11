<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LienSignupRequest extends FormRequest
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
            'companyId' => 'nullable|integer',
            'newCompanyName' => 'required|string|max:255',

            'companyPhone' => 'nullable|digits:10',
            'fax' => 'nullable|digits:10',

            'role' => 'required|string',

            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'phone' => 'nullable|digits:10',

            'address' => 'nullable|string',
            'city' => 'nullable|string',

            'states' => 'required|array|min:1',
            'states.*' => 'integer|exists:states,id',

            'zip' => 'nullable|string|max:10',

            'password' => 'required|string|min:8|confirmed',

            'profileImage' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ];
    }
}
