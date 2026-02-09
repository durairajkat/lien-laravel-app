<?php

namespace Database\Seeders;

use App\Models\TierTable;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $tiers = [
            ['role_id' => 1, 'tier_limit' => 'GC Sold to O',        'customer_id' => 1, 'tier_code' => 'GC',  'tier_coverage_id' => 'tier1'],
            ['role_id' => 2, 'tier_limit' => 'SC Sold to O',        'customer_id' => 1, 'tier_code' => 'S1',  'tier_coverage_id' => 'tier1'],
            ['role_id' => 3, 'tier_limit' => 'S Sold to O',         'customer_id' => 1, 'tier_code' => 'M11', 'tier_coverage_id' => 'tier1'],
            ['role_id' => 4, 'tier_limit' => 'L Sold to O',         'customer_id' => 1, 'tier_code' => 'L1',  'tier_coverage_id' => 'tier1'],

            ['role_id' => 2, 'tier_limit' => 'SC Sold to GC',       'customer_id' => 2, 'tier_code' => 'S2',  'tier_coverage_id' => 'tier2'],
            ['role_id' => 3, 'tier_limit' => 'S Sold to GC',        'customer_id' => 2, 'tier_code' => 'M22', 'tier_coverage_id' => 'tier2'],
            ['role_id' => 4, 'tier_limit' => 'L Sold to GC',        'customer_id' => 2, 'tier_code' => 'L2',  'tier_coverage_id' => 'tier2'],

            ['role_id' => 2, 'tier_limit' => 'SC Sold to SC',       'customer_id' => 3, 'tier_code' => 'S3',  'tier_coverage_id' => 'tier3'],
            ['role_id' => 3, 'tier_limit' => 'S Sold to SC',        'customer_id' => 3, 'tier_code' => 'M33', 'tier_coverage_id' => 'tier3'],
            ['role_id' => 4, 'tier_limit' => 'L Sold to SC',        'customer_id' => 3, 'tier_code' => 'L3',  'tier_coverage_id' => 'tier3'],

            ['role_id' => 2, 'tier_limit' => 'SC Sold to SC to SC', 'customer_id' => 5, 'tier_code' => 'S4',  'tier_coverage_id' => 'tier4'],
            ['role_id' => 3, 'tier_limit' => 'S Sold to Sub-Sub',   'customer_id' => 5, 'tier_code' => 'M44', 'tier_coverage_id' => 'tier4'],
            ['role_id' => 4, 'tier_limit' => 'L Sold to SC-SC',     'customer_id' => 5, 'tier_code' => 'L4',  'tier_coverage_id' => 'tier4'],
        ];

        foreach ($tiers as $tier) {
            TierTable::firstOrCreate(
                [
                    'role_id'          => $tier['role_id'],
                    // 'customer_id'      => $tier['customer_id'],
                    // 'tier_code'        => $tier['tier_code'],
                    // 'tier_coverage_id' => $tier['tier_coverage_id'],
                ],
                [
                    'tier_limit' => $tier['tier_limit'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
