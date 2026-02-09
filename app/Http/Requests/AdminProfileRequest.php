<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProfileRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' =>  'required|email|unique:users,email,' . $this->admin_id,
            'user_company'  =>  'required',
            'user_address'  => 'required',
            'user_city' =>  'required',
            'user_state'    =>  'required',
            'zip'  => 'required',
            'phone' => 'required|numeric',
            'website'   => 'nullable|url',
            'office_phone'  =>  'nullable|numeric',
            'avatar' => 'image|mimes:jpeg,bmp,png',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required'   =>  'First name is required.',
            'last_name.required'    =>  'Last name is required.',
            'email.required'        =>  'Email is required.',
            'email.email'           =>  'Email must be valid.',
            'email.unique'          =>  'This email is already taken.',
            'user_company.required' =>  'Please provide a company name.',
            'user_address.required'   =>  'Please provide an address.',
            'user_city.required'   =>  'Please provide a city.',
            'user_state.required'   =>  'Please provide a state.',
            'zip.required'   =>  'Please provide a zip code.',
            'phone.required'   =>  'Please provide a phone number',
            'phone.numeric'   =>  'The phone number should be a valid phone number. ',
            'website.url'   =>  'Please provide a valid url.',
            'office_phone.numeric'   =>  'The office phone number should be a valid phone number.',
            'avatar.image'  =>  'The profile image must be a valid image type.',
            'avatar.mimes'  =>  'The profile image must be a jpeg, bmp or png file.'
        ];
    }
}
