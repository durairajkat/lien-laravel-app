<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\ProjectType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate the roles table
        ProjectType::truncate();
        //Insert values to seed table
        DB::table('project_types')->insert([
            [
                'project_type' => 'Private',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'project_type' => 'Public',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'project_type' => 'Federal',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
