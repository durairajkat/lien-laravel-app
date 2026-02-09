<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeysMapCompanyContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('map_company_contacts', function (Blueprint $table) {
            // $table->dropForeign(['company_id']);
            // $table->dropForeign(['company_contact_id']);
            // $table->dropForeign(['user_id']);
            // $table->dropForeign(['state_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
