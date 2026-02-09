<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimDataFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_data_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned()->index()->unique();
            $table->string('p_name')->nullable();
            $table->text('p_address')->nullable();
            $table->string('p_city')->nullable();
            $table->text('p_state')->nullable();
            $table->string('p_zip')->nullable();
            $table->string('p_phone')->nullable();
            $table->string('p_country_of_property')->nullable();
            $table->string('c_name')->nullable();
            $table->string('c_contact_name')->nullable();
            $table->text('c_address')->nullable();
            $table->string('c_city')->nullable();
            $table->string('c_state')->nullable();
            $table->string('c_zip')->nullable();
            $table->string('c_phone')->nullable();
            $table->string('c_no')->nullable();
            $table->string('c_account')->nullable();
            $table->string('p_owner_company')->nullable();
            $table->string('p_owner_contact')->nullable();
            $table->text('p_owner_address')->nullable();
            $table->string('p_owner_city')->nullable();
            $table->string('p_owner_state')->nullable();
            $table->string('p_owner_zip')->nullable();
            $table->string('p_owner_phone')->nullable();
            $table->string('o_contractor_company')->nullable();
            $table->string('o_contractor_contact')->nullable();
            $table->text('o_contractor_address')->nullable();
            $table->string('o_contractor_city')->nullable();
            $table->string('o_contractor_state')->nullable();
            $table->string('o_contractor_zip')->nullable();
            $table->string('o_contractor_phone')->nullable();
            $table->string('s_contractor_company')->nullable();
            $table->string('s_contractor_contact')->nullable();
            $table->text('s_contractor_address')->nullable();
            $table->string('s_contractor_city')->nullable();
            $table->string('s_contractor_state')->nullable();
            $table->string('s_contractor_zip')->nullable();
            $table->string('s_contractor_phone')->nullable();
            $table->string('project_type')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('project_number')->nullable();
            $table->string('project_notice')->nullable();
            $table->string('payment_bond')->nullable();
            $table->string('payment_bond_number')->nullable();
            $table->string('bond_company')->nullable();
            $table->string('bond_contract')->nullable();
            $table->text('bond_address')->nullable();
            $table->string('bond_city')->nullable();
            $table->string('bond_state')->nullable();
            $table->string('bond_zip')->nullable();
            $table->string('bond_phone')->nullable();
            $table->string('order_value')->nullable();
            $table->string('job_number')->nullable();
            $table->string('pon_number')->nullable();
            $table->string('order_contract_number')->nullable();
            $table->string('date_product_needed')->nullable();
            $table->string('start_date')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('status')->nullable();
            $table->string('status_other')->nullable();
            $table->string('documents_other_document')->nullable();
            $table->enum('documents_purchase_order',[0,1])->nullable();
            $table->enum('documents_delivery_tickets',[0,1])->nullable();
            $table->enum('documents_waiver',[0,1])->nullable();
            $table->enum('documents_legal_description',[0,1])->nullable();
            $table->enum('documents_payment_bond',[0,1])->nullable();
            $table->enum('documents_joint_check_agreement',[0,1])->nullable();
            $table->enum('documents_preliminary_notice',[0,1])->nullable();
            $table->enum('documents_other',[0,1])->nullable();
            $table->string('miscellaneous')->nullable();
            $table->string('add_contact_first_name')->nullable();
            $table->string('add_contact_last_name')->nullable();
            $table->string('add_contact_type')->nullable();
            $table->string('add_contact_company')->nullable();
            $table->string('add_contact_phone')->nullable();
            $table->string('add_contact_email')->nullable();
            $table->string('heard_by_other')->nullable();
            $table->enum('heard_by_web',[0,1])->nullable();
            $table->enum('heard_by_google',[0,1])->nullable();
            $table->enum('heard_by_aol',[0,1])->nullable();
            $table->enum('heard_by_referral',[0,1])->nullable();
            $table->enum('heard_by_msn',[0,1])->nullable();
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
        Schema::dropIfExists('claim_data_forms');
    }
}
