<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToProjectRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_roles', function (Blueprint $table) {
            $table->text('description')->nullable()->after('project_roles');
            $table->unsignedBigInteger('created_by')->nullable()->after('description');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_roles', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('created_by');
            $table->dropSoftDeletes();
        });
    }
}
