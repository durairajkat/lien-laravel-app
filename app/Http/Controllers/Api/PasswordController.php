<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    /**
     * Send reset password link
     */
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json([
                'message' => 'Password reset link sent to your email.'
            ])
            : response()->json([
                'message' => 'Unable to send reset link.'
            ], 400);
    }

    /**
     * Reset password
     */
    public function reset(ResetPasswordRequest $request, PasswordResetService $passwordResetService)
    {
       
        $status = $passwordResetService->reset(
            $request->validated()
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json([
                'message' => 'Password reset successful.'
            ])
            : response()->json([
                'message' => 'Invalid token or email.'
            ], 400);
    }
}
