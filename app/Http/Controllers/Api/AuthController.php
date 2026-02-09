<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Login user and return an API token
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        $user = $authService->authenticate($request->email, $request->password);

        $token = $user->createToken($request->input('device_name', 'api'))->plainTextToken;

        $projects = $user->projects()->where('status', '1')->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*, project_contracts.total_claim_amount AS unpaid_balance'));
        $active_projects = $projects->get();

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'active_projects' => $active_projects
        ]);
    }
    /**
     * Logout user (Revoke the token)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke only current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

}
