<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLienBoundSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lien_bound_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('state_id')->index()->unsigned();
            $table->bigInteger('project_type_id')->index()->unsigned();
            $table->longText('rights_available');
            $table->longText('claimant');
            $table->longText('prelim_notice');
            $table->longText('other_notice');
            $table->longText('lien');
            $table->longText('suit');
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lien_bound_summaries');
    }
}
