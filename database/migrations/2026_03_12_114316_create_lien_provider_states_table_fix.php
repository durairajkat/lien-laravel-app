<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLienProviderStatesTableFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('lien_provider_states')) {
            Schema::create('lien_provider_states', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lien_id');
                $table->unsignedBigInteger('state_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('lien_provider_states')) {
            Schema::dropIfExists('lien_provider_states');
        }
    }
}
