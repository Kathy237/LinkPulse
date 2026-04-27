<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::table('visits', function (Blueprint $table) {
        $table->string('device_id', 255)->nullable()->after('portfolio_id');
     });
    }

    public function down(): void
    {
        Schema::table('nfc_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('portfolio_id')->nullable(false)->change();
        });
    }
};