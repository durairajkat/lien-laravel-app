<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('user_name')->unique()->index();
            $table->string('email')->unique();
            $table->string('password');
            $table->bigInteger('role')->unsigned()->index();
            $table->enum('status', ['0', '1'])->default(0)->comment('0 => Active, 1 => Inactive');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role')
                ->references('id')->on('roles')
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
        Schema::dropIfExists('users');
    }
}
