<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('job_info_id')->unsigned()->index();
            $table->bigInteger('industry_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('job_info_id')
                ->references('id')->on('job_infos')
                ->onDelete('cascade');
            $table->foreign('industry_id')
                ->references('id')->on('contacts')
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
        Schema::dropIfExists('job_contracts');
    }
}
