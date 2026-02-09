<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLienProviderStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_preferences')) {
            Schema::create('lien_provider_states', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('lien_id');
                $table->unsignedBigInteger('state_id');

                $table->timestamps();
                $table->softDeletes();

                $table->index('lien_id', 'lien_provider_states_lien_id_index');
                $table->index('state_id', 'lien_provider_states_state_id_index');
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
        Schema::dropIfExists('lien_provider_states');
    }
}
