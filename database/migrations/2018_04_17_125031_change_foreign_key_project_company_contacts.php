<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeyProjectCompanyContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_industry_contact_maps', function (Blueprint $table) {
            $table->dropForeign(['contactId']);
            $table->foreign('contactId')
                ->references('id')->on('map_company_contacts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_industry_contact_maps', function (Blueprint $table) {
            //
        });
    }
}
