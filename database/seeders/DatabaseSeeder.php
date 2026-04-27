<?php

namespace Database\Seeders;

use App\Models\CardTemplate;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin par défaut
        User::updateOrCreate(
            ['email' => 'kathywassu@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('MonMotPasse237@'),
                'role' => 'admin',
                'status' => 'active',
                'locale' => 'fr',
            ]
        );

        // Templates de cartes
        CardTemplate::updateOrCreate(
            ['name' => 'Modèle Minimaliste'],
            ['template_data' => json_encode(['style'=>'minimal', 'colors'=>['primary'=>'#fff','secondary'=>'#000']]), 'is_active' => true, 'sort_order' => 1]
        );
        CardTemplate::updateOrCreate(
            ['name' => 'Modèle Moderne'],
            ['template_data' => json_encode(['style'=>'modern', 'colors'=>['primary'=>'#1E3A8A','secondary'=>'#F59E0B']]), 'is_active' => true, 'sort_order' => 2]
        );
        CardTemplate::updateOrCreate(
            ['name' => 'Modèle Classique'],
            ['template_data' => json_encode(['style'=>'classic', 'colors'=>['primary'=>'#F3F4F6','secondary'=>'#374151']]), 'is_active' => true, 'sort_order' => 3]
        );

        // Configuration globale
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => 'LinkPulse']);
        Setting::updateOrCreate(['key' => 'default_language'], ['value' => 'fr']);
        Setting::updateOrCreate(['key' => 'contact_email'], ['value' => 'admin@example.com']);
    }
}