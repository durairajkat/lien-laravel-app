<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\UserDetails;
use App\Models\LienProvider;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // User
        $user = User::firstOrCreate(
            ['email' => 'nlb@nlb.com'],
            [
                'name'      => 'NLB',
                'user_name' => 'NLB',
                'password'  => Hash::make('123456'),
                'role'      => 7,
                'status'    => 0,
                'created_at'=> $now,
                'updated_at'=> $now,
            ]
        );

        // Company
        $company = Company::firstOrCreate(
            [
                'company' => 'National Lien & Bond Claim Systems',
                'user_id' => $user->id,
            ],
            [
                'state_id'   => 1,
                'created_at'=> $now,
                'updated_at'=> $now,
            ]
        );

        // User Details
        UserDetails::firstOrCreate(
            ['user_id' => $user->id],
            [
                'company'     => $company->company,
                'address'     => 'NLB',
                'city'        => 'NLB',
                'zip'         => '50001',
                'phone'       => '1234567890',
                'company_id'  => $company->id,
                'lien_status' => 0,
                'state_id'    => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]
        );

        // Lien Provider
        LienProvider::firstOrCreate(
            [
                'email'    => 'nlb@nlb.com',
                'user_id'  => $user->id,
            ],
            [
                'company_id'  => $company->id,
                'company'     => $company->company,
                'firstName'   => 'NLB',
                'lastName'    => 'NLB',
                'address'     => 'NLB',
                'stateId'     => 1,
                'city'        => 'NLB',
                'zip'         => '50001',
                'phone'       => '1234567890',
                'is_deletable'=> 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]
        );
    }
}
