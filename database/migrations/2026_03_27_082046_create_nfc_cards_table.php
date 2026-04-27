<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_nfc_cards_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNfcCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nfc_cards', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 100 )->unique();   // identifiant unique de la carte physique
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // l'utilisateur qui a associé la carte
            $table->timestamps();

            $table->index('uid');
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
        Schema::dropIfExists('nfc_cards');
    }
}