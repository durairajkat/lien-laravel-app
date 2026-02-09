<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\CustomerCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerCode::truncate();

        DB::table('customer_codes')->insert([
            [
                'name' => 'Owner',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'General Contractor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sub Contractor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Lessor of equipment',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Sub-Sub Contractor',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
