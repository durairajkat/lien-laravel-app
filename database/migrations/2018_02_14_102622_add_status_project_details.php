<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusProjectDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('project_details', function (Blueprint $table) {
            $table->enum('status',['0','1'])->nullable()->comment('0 => InActive, 1 => Active')->default('1')->after('project_name');
            //$table->integer('status')->default(1)->after('preliminary_deadline');
            
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
