<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSelectedToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('role_id')->nullable()->after('project_id'); // from contact_roles nd role_type = 'project'
            $table->boolean('is_selected')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('project_id');
            $table->dropColumn('is_selected');
            $table->dropColumn('role_id');
        });
    }
}
