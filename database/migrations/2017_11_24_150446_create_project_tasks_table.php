<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->index()->unsigned();
            $table->string('task_name');
            $table->date('due_date');
            $table->date('complete_date')->nullable();
            $table->enum('email_alert',['0','1'])->comments('0 => Off, 1 => On');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')->on('project_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_tasks');
    }
}
