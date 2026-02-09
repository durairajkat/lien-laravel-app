<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAndCompanyToLienProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lien_providers', function (Blueprint $table) {
            $table->bigInteger('company_id')->nullable()->unsigned()->after('id');
            $table->bigInteger('user_id')->nullable()->unsigned()->after('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lien_providers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('user_id');
        });
    }
}
