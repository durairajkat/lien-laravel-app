<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyDetailsToMapContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('map_company_contacts', function (Blueprint $table) {
            $table->bigInteger('company_id')->nullable()->unsigned()->change();
            $table->bigInteger('company_contact_id')->nullable()->unsigned()->change();
            $table->boolean('is_user')->default(0)->comment('0 => Inactive, 1 => Active')->after('user_id');
            $table->text('address')->nullable()->after('is_user');
            $table->string('city')->nullable()->after('address');
            $table->bigInteger('state_id')->nullable()->index()->unsigned()->after('city');
            $table->integer('zip')->nullable()->after('state_id');
            $table->bigInteger('phone')->nullable()->after('zip');
            $table->bigInteger('fax')->nullable()->after('phone');

            $table->foreign('state_id')
                ->references('id')->on('states')
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
        Schema::table('map_company_contacts', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropColumn('is_user');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('state_id');
            $table->dropColumn('zip');
            $table->dropColumn('phone');
            $table->dropColumn('fax');

        });
    }
}
