<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counties', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('state_id');
            $table->string('name', 150);

            // Official US Census codes
            $table->char('county_fips_code', 5)->nullable();
            $table->char('state_fips_code', 2)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('state_id');

            // Foreign key
            $table->foreign('state_id')
                ->references('id')
                ->on('states')
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
        Schema::dropIfExists('counties');
    }
}
