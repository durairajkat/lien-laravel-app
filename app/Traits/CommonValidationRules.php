<?php

namespace App\Traits;

use Illuminate\Validation\Rules\Password;

trait CommonValidationRules
{
    public static function phone(bool $required = true): array
    {
        return $required
            ? ['required', 'digits:10']
            : ['nullable', 'digits:10'];
    }

    public static function stateId(bool $required = true): array
    {
        return $required
            ? ['required', 'integer', 'exists:states,id']
            : ['nullable', 'integer', 'exists:states,id'];
    }

    public static function zipCode(bool $required = true): array
    {
        return $required
            ? ['required', 'regex:/^\d{3,7}$/']
            : ['nullable', 'regex:/^\d{3,7}$/'];
    }

    public static function companyId(bool $required = true): array
    {
        return $required
            ? ['required', 'integer', 'exists:companies,id']
            : ['nullable', 'integer', 'exists:companies,id'];
    }

    public static function imageRules(bool $required = true): array
    {
        return $required
            ? ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
            : ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
    }

    public static function password(bool $required = true): array
    {
        return [
                $required ? 'required' : 'nullable',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
        ];
    }
}