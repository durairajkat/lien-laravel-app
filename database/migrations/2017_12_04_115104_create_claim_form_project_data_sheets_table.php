<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimFormProjectDataSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_form_project_data_sheets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned()->index()->unique();
            $table->string('company')->nullable();
            $table->string('contact')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('purpose')->nullable();
            $table->string('claim_date')->nullable();
            $table->string('claim_type')->nullable();
            $table->string('claim_type_other')->nullable();
            $table->string('contract_date')->nullable();
            $table->string('contract_type')->nullable();
            $table->decimal('base_amount', 8, 2)->nullable();
            $table->decimal('extra_amount', 8, 2)->nullable();
            $table->decimal('subtotal', 8, 2)->nullable();
            $table->decimal('payments', 8, 2)->nullable();
            $table->decimal('total', 8, 2)->nullable();

            $table->string('extra_work_related')->nullable();
            $table->string('provided')->nullable();
            $table->string('custom_manufacture_date')->nullable();
            $table->string('custom_manufacture')->nullable();
            $table->string('preliminary_notice')->nullable();
            $table->string('lienwaiver')->nullable();
            $table->string('construction_age')->nullable();
            $table->string('construction_type')->nullable();
            $table->string('notice_of_completion')->nullable();
            $table->string('project_complete')->nullable();
            $table->string('complete_date')->nullable();

            $table->enum('agree', [0, 1])->comment('0 => Not Agree, 1 => Agree');;
            $table->string('signature')->nullable();
            $table->string('signature_date')->nullable();
            $table->string('project_state')->nullable();
            $table->string('project_state_other')->nullable();
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
        Schema::dropIfExists('claim_form_project_data_sheets');
    }
}
