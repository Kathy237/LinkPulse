<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_projects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('github_url', 500)->nullable();
            $table->string('demo_url', 500)->nullable();
            $table->string('image')->nullable();      // image du projet
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('portfolio_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}