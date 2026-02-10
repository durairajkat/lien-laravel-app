<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('state_and_county_fips_master.csv');

        if (!File::exists($path)) {
            $this->command->error('County file not found in public folder.');
            return;
        }

        $rows = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        unset($rows[0]);
        foreach ($rows as $row) {
            [$id, $county, $state_code] = str_getcsv($row);
            if (!empty($county) && $state_code != 'NA') {
                $stateId = DB::table('states')
                    ->where('short_code', $state_code)
                    ->value('id');

                if (!$stateId) {
                    continue;
                }
                DB::table('counties')->updateOrInsert(
                    [
                        'county_fips_code'  => $id,
                        'state_id' => $stateId,
                    ],
                    [
                        'name'       => $county,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }



        }

        $this->command->info('US counties seeded successfully.');
    }
}
