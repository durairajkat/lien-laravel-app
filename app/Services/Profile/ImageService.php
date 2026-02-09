<?php

namespace App\Services\Profile;

use App\User;
use App\Models\UserDetails;

class ImageService
{
    public function updateProfileImage(User $user, $file): ?string
    {
        $userDetails = UserDetails::where('user_id', $user->id)->first();

        $oldImage = $userDetails?->image;

        if ($oldImage) {
            \Storage::disk('public')->delete($oldImage);
        }

        $newPath = $file->store('profile-images', 'public');

        UserDetails::updateOrCreate(
            ['user_id' => $user->id],
            ['image' => $newPath]
        );

        return $newPath;
    }
}
