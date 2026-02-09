<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimFromsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_froms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index()->unsigned();
            $table->bigInteger('project_id')->index()->unsigned()->nullable();
            $table->enum('status', [0, 1])->default(0)->nullable()->comment('1 => not saved, 2 => saved');
            $table->string('company_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->longText('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('other_claim')->nullable();
            //$table->date('contract_date')->nullable();
            $table->string('contract_date')->nullable();
            $table->string('extra_amount')->nullable();
            $table->string('contact_total')->nullable();
            $table->string('payment')->nullable();
            $table->string('claim_amount')->nullable();
            $table->string('project_state')->nullable();
            $table->string('date_of_completion')->nullable();
            $table->string('project_name')->nullable();
            $table->string('project_address')->nullable();
            $table->string('county_of_property')->nullable();
            $table->string('project_owner')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('contact_city')->nullable();
            $table->string('contact_state')->nullable();
            $table->string('contact_zip')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('original_contractor')->nullable();
            $table->string('oc_address')->nullable();
            $table->string('oc_city')->nullable();
            $table->string('oc_state')->nullable();
            $table->string('oc_zip')->nullable();
            $table->string('oc_phone')->nullable();
            $table->string('sc_name')->nullable();
            $table->string('sc_address')->nullable();
            $table->string('sc_city')->nullable();
            $table->string('sc_state')->nullable();
            $table->string('sc_zip')->nullable();
            $table->string('sc_phone')->nullable();
            $table->string('contract')->nullable();
            $table->string('project')->nullable();
            $table->string('payment_bond')->nullable();
            $table->string('pb_company')->nullable();
            $table->string('pb_name')->nullable();
            $table->string('pb_address')->nullable();
            $table->string('pb_city')->nullable();
            $table->string('pb_state')->nullable();
            $table->string('pb_zip')->nullable();
            $table->string('pb_phone')->nullable();
            $table->string('mpi_name')->nullable();
            $table->string('mpi_relationship')->nullable();
            $table->string('business_name')->nullable();
            $table->string('mpi_phone')->nullable();
            $table->string('mpi_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_state')->nullable();
            $table->string('customer_zip')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_no')->nullable();
            $table->string('customer_account')->nullable();
            $table->string('value_of_order')->nullable();
            $table->string('job_no')->nullable();
            $table->string('po_no')->nullable();
            $table->string('contract_no')->nullable();
            $table->string('date_product_needed')->nullable();
            $table->string('start_work_date')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->string('project_status_other')->nullable();
            $table->string('check_list_other')->nullable();
            $table->string('miscellaneous')->nullable();
            $table->string('web_search_other')->nullable();
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
        Schema::dropIfExists('claim_froms');
    }
}
