<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToProjectIndustryContactMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_industry_contact_maps', function (Blueprint $table) {
            $table->unsignedBigInteger('company_contact_id')->nullable();
            $table->unsignedBigInteger('contactId')->nullable()->change();
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
            $table->dropColumn('company_contact_id');
            $table->unsignedBigInteger('contactId')->nullable(false)->change();
        });
    }
}
