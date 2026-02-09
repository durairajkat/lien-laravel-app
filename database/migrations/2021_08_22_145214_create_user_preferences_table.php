<?php



use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



class CreateUserPreferencesTable extends Migration

{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()

    {
        if (!Schema::hasTable('user_preferences')) {
            Schema::create('user_preferences', function (Blueprint $table) {

                $table->id();

                $table->foreignId('user_id');

                $table->string('project_name')->index();

                $table->foreignId('state_id');

                $table->foreignId('project_type_id');

                $table->foreignId('role_id');

                $table->foreignId('customer_id');

                $table->string('answer1')->nullable();

                $table->string('answer2')->nullable();

                $table->timestamps();
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

        Schema::dropIfExists('user_preferences');
    }
}
