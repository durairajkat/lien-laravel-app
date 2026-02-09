<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackagePrice;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.

     *

     * @return void
     */
    public function run()
    {
        $packages = [
            ['name' => 'Single State', 'is_multiple' => '0'],
            ['name' => 'Multiple States', 'is_multiple' => '1'],
            ['name' => 'All States', 'is_multiple' => '2'],
        ];

        foreach ($packages as $p) {
            Package::firstOrCreate(
                ['name' => $p['name']],
                ['is_multiple' => $p['is_multiple']]
            );
        }

        $prices = [
            ['state_lower_range' => '0', 'state_upper_range' => '1', 'price' => '36'],
            ['state_lower_range' => '2', 'state_upper_range' => '5', 'price' => '108'],
            ['state_lower_range' => '6', 'state_upper_range' => '50', 'price' => '144'],
        ];

        foreach ($prices as $pp) {
            PackagePrice::firstOrCreate(
                ['state_lower_range' => $pp['state_lower_range'], 'state_upper_range' => $pp['state_upper_range']],
                ['price' => $pp['price']]
            );
        }
    }
}
