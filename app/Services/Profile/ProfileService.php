<?php

namespace App\Services\Profile;

use App\Models\Company;
use App\Models\UserDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\User;

class ProfileService
{

    public function __construct(
        protected UserDetailService $userDetailsService
    ) {}

    public function update(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data) {

            /* -------------------------
             | 1. Update User Name
             --------------------------*/
            $user->update([
                'name' => trim($data['first_name'] . ' ' . $data['last_name']),
            ]);

            /* -------------------------
             | 2. Company Create / Update
             --------------------------*/
            $companyData = [
                'company'  => $data['company_name'],
                'website'  => $data['website'] ?? null,
                'state_id' => $data['state_id'],
                'address'  => $data['address'],
                'city'     => $data['city'],
                'zip'      => $data['zip_code'],
            ];

            if (!empty($data['office_phone'])) {
                $companyData['phone'] = $data['office_phone'];
            }

            $company = Company::updateOrCreate(
                ['user_id' => $user->id],
                $companyData
            );

            /* -------------------------
             | 3. User Details Updation
             --------------------------*/
            $this->userDetailsService->updateUserDetails($company, $user->id, $data);
        });
    }
}
