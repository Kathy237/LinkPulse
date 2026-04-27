<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_social_links_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->string('platform', 50);
            $table->string('url', 500);
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
        Schema::dropIfExists('social_links');
    }
}