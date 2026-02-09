<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_contracts', function (Blueprint $table) {
            $table->string('general_description')->nullable()->after('calculation_status');
            $table->biginteger('job_no')->nullable()->after('general_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_contracts', function (Blueprint $table) {
            $table->dropColumn('general_description');
            $table->dropColumn('job_no');
        });
    }
}
