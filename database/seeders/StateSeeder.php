<?php

namespace Database\Seeders;

use App\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
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

        $states = [

            ['name' => 'Alabama', 'code' => '01', 'short_code' => 'AL'],

            ['name' => 'Alaska', 'code' => '02', 'short_code' => 'AK'],

            ['name' => 'Arizona', 'code' => '04', 'short_code' => 'AZ'],

            ['name' => 'Arkansas', 'code' => '05', 'short_code' => 'AR'],

            ['name' => 'California', 'code' => '06', 'short_code' => 'CA'],

            ['name' => 'Colorado', 'code' => '08', 'short_code' => 'CO'],

            ['name' => 'Connecticut', 'code' => '09', 'short_code' => 'CT'],

            ['name' => 'Delaware', 'code' => '10', 'short_code' => 'DE'],

            ['name' => 'District of Columbia', 'code' => '11', 'short_code' => 'DC'],

            ['name' => 'Florida', 'code' => '12', 'short_code' => 'FL'],

            ['name' => 'Georgia', 'code' => '13', 'short_code' => 'GA'],

            ['name' => 'Hawaii', 'code' => '15', 'short_code' => 'HI'],

            ['name' => 'Idaho', 'code' => '16', 'short_code' => 'ID'],

            ['name' => 'Illinois', 'code' => '17', 'short_code' => 'IL'],

            ['name' => 'Indiana', 'code' => '18', 'short_code' => 'IN'],

            ['name' => 'Iowa', 'code' => '19', 'short_code' => 'IA'],

            ['name' => 'Kansas', 'code' => '20', 'short_code' => 'KS'],

            ['name' => 'Kentucky', 'code' => '21', 'short_code' => 'KY'],

            ['name' => 'Louisiana', 'code' => '22', 'short_code' => 'LA'],

            ['name' => 'Maine', 'code' => '23', 'short_code' => 'ME'],

            ['name' => 'Maryland', 'code' => '24', 'short_code' => 'MD'],

            ['name' => 'Massachusetts', 'code' => '25', 'short_code' => 'MA'],

            ['name' => 'Michigan', 'code' => '26', 'short_code' => 'MI'],

            ['name' => 'Minnesota', 'code' => '27', 'short_code' => 'MN'],

            ['name' => 'Mississippi', 'code' => '28', 'short_code' => 'MS'],

            ['name' => 'Missouri', 'code' => '29', 'short_code' => 'MO'],

            ['name' => 'Montana', 'code' => '30', 'short_code' => 'MT'],

            ['name' => 'Nebraska', 'code' => '31', 'short_code' => 'NE'],

            ['name' => 'Nevada', 'code' => '32', 'short_code' => 'NV'],

            ['name' => 'New Hampshire', 'code' => '33', 'short_code' => 'NH'],

            ['name' => 'New Jersey', 'code' => '34', 'short_code' => 'NJ'],

            ['name' => 'New Mexico', 'code' => '35', 'short_code' => 'NM'],

            ['name' => 'New York', 'code' => '36', 'short_code' => 'NY'],

            ['name' => 'North Carolina', 'code' => '37', 'short_code' => 'NC'],

            ['name' => 'North Dakota', 'code' => '38', 'short_code' => 'ND'],

            ['name' => 'Ohio', 'code' => '39', 'short_code' => 'OH'],

            ['name' => 'Oklahoma', 'code' => '40', 'short_code' => 'OK'],

            ['name' => 'Oregon', 'code' => '41', 'short_code' => 'OR'],

            ['name' => 'Pennsylvania', 'code' => '42', 'short_code' => 'PA'],

            ['name' => 'Rhode Island', 'code' => '44', 'short_code' => 'RI'],

            ['name' => 'South Carolina', 'code' => '45', 'short_code' => 'SC'],

            ['name' => 'South Dakota', 'code' => '46', 'short_code' => 'SD'],

            ['name' => 'Tennessee', 'code' => '47', 'short_code' => 'TN'],

            ['name' => 'Texas', 'code' => '48', 'short_code' => 'TX'],

            ['name' => 'Utah', 'code' => '49', 'short_code' => 'UT'],

            ['name' => 'Vermont', 'code' => '50', 'short_code' => 'VT'],

            ['name' => 'Virginia', 'code' => '51', 'short_code' => 'VA'],

            ['name' => 'Washington', 'code' => '53', 'short_code' => 'WA'],

            ['name' => 'West Virginia', 'code' => '54', 'short_code' => 'WV'],

            ['name' => 'Wisconsin', 'code' => '55', 'short_code' => 'WI'],

            ['name' => 'Wyoming', 'code' => '56', 'short_code' => 'WY'],

        ];

        foreach ($states as $s) {
            State::firstOrCreate(
                ['short_code' => $s['short_code']],
                ['name' => $s['name'], 'code' => $s['code'], 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
