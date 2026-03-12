<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleNameToLienProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('lien_providers', 'role_name')) {
            Schema::table('lien_providers', function (Blueprint $table) {
                $table->string('role_name')->nullable();
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
         if (Schema::hasColumn('lien_providers', 'role_name')) {
            Schema::table('lien_providers', function (Blueprint $table) {
                $table->dropColumn('role_name');
            });
        }
    }
}
