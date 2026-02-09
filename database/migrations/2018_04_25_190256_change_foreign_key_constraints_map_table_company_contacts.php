<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeyConstraintsMapTableCompanyContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('project_details', function (Blueprint $table) {
            // $table->dropForeign(['customer_contract_id']);

            /*$table->foreign('customer_contract_id')
                ->references('id')->on('map_company_contacts')
                ->onDelete('restrict');*/
        });

        /*Schema::table('map_company_contacts', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['company_contact_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['state_id']);
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');

            $table->foreign('company_contact_id')
                ->references('id')->on('company_contacts')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('state_id')
                ->references('id')->on('states')
                ->onDelete('cascade');
        });*/


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
