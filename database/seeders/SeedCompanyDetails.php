<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\UserDetails;
use Illuminate\Database\Seeder;
use App\Models\MapCompanyContact;

class SeedCompanyDetails extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userDetails = UserDetails::whereNull('company_id')->get();
        foreach ($userDetails as $userDetail) {
            if (is_null($userDetail->user->parent_id)) {
                $company = new Company();
                $company->user_id = $userDetail->user_id;
                $company->company = $userDetail->company;
                $company->website = $userDetail->website;
                $company->address = $userDetail->address;
                $company->city = $userDetail->city;
                $company->state_id = $userDetail->state_id;
                $company->zip = $userDetail->zip;
                $company->phone = $userDetail->phone;
                $company->fax = $userDetail->fax;
                $company->save();

                $userDetail->company_id = $company->id;
                $userDetails->update();
            } else {
                $parentUser = $userDetail->user->parentUser;
                $parentCompany = $parentUser->details->company_id;
                $company = new MapCompanyContact();
                $company->company_id = $parentCompany;
                $company->user_id = $userDetail->user_id;
                $company->is_user = '1';
                $company->address = $userDetail->address;
                $company->city = $userDetail->city;
                $company->state_id = $userDetail->state_id;
                $company->zip = $userDetail->zip;
                $company->phone = $userDetail->phone;
                $company->fax = $userDetail->fax;
                $company->save();

                $userDetail->company_id = $parentCompany;
                $userDetail->update();
            }
        }
    }
}
