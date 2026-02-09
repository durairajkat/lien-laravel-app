<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned()->index()->unique();
            $table->decimal('base_amount',8,2)->default(0)->nullable();
            $table->decimal('extra_amount',8,2)->default(0)->nullable();
            $table->decimal('credits',8,2)->default(0)->nullable();
            $table->decimal('waiver',8,2)->default(0)->nullable();
            $table->string('receivable_status')->nullable();
            $table->enum('calculation_status',['0','1'])->nullable()->comment('0 => In progress, 1 => Complete');
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
        Schema::dropIfExists('project_contracts');
    }
}
