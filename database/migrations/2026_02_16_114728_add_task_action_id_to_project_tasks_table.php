<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskActionIdToProjectTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('task_action_id')->nullable()->after('project_id');
            $table->unsignedBigInteger('assigned_to')->nullable()->after('comment');
            $table->unsignedBigInteger('assigned_by')->nullable()->after('assigned_to');
            $table->timestamp('assigned_at')->nullable('assigned_by');
            $table->enum('status',['in_progress', 'completed', 'on_hold', 'cancelled'])->nullable()->default('in_progress')->after('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_tasks', function (Blueprint $table) {
            $table->dropColumn('task_action_id');
            $table->dropColumn('assigned_to');
            $table->dropColumn('assigned_by');
            $table->dropColumn('assigned_at');
            $table->dropColumn('status');
        });
    }
}
