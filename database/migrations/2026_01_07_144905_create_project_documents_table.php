<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_preferences')) {
            Schema::create('project_documents', function (Blueprint $table) {
                $table->id();

                $table->unsignedInteger('project_id');
                $table->string('title');
                $table->text('notes');
                $table->date('date');
                $table->string('filename');
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
        Schema::dropIfExists('project_documents');
    }
}
