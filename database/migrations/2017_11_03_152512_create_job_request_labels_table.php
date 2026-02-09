<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobRequestLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_request_labels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('combination_id')->index()->unsigned();
            $table->string('label');
            $table->timestamps();

            $table->foreign('combination_id')
                ->references('id')->on('job_request_combinations')
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
        Schema::dropIfExists('job_request_labels');
    }
}
