<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Password;

use App\User;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{
    public function reset(array $data): string
    {
        return Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                // revoke all tokens (API safety)
                if (method_exists($user, 'tokens')) {
                    $user->tokens()->delete();
                }
            }
        );
    }
}
