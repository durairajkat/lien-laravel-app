<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAndIsViewableToJobInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_infos', function (Blueprint $table) {
            $table->boolean('is_viewable')->default(0)->after('gc_email');
            $table->enum('status',[0, 1, 2])->default(0)->after('is_viewable')->comment('0 => Not completed, 1 => Active, 2 => Completed');
            $table->dateTime('date_completed')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lien_providers', function (Blueprint $table) {
            $table->dropColumn('is_viewable');
            $table->dropColumn('status');
            $table->dropColumn('date_completed');
        });
    }
}
