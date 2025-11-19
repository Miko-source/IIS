<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('campaign_user')->insert([

            // Operace Nedůvěra
            [
                'campaign_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Martin (admin)
            [
                'campaign_id' => 1,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Jana (coordinator)
            [
                'campaign_id' => 1,
                'user_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Petr (worker)

            // Projekt Rozdělení
            [
                'campaign_id' => 2,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Alex (campaign_manager)
            [
                'campaign_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Martin (admin)
            [
                'campaign_id' => 2,
                'user_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Marek (worker)

            // Simulace Panika
            [
                'campaign_id' => 3,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Martin (admin)
            [
                'campaign_id' => 3,
                'user_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Lucie (coordinator)

            // Neexistující Horizont 2025
            [
                'campaign_id' => 4,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Alex (campaign_manager)
            [
                'campaign_id' => 4,
                'user_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Karel (worker)
            [
                'campaign_id' => 4,
                'user_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Lukáš (worker)

            // Protokol Myšlenka 
            [
                'campaign_id' => 5,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Martin (admin)
            [
                'campaign_id' => 5,
                'user_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ], // David Marek (coordinator)
            [
                'campaign_id' => 5,
                'user_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ], // Filip (worker)

            // Akce Paralýza (campaign 6)
            [
                'campaign_id' => 6,
                'user_id' => 1, // Martin (admin) - manager kampaně
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 6,
                'user_id' => 3, // Jana (coordinator) - koordinátor kroků
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 6,
                'user_id' => 4, // Lucie (coordinator)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 6,
                'user_id' => 5, // David (coordinator)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 6,
                'user_id' => 8, // Karel (worker) - realizátor aktivit
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 6,
                'user_id' => 9, // Lukáš (worker) - realizátor aktivit
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Taktika Narušení (campaign 7)
            [
                'campaign_id' => 7,
                'user_id' => 2, // Alex (campaign_manager) - manager kampaně
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 7,
                'user_id' => 3, // Jana (coordinator)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 7,
                'user_id' => 4, // Lucie (coordinator) - koordinátor kroků
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 7,
                'user_id' => 5, // David (coordinator)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 7,
                'user_id' => 10, // Filip (worker) - realizátor aktivit
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 7,
                'user_id' => 11, // David P. (worker) - realizátor aktivit
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Operace Echo (campaign 8)
            [
                'campaign_id' => 8,
                'user_id' => 1, // Martin (admin) - manager kampaně
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 8,
                'user_id' => 3, // Jana (coordinator)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 8,
                'user_id' => 4, // Lucie (coordinator)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 8,
                'user_id' => 5, // David (coordinator) - koordinátor kroků
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 8,
                'user_id' => 8, // Karel (worker) - realizátor aktivit
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 8,
                'user_id' => 9, // Lukáš (worker) - realizátor aktivit
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 8,
                'user_id' => 11, // David P. (worker) - realizátor aktivit
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}