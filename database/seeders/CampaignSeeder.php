<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('campaigns')->insert([
            [
                'topic_id' => 1,
                'user_id' => 1, // Martin (admin)
                'name' => 'Operace Nedůvěra',
                'start_date' => now()->subMonths(3)->format('Y-m-d'),
                'end_date' => now()->subMonth()->format('Y-m-d'),
                'done' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 2,
                'user_id' => 2, // Alex (campaign_manager)
                'name' => 'Projekt Rozdělení',
                'start_date' => now()->subMonths(3)->format('Y-m-d'),
                'end_date' => now()->subMonth()->format('Y-m-d'),
                'done' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 3,
                'user_id' => 1, // Martin (admin)
                'name' => 'Simulace Panika',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'done' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 4,
                'user_id' => 2, // Alex (campaign_manager)
                'name' => 'Neexistující Horizont 2025',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'done' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 5,
                'user_id' => 1, // Martin (admin)
                'name' => 'Protokol Myšlenka',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'done' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 2,
                'user_id' => 1,
                'name' => 'Akce Paralýza',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'done' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 3,
                'user_id' => 2,
                'name' => 'Taktika Narušení',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'done' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 3,
                'user_id' => 1,
                'name' => 'Operace Echo',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'done' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
