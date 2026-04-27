<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_portfolios_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('slug', 100)->unique();
            $table->string('display_name', 150);
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();      // chemin photo
            $table->string('cv_file')->nullable();            // chemin CV
            $table->string('vcard_email')->nullable();
            $table->string('vcard_phone', 50)->nullable();
            $table->text('vcard_address')->nullable();
            $table->string('portfolio_external_url', 500)->nullable(); // lien externe
            $table->string('theme', 30)->default('light');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index pour accélérer les requêtes
            $table->index('slug');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolios');
    }
}