<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_nfc_reads_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNfcReadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nfc_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('nfc_cards')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('read_at')->useCurrent();

            $table->index('user_id');
            $table->index('card_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nfc_reads');
    }
}