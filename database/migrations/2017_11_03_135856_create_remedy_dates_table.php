<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemedyDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remedy_dates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('remedy_id')->index()->unsigned();
            $table->text('date_name')->nullable();
            $table->integer('date_order')->nullable();
            $table->string('date_number')->nullable();
            $table->integer('recurring')->default(0)->nullable();
            $table->timestamps();

            $table->foreign('remedy_id')
                ->references('id')->on('remedies')
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
        Schema::dropIfExists('remedy_dates');
    }
}
