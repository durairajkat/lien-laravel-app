<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //disable foreign key check for this connection before running seeders
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StateSeeder::class);
        $this->call(ProjectRoleSeeder::class);
        $this->call(ProjectTypeSeeder::class);
        $this->call(TierTableSeeder::class);
        $this->call(PropertyTypeSeeder::class);
        $this->call(CustomerCodeSeeder::class);
    }
}
