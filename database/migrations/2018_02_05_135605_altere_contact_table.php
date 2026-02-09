<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltereContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('contacts', function (Blueprint $table) {
        //    //$table->engine = 'InnoDB';

        //     $table->string('company')->nullable()->change(); 
        //     $table->string('last_name')->nullable(false)->change();
        //     $table->text('address')->nullable()->change();
        //     $table->string('city')->nullable()->change();
        //     $table->integer('zip')->nullable()->change();
        //     $table->bigInteger('phone')->nullable()->change();
        //     $table->string('email')->nullable()->change();
        //     $table->timestamps();

           

        // });
       DB::statement('ALTER TABLE  `contacts` CHANGE  `zip`  `zip` INT( 11 ) NULL 
');

  DB::statement('ALTER TABLE  `contacts` CHANGE  `city`  `city` VARCHAR(200) NULL 
');
   DB::statement('ALTER TABLE  `contacts` CHANGE  `phone`  `phone` BIGINT( 255 ) NULL 
');
    DB::statement('ALTER TABLE  `contacts` CHANGE  `email`  `email` VARCHAR(200) NULL 
');
    DB::statement('ALTER TABLE  `contacts` CHANGE  `company`  `company` VARCHAR(200) NULL 
');

DB::statement('ALTER TABLE  `contacts` CHANGE  `address`  `address` VARCHAR(200) NULL 
');

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
