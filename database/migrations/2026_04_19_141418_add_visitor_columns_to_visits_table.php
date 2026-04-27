<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->string('visitor_name', 191)->nullable()->after('user_agent');
            $table->string('visitor_email', 191)->nullable()->after('visitor_name');
            $table->string('visitor_phone', 50)->nullable()->after('visitor_email');
            $table->text('visitor_address')->nullable()->after('visitor_phone');
            $table->jsonb('visitor_social_links')->nullable()->after('visitor_address');
        });
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn([
                'visitor_name',
                'visitor_email',
                'visitor_phone',
                'visitor_address',
                'visitor_social_links'
            ]);
        });
    }
};