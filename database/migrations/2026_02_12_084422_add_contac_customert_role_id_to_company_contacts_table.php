<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContacCustomertRoleIdToCompanyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_role_id')->nullable()->after('contact_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_contacts', function (Blueprint $table) {
            $table->dropColumn('contact_role_id');
        });
    }
}
