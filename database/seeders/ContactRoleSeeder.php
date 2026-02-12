<?php

namespace Database\Seeders;

use App\Models\Master\ContactRole;
use Illuminate\Database\Seeder;

class ContactRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $roles = [
            'customer' => [
                'CEO',
                'President',
                'Vice President',
                'Manager',
                'Director',
                'Supervisor',
            ],
            'project' => [
                'Architect',
                'Bonding Company',
                'Engineer',
                'General Contractor',
                'Lender',
                'Owner',
                'Sub-Contractor',
                'Title Company',
            ],
        ];

        foreach ($roles as $type => $roleList) {
            foreach ($roleList as $role) {
                ContactRole::updateOrCreate(
                    ['name' => $role],
                    ['role_type' => $type]
                );
            }
        }
    }
}
