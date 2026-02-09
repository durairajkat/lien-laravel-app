<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate the user table
        User::truncate();
        //Insert values to seed table
        $user = new User();
        $user->name = 'Super Admin';
        $user->user_name = 'superadmin';
        $user->email = 'superadmin@admin.com';
        $user->password = '123456';
        $user->role = 1;
        $user->save();
    }
}
