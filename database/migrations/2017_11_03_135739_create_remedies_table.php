<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemediesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remedies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('state_id')->index()->unsigned();
            $table->bigInteger('project_type_id')->index()->unsigned();
            $table->string('remedy')->nullable();
            $table->timestamps();

            $table->foreign('state_id')
                ->references('id')->on('states')
                ->onDelete('cascade');
            $table->foreign('project_type_id')
                ->references('id')->on('project_types')
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
        Schema::dropIfExists('remedies');
    }
}
