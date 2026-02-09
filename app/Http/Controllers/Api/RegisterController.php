<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\RegistrationService;

class RegisterController extends Controller
{
    public function signup(SignupRequest $request, RegistrationService $registrationService)
    {

        $user = $registrationService->register(
            $request->validated()
        );

        // Sanctum token (API only)
        $token = $user->createToken(
            $request->input('device_name', 'api')
        )->plainTextToken;

        return response()->json([
            'message' => 'Registration successful. Your account is pending activation.',
            'user'    => new UserResource($user),
            'token'   => $token,
        ], 201);
        
    }
}
