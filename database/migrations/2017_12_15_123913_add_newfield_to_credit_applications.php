<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewfieldToCreditApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_applications', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('home')->nullable();
            $table->string('phone2')->nullable();
            $table->string('social')->nullable();
            $table->string('position')->nullable();
            $table->string('name1')->nullable();
            $table->string('home1')->nullable();
            $table->string('phone1')->nullable();
            $table->string('social1')->nullable();
            $table->string('position1')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
