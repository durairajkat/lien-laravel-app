<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.

     *

     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $names = [
            'Residential',
            'Commercial',
        ];

        foreach ($names as $name) {
            PropertyType::firstOrCreate(
                ['name' => $name],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
