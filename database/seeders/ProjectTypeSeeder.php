<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.

     *

     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        //Insert values to seed table

        $names = [
            'Private' => 'Residential or commercial private property',
            'Public' => 'State, county, or municipal government project',
            'Federal' => 'Federal government project (Miller Act)',
        ];

        foreach ($names as $name => $description) {
            ProjectType::updateOrCreate(
                ['project_type' => $name],
                ['created_at' => $now, 'updated_at' => $now, 'description' => $description]
            );
        }
    }
}
