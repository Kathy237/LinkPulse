<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_visits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->enum('source', ['direct', 'nfc', 'qr'])->default('direct');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('visited_at')->useCurrent();

            $table->index('portfolio_id');
            $table->index('source');
            $table->index('visited_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits');
    }
}