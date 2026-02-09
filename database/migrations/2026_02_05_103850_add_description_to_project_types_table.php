<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToProjectTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_types', function (Blueprint $table) {
            $table->text('description')->nullable()->after('project_type');
            $table->unsignedBigInteger('created_by')->nullable()->after('description');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_types', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('created_by');
            $table->dropSoftDeletes();
        });
    }
}
