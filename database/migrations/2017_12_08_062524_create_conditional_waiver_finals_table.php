<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionalWaiverFinalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditional_waiver_finals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->index()->unsigned()->unique();
            $table->string('Payer')->nullable();
            $table->decimal('CheckAmount',8,2)->nullable();
            $table->string('Payee')->nullable();
            $table->string('Owner')->nullable();
            $table->string('ProjectName')->nullable();
            $table->text('ProjectAddress')->nullable();
            $table->decimal('DisputeAmount',8,2)->nullable();
            $table->date('SignedDate')->nullable();
            $table->string('SignedCompany')->nullable();
            $table->string('SignedAddress')->nullable();
            $table->string('ContractorState')->nullable();
            $table->string('ContractorCounty')->nullable();
            $table->string('Undersigned')->nullable();
            $table->string('ContractorTitle')->nullable();
            $table->string('ContractorCompanyName')->nullable();
            $table->string('ContractorWorkType')->nullable();
            $table->string('ContractorProjectAddress')->nullable();
            $table->decimal('ContractorAmount',8,2)->nullable();
            $table->decimal('ContractorPaid',8,2)->nullable();
            $table->string('ItemName1')->nullable();
            $table->string('WhatFor1')->nullable();
            $table->decimal('ContractPrice1',8,2)->nullable();
            $table->decimal('AmountPaid1',8,2)->nullable();
            $table->decimal('ThisPayment1',8,2)->nullable();
            $table->decimal('BalDue1',8,2)->nullable();
            $table->string('ItemName2')->nullable();
            $table->string('WhatFor2')->nullable();
            $table->decimal('ContractPrice2',8,2)->nullable();
            $table->decimal('AmountPaid2',8,2)->nullable();
            $table->decimal('ThisPayment2',8,2)->nullable();
            $table->decimal('BalDue2',8,2)->nullable();
            $table->string('ItemName3')->nullable();
            $table->string('WhatFor3')->nullable();
            $table->decimal('ContractPrice3',8,2)->nullable();
            $table->decimal('AmountPaid3',8,2)->nullable();
            $table->decimal('ThisPayment3',8,2)->nullable();
            $table->decimal('BalDue3',8,2)->nullable();
            $table->string('ItemName4')->nullable();
            $table->string('WhatFor4')->nullable();
            $table->decimal('ContractPrice4',8,2)->nullable();
            $table->decimal('AmountPaid4',8,2)->nullable();
            $table->decimal('ThisPayment4',8,2)->nullable();
            $table->decimal('BalDue4',8,2)->nullable();
            $table->string('ItemName5')->nullable();
            $table->string('WhatFor5')->nullable();
            $table->decimal('ContractPrice5',8,2)->nullable();
            $table->decimal('AmountPaid5',8,2)->nullable();
            $table->decimal('ThisPayment5',8,2)->nullable();
            $table->decimal('BalDue5',8,2)->nullable();
            $table->string('ContractorDate')->nullable();
            $table->string('NotaryDay')->nullable();
            $table->string('NotaryMonth')->nullable();
            $table->string('NotaryYear')->nullable();
            $table->string('NotarySigned')->nullable();
            $table->timestamps();

            //Foreign Keys
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
        Schema::dropIfExists('conditional_waiver_finals');
    }
}
