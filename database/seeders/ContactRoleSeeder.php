<?php

namespace Database\Seeders;

use App\Helpers\StringHelper;
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
        ContactRole::truncate();
        foreach ($roles as $type => $roleList) {
            foreach ($roleList as $role) {
                ContactRole::updateOrCreate(
                    [
                        'name' => $role,
                        'role_type' => $type,
                    ],
                    [
                        'normalized_name' => StringHelper::normalizeString($role),
                    ]
                );
            }
        }
    }
}
