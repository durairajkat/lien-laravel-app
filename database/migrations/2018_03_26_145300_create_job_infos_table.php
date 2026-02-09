<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned()->index();
            $table->string('company_name')->nullable();
            $table->string('company_fname')->nullable();
            $table->string('company_lname')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_city')->nullable();
            $table->bigInteger('company_state')->unsigned()->index()->nullable();
            $table->bigInteger('company_zip')->nullable();
            $table->string('company_email')->nullable();
            $table->text('company_phone')->nullable();
            $table->string('company_office_phone')->nullable();
            $table->string('job_name')->nullable();
            $table->string('job_address')->nullable();
            $table->string('job_city')->nullable();
            $table->bigInteger('job_state')->nullable()->unsigned()->index();
            $table->string('job_zip')->nullable();
            $table->bigInteger('customer_id')->nullable()->unsigned()->index();
            $table->bigInteger('contract_amount')->nullable();
            $table->string('first_day_of_work')->nullable();
            $table->enum('is_subcontractor',['0','1'])->comment('0 => yes, 1 => no')->default(1)->nullable();
            $table->enum('is_gc',['0','1'])->default(0)->comment('0 => yes, 1 => no')->nullable();
            $table->string('gc_company')->nullable();
            $table->text('gc_address')->nullable();
            $table->string('gc_city')->nullable();
            $table->bigInteger('gc_state')->unsigned()->index()->nullable();
            $table->bigInteger('gc_zip')->nullable();
            $table->string('gc_phone')->nullable();
            $table->string('gc_fax')->nullable();
            $table->string('gc_web')->nullable();
            $table->string('gc_first_name')->nullable();
            $table->string('gc_last_name')->nullable();
            $table->string('gc_title')->nullable();
            $table->string('gc_direct_phone')->nullable();
            $table->string('gc_cell')->nullable();
            $table->string('gc_email')->nullable();
            $table->timestamps();

            $table->foreign('company_state')
                ->references('id')->on('states')
                ->onDelete('cascade');
            $table->foreign('job_state')
                ->references('id')->on('states')
                ->onDelete('cascade');
            $table->foreign('gc_state')
                ->references('id')->on('states')
                ->onDelete('cascade');
            $table->foreign('customer_id')
                ->references('id')->on('contacts')
                ->onDelete('cascade');
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
        Schema::dropIfExists('job_infos');
    }
}
