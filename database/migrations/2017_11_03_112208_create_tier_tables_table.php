<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTierTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tier_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('role_id')->index()->unsigned();
            $table->string('tier_limit');
            $table->bigInteger('customer_id')->index()->unsigned();
            $table->string('tier_code')->unique();
            $table->string('tier_coverage_id');
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')->on('project_roles')
                ->onDelete('cascade');
            $table->foreign('customer_id')
                ->references('id')->on('customer_codes')
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
        Schema::dropIfExists('tier_tables');
    }
}
