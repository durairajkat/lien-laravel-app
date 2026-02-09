<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->insert([
            ['name' => 'Single State', 'is_multiple' => '0'],
            ['name' => 'Multiple States', 'is_multiple' => '1'],
            ['name' => 'All States', 'is_multiple' => '2']
        ]);

        DB::table('package_prices')->insert([
            ['state_lower_range' => '0', 'state_upper_range' => '1', 'price' => '36'],
            ['state_lower_range' => '2', 'state_upper_range' => '5', 'price' => '108'],
            ['state_lower_range' => '6', 'state_upper_range' => '50', 'price' => '144']
        ]);
    }
}
