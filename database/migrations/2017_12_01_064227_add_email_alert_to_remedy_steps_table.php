<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailAlertToRemedyStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remedy_steps', function (Blueprint $table) {
            $table->enum('email_alert',['0','1'])->default(1)->comment('0 => Off, 1 => On')->after('notes');
            $table->date('legal_completion_date')->nullable()->after('email_alert');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remedy_steps', function (Blueprint $table) {
            $table->dropColumn('email_alert');
            $table->dropColumn('legal_completion_date');
        });
    }
}
