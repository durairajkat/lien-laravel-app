<?php

namespace Database\Seeders;

use App\User;
use App\Models\Company;
use App\Models\UserDetails;
use App\Models\LienProvider;
use Illuminate\Database\Seeder;

class LienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = new User();
        $user->name = 'NLB';
        $user->user_name = 'NLB';
        $user->email = 'nlb@nlb.com';
        $user->password = '123456';
        $user->role = '7';
        $user->status = '0';
        $user->save();

        $company = new Company();
        $company->company = 'National Lien & Bond Claim Systems';
        $company->user_id = $user->id;
        $company->state_id = '1';
        $company->save();

        $userDetails = new UserDetails();
        $userDetails->user_id = $user->id;
        $userDetails->company = $company->company;
        $userDetails->address = 'NLB';
        $userDetails->city = 'NLB';
        $userDetails->zip = '50001';
        $userDetails->phone = '1234567890';
        $userDetails->company_id = $company->id;
        $userDetails->lien_status = '0';
        $userDetails->state_id = '1';
        $userDetails->save();

        $lienProvider = new LienProvider();
        $lienProvider->company_id = $company->id;
        $lienProvider->user_id = $user->id;
        $lienProvider->company = $company->company;
        $lienProvider->firstName = 'NLB';
        $lienProvider->lastName = 'NLB';
        $lienProvider->address = 'NLB';
        $lienProvider->stateId = '1';
        $lienProvider->city = 'NLB';
        $lienProvider->zip = '50001';
        $lienProvider->phone = '1234567890';
        $lienProvider->email = 'nlb@nlb.com';
        $lienProvider->is_deletable = '0';
        $lienProvider->save();
    }
}
