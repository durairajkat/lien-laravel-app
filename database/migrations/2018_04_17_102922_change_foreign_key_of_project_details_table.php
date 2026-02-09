<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeyOfProjectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_details', function (Blueprint $table) {
            $table->dropForeign(['customer_contract_id']);
            $table->foreign('customer_contract_id')
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
        Schema::table('project_details', function (Blueprint $table) {
            $table->dropForeign(['customer_contract_id']);
            $table->foreign('customer_contract_id')
                ->references('id')->on('contacts')
                ->onDelete('cascade');
        });
    }
}
