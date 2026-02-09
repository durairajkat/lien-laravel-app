<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobRequestDeadlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_request_deadlines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('combination_id')->index()->unsigned();
            $table->bigInteger('days')->default(0);
            $table->bigInteger('months')->default(0);
            $table->bigInteger('years')->default(0);
            $table->bigInteger('label_id')->index()->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->foreign('combination_id')
                ->references('id')->on('job_request_combinations')
                ->onDelete('cascade');
            $table->foreign('label_id')
                ->references('id')->on('job_request_labels')
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
        Schema::dropIfExists('job_request_deadlines');
    }
}
