<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CampaignUserSeeder extends Seeder
{
    public function run(): void
    {
        \DB::table('campaign_user')->insert([

            // Operace Nedůvěra – Martin (admin)
            ['campaign_id' => 1, 'user_id' => 1], // Martin - admin
            ['campaign_id' => 1, 'user_id' => 3], // Jana - coordinator
            ['campaign_id' => 1, 'user_id' => 5], // Tomáš - guest

            // Projekt Rozdělení – Alex (campaign_manager)
            ['campaign_id' => 2, 'user_id' => 1], // Martin - admin
            ['campaign_id' => 2, 'user_id' => 2], // Alex - campaign_manager
            ['campaign_id' => 2, 'user_id' => 4], // Petr - worker

            // Simulace Panika – Martin (admin)
            ['campaign_id' => 3, 'user_id' => 1], // Martin - admin
            ['campaign_id' => 3, 'user_id' => 3], // Jana - coordinator

            // Neexistující Horizont 2025 – Alex (campaign_manager)
            ['campaign_id' => 4, 'user_id' => 1], // Martin - admin
            ['campaign_id' => 4, 'user_id' => 2], // Alex - campaign_manager
            ['campaign_id' => 4, 'user_id' => 4], // Petr - worker

            // Protokol Myšlenka – Martin (admin)
            ['campaign_id' => 5, 'user_id' => 1], // Martin - admin
            ['campaign_id' => 5, 'user_id' => 3], // Jana - coordinator
            ['campaign_id' => 5, 'user_id' => 4], // Petr - worker

        ]);
    }
}
