<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileImageRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Services\Profile\ImageService;
use App\Services\Profile\PasswordService;
use App\Services\Profile\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateProfile(UpdateProfileRequest $request, ProfileService $profileService)
    {
        $profileService->update(
            auth()->user(),
            $request->validated() + ['image' => $request->file('image')]
        );

        return response()->json([
            'message' => 'Profile updated successfully',
        ]);
    }

    public function getProfile()
    {
        $user = Auth::user()
            ->refresh()
            ->load(['details', 'company']);

        return new ProfileResource($user);
    }

    public function updateProfileImage(
        UpdateProfileImageRequest $request,
        ImageService $imageService
    ) {
        $path = $imageService->updateProfileImage(
            auth()->user(),
            $request->file('image')
        );

        return response()->json([
            'message' => 'Profile image updated successfully',
            'image'   => Storage::disk('public')->url($path),
        ]);
    }

    public function updatePassword(
        UpdatePasswordRequest $request,
        PasswordService $passwordService
    ) {

        $passwordService->update(
            auth()->user(),
            $request->current_password,
            $request->password
        );

        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Password updated successfully. Please login again.',
            'logout'  => true,
        ], 200);
    }
}
