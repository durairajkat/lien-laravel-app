<?php

namespace App\Services\Profile;

use App\Models\Company;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Storage;

class UserDetailService
{
    public function updateUserDetails(?Company $companyInfo, $userId, array $data): void
    {
        // Implementation for updating user details

        $userDetails = UserDetails::where('user_id', $userId)->first();

        $imagePath = $userDetails?->image;

        if (!empty($data['image'])) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $data['image']->store('profile-images', 'public');
        }

        $userDetailsData = [
            'company_id' => $companyInfo?->id ?? null,
            'company'    => $companyInfo?->company ?? null,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'phone'      => $data['phone'],
            'address'    => $data['address'] ?? null,
            'city'       => $data['city'] ?? null,
            'state_id'   => $data['state_id'],
            'zip'        => $data['zip_code'],
            'image'      => $imagePath,
        ];

        if (!empty($data['office_phone'])) {
            $userDetailsData['office_phone'] = $data['office_phone'];
        }

        if (!empty($data['country'])) {
            $userDetailsData['country'] = $data['country'];
        }

        UserDetails::updateOrCreate(
            ['user_id' => $userId],
            $userDetailsData
        );
    }
}
