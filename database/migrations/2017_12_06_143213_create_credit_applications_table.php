<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned()->index()->unique();
            $table->string('Company')->nullable();
            $table->string('Contact')->nullable();
            $table->text('Address')->nullable();
            $table->string('City')->nullable();
            $table->string('State')->nullable();
            $table->integer('Zip')->nullable();
            $table->bigInteger('Phone')->nullable();
            $table->bigInteger('Fax')->nullable();
            $table->string('Email')->nullable();
            $table->string('CustomerCompany')->nullable();
            $table->string('CustomerContact')->nullable();
            $table->text('CustomerAddress')->nullable();
            $table->string('CustomerCity')->nullable();
            $table->string('CustomerState')->nullable();
            $table->integer('CustomerZip')->nullable();
            $table->bigInteger('CustomerPhone')->nullable();
            $table->bigInteger('CustomerFax')->nullable();
            $table->string('CustomerEmail')->nullable();
            $table->string('StateOfOrigin')->nullable();
            $table->string('FederalID')->nullable();
            $table->string('SalesTaxID')->nullable();
            $table->string('PaymentContact')->nullable();
            $table->string('PaymentPhone')->nullable();
            $table->string('PaymentEmail')->nullable();
            $table->string('PaymentAddress')->nullable();
            $table->string('PurchaseManager')->nullable();
            $table->bigInteger('PurchaseManagerPhone')->nullable();
            $table->string('ParentCompany')->nullable();
            $table->text('ParentCompanyAddress')->nullable();
            $table->string('ParentCompanyPO')->nullable();
            $table->string('ParentCompanyCity')->nullable();
            $table->string('ParentCompanyState')->nullable();
            $table->integer('ParentCompanyZip')->nullable();
            $table->bigInteger('ParentCompanyPhone')->nullable();
            $table->bigInteger('ParentCompanyFax')->nullable();
            $table->text('OwnersLine1')->nullable();
            $table->text('OwnersLine2')->nullable();
            $table->string('Bankruptcy')->nullable();
            $table->string('BankruptcyYearState')->nullable();
            $table->string('Judgement')->nullable();
            $table->string('PendingLegal')->nullable();
            $table->string('BankName')->nullable();
            $table->string('BankAccount')->nullable();
            $table->bigInteger('BankPhone')->nullable();
            $table->bigInteger('BankFax')->nullable();
            $table->string('BankContact')->nullable();
            $table->string('Reference1Name')->nullable();
            $table->string('Reference1Account')->nullable();
            $table->bigInteger('Reference1Phone')->nullable();
            $table->bigInteger('Reference1Fax')->nullable();
            $table->string('Reference1Contact')->nullable();
            $table->string('Reference2Name')->nullable();
            $table->string('Reference2Account')->nullable();
            $table->bigInteger('Reference2Phone')->nullable();
            $table->bigInteger('Reference2Fax')->nullable();
            $table->string('Reference2Contact')->nullable();
            $table->string('Reference3Name')->nullable();
            $table->string('Reference3Account')->nullable();
            $table->bigInteger('Reference3Phone')->nullable();
            $table->bigInteger('Reference3Fax')->nullable();
            $table->string('Reference3Contact')->nullable();
            $table->string('Customer')->nullable();
            $table->string('PreparedBy')->nullable();
            $table->string('Dated')->nullable();
            $table->string('Title')->nullable();
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
        Schema::dropIfExists('credit_applications');
    }
}
