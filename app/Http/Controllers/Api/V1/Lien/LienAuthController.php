<?php

namespace App\Http\Controllers\Api\V1\Lien;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LienSignupRequest;
use App\Http\Resources\UserResource;
use App\Services\Lien\LienCreationService;

class LienAuthController extends Controller
{
    public function __construct(protected LienCreationService $lien_creation_service) {}

    public function signup(LienSignupRequest $request)
    {

        $userInfo = $this->lien_creation_service->lienUserAdd($request);

        $token = $userInfo->createToken(
            $request->input('device_name', 'api')
        )->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user'    => new UserResource($userInfo),
            'token'   => $token,
        ], 201);
    }
}
