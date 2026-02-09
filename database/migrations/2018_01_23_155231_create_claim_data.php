<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index()->unsigned();
            $table->string('project_name')->nullable();
           // $table->enum('status',[0,1])->default(0)->nullable()->comment('1 => not saved, 2 => saved');
            $table->string('filling_type')->nullable();
            $table->string('other_data')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('original')->nullable();
            $table->string('base_amount')->nullable();
            $table->string('extra_amount')->nullable();
            $table->string('payment')->nullable();
            $table->string('notice_step1')->nullable();
            $table->string('status')->nullable();
            $table->string('custom')->nullable();
            $table->string('myfile_date')->nullable();
            $table->string('preliminary')->nullable();
            $table->string('myfile_preliminary')->nullable();
            $table->string('lien')->nullable();
            $table->string('myfile_lien')->nullable();
            $table->string('myfile')->nullable();
            $table->string('construction')->nullable();
            $table->string('first_date')->nullable();
            $table->string('last_date')->nullable();
            $table->string('shipping')->nullable();
            $table->string('whole')->nullable();
            $table->string('myfile_next')->nullable();
            
            $table->string('project_state')->nullable();
            //$table->string('myfile')->nullable();


            
            //$table->date('contract_date')->nullable();
          
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
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
        //
    }
}
