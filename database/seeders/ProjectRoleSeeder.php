<?php

namespace Database\Seeders;

use App\Models\ProjectRole;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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

        $now = Carbon::now();

        //Insert values to seed table

        $names = [

            'Original Contractor' => 'Prime/general contractor with direct contract to owner',

            'Subcontractor' => 'Working under general contractor or another subcontractor',

            'Supplier' => 'Provides materials or services to a project',

            'Lessor of Equipment' => 'Renting or leasing equipment for the project',
        ];

        foreach ($names as $name => $description) {
            ProjectRole::updateOrCreate(
                ['project_roles' => $name],
                ['created_at' => $now, 'updated_at' => $now, 'description' => $description]
            );
        }
    }
}
