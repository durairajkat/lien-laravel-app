<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReplyStatusInContactUsForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_uses', function (Blueprint $table) {
            $table->enum('status',[0,1])->default(0)->comment('0 => Not Replied, 1 => Replied');
            $table->longText('replyMessage')->nullable();
            $table->text('subject')->nullable();
            $table->string('fileName')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_uses', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('replyMessage');
            $table->dropColumn('subject');
            $table->dropColumn('fileName');
        });
    }
}
