<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.

     * Superadmin

     * Agency User

     * Business user

     * Sub User

     *
     * @return void
     */
    public function run(): void
    {
        $now = Carbon::now();

        $types = [

            'Super admin',

            'Agency user',

            'Business user',

            'Sub user',

            'Member',

            'Sub-Member',

            'Lien-Providers',
        ];

        foreach ($types as $type) {
            Role::firstOrCreate(
                ['type' => $type],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
