<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToProjectDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // If table does NOT exist → create it
        if (!Schema::hasTable('project_documents')) {

            Schema::create('project_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('project_id');
                $table->string('title')->nullable();
                $table->text('notes')->nullable();
                $table->date('date')->nullable();
                $table->string('filename')->nullable();
                $table->timestamps();

                // Optional: foreign key (only if projects table exists)
                // $table->foreign('project_id')
                //       ->references('id')
                //       ->on('projects')
                //       ->onDelete('cascade');
            });
        } else {

            // Table exists → update safely
            Schema::table('project_documents', function (Blueprint $table) {

                if (!Schema::hasColumn('project_documents', 'id')) {
                    $table->id();
                }

                if (!Schema::hasColumn('project_documents', 'project_id')) {
                    $table->unsignedBigInteger('project_id')->nullable();
                }

                if (!Schema::hasColumn('project_documents', 'title')) {
                    $table->string('title')->nullable();
                }

                if (!Schema::hasColumn('project_documents', 'notes')) {
                    $table->text('notes')->nullable();
                }

                if (!Schema::hasColumn('project_documents', 'date')) {
                    $table->date('date')->nullable();
                }

                if (!Schema::hasColumn('project_documents', 'filename')) {
                    $table->string('filename')->nullable();
                }

                if (!Schema::hasColumn('project_documents', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }

                if (!Schema::hasColumn('project_documents', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
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
