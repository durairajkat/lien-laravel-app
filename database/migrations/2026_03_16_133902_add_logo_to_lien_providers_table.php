<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoToLienProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('lien_providers', 'logo')) {
            Schema::table('lien_providers', function (Blueprint $table) {
                $table->string('logo')->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('lien_providers', 'logo')) {
            Schema::table('lien_providers', function (Blueprint $table) {
                $table->dropColumn('logo');
            });
        }
    }
}
