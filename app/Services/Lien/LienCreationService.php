<?php

namespace App\Services\Lien;

use App\Models\LienProvider;
use App\Models\LienProviderStates;
use App\Models\UserDetails;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LienCreationService
{
    public function lienUserAdd($request)
    {
        return DB::transaction(function () use ($request) {

            $userName = ($request->firstName ?? '') . ' ' . ($request->lastName ?? '');

            /**
             * Create or update user
             */
            $user = User::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $userName,
                    'user_name' => $userName,
                    'password' => Hash::make($request->password),
                    'role' => 7,
                    'status' => '0',
                ]
            );

            /**
             * Role name handling
             */
            $roleName = $request->role;

            if (!empty($request->role_other) && $roleName === 'other') {
                $roleName = $request->role_other;
            }

            $logoPath = null;

            /**
             * Upload member image
             */
            if ($request->hasFile('profileImage')) {

                $file = $request->file('profileImage');

                $random = rand(10000, 99999);
                $extension = $file->getClientOriginalExtension();

                $fileName = 'lien_logo _' . $random . '.' . $extension;
                $logoPath = $file->storeAs('lien_provider', $fileName, 'public');
            }

            $data = [
                'company_id' => $request->companyId,
                'company' => $request->newCompanyName,
                'role_name' => $roleName,
                'companyPhone' => $request->companyPhone,
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'address' => $request->address,
                'city' => $request->city,
                'stateId' => $request->states[0] ?? null,
                'zip' => $request->zip,
                'phone' => $request->phone,
                'fax' => $request->fax,
                'email' => $request->email,
            ];
            if ($logoPath) {
                $data['logo'] = $logoPath;
            }

            /**
             * Create or update Lien Provider
             */
            $lienProvider = LienProvider::updateOrCreate(
                ['user_id' => $user->id],
                $data
            );

            /**
             * Update states
             */
            if (!empty($request->states)) {

                LienProviderStates::where('lien_id', $lienProvider->id)->delete();

                foreach ($request->states as $state) {
                    LienProviderStates::create([
                        'lien_id' => $lienProvider->id,
                        'state_id' => $state
                    ]);
                }
            }

            /**
             * Create or update user details
             */
            UserDetails::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company' => $request->newCompanyName,
                    'company_id' => $request->companyId,
                    'first_name' => $request->firstName,
                    'last_name' => $request->lastName,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state_id' => $request->states[0] ?? null,
                    'zip' => $request->zip,
                    'phone' => $request->phone,
                ]
            );

            return $user;
        });
    }
}
