<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeletesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tables', function (Blueprint $table) {
            $tables = [
                'users',
                'project_details',
                'project_tasks',
                'roles'
            ];
            foreach ($tables as $table) {
                if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                    Schema::table($table, function (Blueprint $tableBlueprint) {
                        $tableBlueprint->softDeletes();
                    });
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            $tables = [
                'users',
                'project_details',
                'project_tasks',
                'roles'
            ];

            foreach ($tables as $table) {
                if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                    Schema::table($table, function (Blueprint $tableBlueprint) {
                        $tableBlueprint->dropSoftDeletes();
                    });
                }
            }
        });
    }
}
