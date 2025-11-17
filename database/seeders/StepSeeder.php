<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StepSeeder extends Seeder
{
    public function run(): void
    {
        \DB::table('steps')->insert([

            // Operace Nedůvěra (campaign_id 1)
            [
                'campaign_id' => 1, 
                'user_id' => 3, 
                'name' => 'Analýza cílové skupiny', 
                'order' => 1, 
                'description' => 'Posouzení míry důvěry ve státní instituce.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 1, 
                'user_id' => 4, 
                'name' => 'Příprava narativu', 
                'order' => 2, 
                'description' => 'Sestavení sdělení podkopávajících důvěru.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 1, 
                'user_id' => 5, 
                'name' => 'Test šíření', 
                'order' => 3, 
                'description' => 'Pilotní vypuštění zpráv.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Projekt Rozdělení (campaign_id 2)
            [
                'campaign_id' => 2, 
                'user_id' => 4, 
                'name' => 'Identifikace konfliktních skupin', 
                'order' => 1, 
                'description' => 'Definování polarizačních bodů.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 2, 
                'user_id' => 5, 
                'name' => 'Vytvoření konfliktu', 
                'order' => 2, 
                'description' => 'Generování protichůdných tvrzení.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 2, 
                'user_id' => 3, 
                'name' => 'Rozšíření do komunit', 
                'order' => 3, 
                'description' => 'Zacílení na komunitní platformy.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Simulace Panika (campaign_id 3)
            [
                'campaign_id' => 3, 
                'user_id' => 5, 
                'name' => 'Sběr neúplných informací', 
                'order' => 1, 
                'description' => 'Úmyslné použití nejasných dat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 3, 
                'user_id' => 3, 
                'name' => 'Tvorba alarmistických zpráv', 
                'order' => 2, 
                'description' => 'Simulace panických sdělení.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 3, 
                'user_id' => 4, 
                'name' => 'Analýza reakce veřejnosti', 
                'order' => 3, 
                'description' => 'Vyhodnocení dopadů paniky.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Neexistující Horizont 2025 (campaign_id 4)
            [
                'campaign_id' => 4, 
                'user_id' => 3, 
                'name' => 'Zpochybnění vědeckého konsenzu', 
                'order' => 1, 
                'description' => 'Sestavení argumentů o ploché Zemi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 4, 
                'user_id' => 4, 
                'name' => 'Vizuální materiály', 
                'order' => 2, 
                'description' => 'Vytvoření falešných vizuálů.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 4, 
                'user_id' => 5, 
                'name' => 'Šíření ve fórech', 
                'order' => 3, 
                'description' => 'Šíření mezi skeptickými skupinami.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Protokol Myšlenka (campaign_id 5)
            [
                'campaign_id' => 5, 
                'user_id' => 4, 
                'name' => 'Modelování hrozby AI', 
                'order' => 1, 
                'description' => 'Sběr konspiračních scénářů.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 5, 
                'user_id' => 5, 
                'name' => 'Tvorba narativu kontroly mysli', 
                'order' => 2, 
                'description' => 'Simulace příběhu o ovládání mysli.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'campaign_id' => 5, 
                'user_id' => 3, 
                'name' => 'Integrace s technologickými prvky', 
                'order' => 3, 
                'description' => 'Spojení s technologickými prvky.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}