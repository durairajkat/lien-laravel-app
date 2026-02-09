<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryAndSoftdeletesToStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ✅ Step 1: Add country_id + deleted_at safely
        Schema::table('states', function (Blueprint $table) {

            // Add country_id only if not exists
            if (!Schema::hasColumn('states', 'country_id')) {
                $table->unsignedBigInteger('country_id')
                    ->nullable()
                    ->after('id');
            }

            // Add softDeletes only if not exists
            if (!Schema::hasColumn('states', 'deleted_at')) {
                $table->softDeletes();
            }
        });


        // ✅ Step 2: Ensure USA exists in countries table
        $usaId = DB::table('countries')->updateOrInsert(
            ['code' => 'US'],
            ['name' => 'United States']
        );

        // Get USA country ID
        $usa = DB::table('countries')->where('code', 'US')->first();


        // ✅ Step 3: Map all existing states to USA
        if ($usa) {
            DB::table('states')
                ->whereNull('country_id')
                ->update(['country_id' => $usa->id]);
        }


        // ✅ Step 4: Add Foreign Key constraint safely
        Schema::table('states', function (Blueprint $table) {

            // Add FK only if not already added
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'states'
                AND COLUMN_NAME = 'country_id'
                AND REFERENCED_TABLE_NAME = 'countries'
            ");

            if (empty($foreignKeys)) {
                $table->foreign('country_id')
                    ->references('id')
                    ->on('countries')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('states', function (Blueprint $table) {
            // Drop FK first
            if (Schema::hasColumn('states', 'country_id')) {
                $table->dropForeign(['country_id']);
                $table->dropColumn('country_id');
            }

            // Drop soft delete column
            if (Schema::hasColumn('states', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}
