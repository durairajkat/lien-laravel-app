<?php

namespace Database\Seeders;

use App\Models\CustomerCode;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CustomerCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        $now = Carbon::now();

        $names = [

            'Owner' => 'Property owner (direct contract)',

            'Original Contractor' => 'Prime contractor with direct contract to owner',

            'Subcontractor' => 'Working under general contractor or another subcontractor',

            // 'Lessor of equipment' => 'Leases equipment to a contractor for use on a project',

            'Sub-Subcontractor' => 'Subcontractor working under another subcontractor',
            'Supplier' => 'Provides materials or services to a project',
            'Equipment Supplier' => 'Provides equipment to a project',

        ];

        foreach ($names as $name => $description) {
            CustomerCode::updateOrCreate(
                ['name' => $name],
                ['created_at' => $now, 'updated_at' => $now, 'description' => $description]
            );
        }
    }
}
