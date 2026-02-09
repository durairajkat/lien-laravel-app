<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_name');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('state_id')->unsigned()->index();
            $table->bigInteger('project_type_id')->unsigned()->index();
            $table->bigInteger('role_id')->unsigned()->index();
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->bigInteger('customer_contract_id')->nullable()->unsigned()->index();
            $table->bigInteger('industry_contract_id')->nullable()->unsigned()->index();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('company_work')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('state_id')
                ->references('id')->on('states')
                ->onDelete('cascade');
            $table->foreign('project_type_id')
                ->references('id')->on('project_types')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')->on('project_roles')
                ->onDelete('cascade');
            $table->foreign('customer_id')
                ->references('id')->on('tier_tables')
                ->onDelete('cascade');
            $table->foreign('customer_contract_id')
                ->references('id')->on('contacts')
                ->onDelete('cascade');
            $table->foreign('industry_contract_id')
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
        Schema::dropIfExists('project_details');
    }
}
