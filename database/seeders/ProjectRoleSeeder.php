<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\ProjectRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate the roles table
        ProjectRole::truncate();
        //Insert values to seed table
        DB::table('project_roles')->insert([
            [
                'project_roles' => 'Original Contractor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'project_roles' => 'Subcontractor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'project_roles' => 'Supplier',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'project_roles' => 'Lessor of Equipment',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
