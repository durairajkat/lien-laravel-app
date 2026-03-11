<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToLienProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lien_providers', function (Blueprint $table) {
            $table->softDeletes();
            $table->string('phone')->nullable()->change();
            $table->string('zip')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('fax')->nullable()->change();
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
            $table->dropSoftDeletes();
            $table->string('phone')->nullable(false)->change();
            $table->string('zip')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('fax')->nullable(false)->change();
        });
    }
}
