<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.

     *

     * @return void
     */
    public function run()
    {
        $password = '123456';

        User::firstOrCreate(
            ['email' => 'superadmin@admin.com'],
            ['name' => 'Super Admin', 'user_name' => 'superadmin', 'password' => $password, 'role' => 1]
        );

        User::firstOrCreate(
            ['email' => 'devadmin@admin.com'],
            ['name' => 'Dev Admin', 'user_name' => 'devadmin', 'password' => $password, 'role' => 1, 'is_dev' => true]
        );
    }
}
