<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateProfileImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // ✅ important for sanctum
    }

    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // 2MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Profile image is required.',
            'image.image'    => 'Uploaded file must be an image.',
            'image.mimes'    => 'Image must be jpg, jpeg, png, or webp.',
            'image.max'      => 'Image size must not exceed 2 MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
