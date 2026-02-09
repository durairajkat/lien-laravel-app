<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDeletableFieldToLienProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lien_providers', function (Blueprint $table) {
            $table->boolean('is_deletable')->default(1)->comment('0 => No, 1 => Yes')->after('email');
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
            $table->dropColumn('is_deletable');
        });
    }
}
