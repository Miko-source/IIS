<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        \DB::table('activities')->insert([

            // STEP 1 – 1 aktivita
            [
                'name' => 'Zpracování úvodní analytické zprávy',
                'step_id' => 1,
                'type_id' => 1,
                'cost' => 1200,
                'description' => 'Analytický text shrnující důvěru cílové skupiny ve státní instituce.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // STEP 2 – 2 aktivity
            [
                'name' => 'Příprava struktury narativu',
                'step_id' => 2,
                'type_id' => 1,
                'cost' => 800,
                'description' => 'Vytvoření dokumentu definujícího klíčové body sdělení.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grafická tvorba doprovodných vizuálů',
                'step_id' => 2,
                'type_id' => 3,
                'cost' => 1500,
                'description' => 'Příprava vizuálů podporujících hlavní narativ.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // STEP 3 – 3 aktivity
            [
                'name' => 'Příprava testovacího příspěvku',
                'step_id' => 3,
                'type_id' => 2,
                'cost' => 300,
                'description' => 'Krátký příspěvek použitý v pilotní fázi šíření.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vytvoření grafiky pro testování',
                'step_id' => 3,
                'type_id' => 3,
                'cost' => 900,
                'description' => 'Jednoduchý vizuál ověřující reakci na obrazový obsah.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Publikace pilotního obsahu',
                'step_id' => 3,
                'type_id' => 2,
                'cost' => 200,
                'description' => 'Pilotní zveřejnění testovacího obsahu na sociální síť.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // STEP 5 – 4 aktivity
            [
                'name' => 'Shromáždění konfliktních příkladů',
                'step_id' => 5,
                'type_id' => 1,
                'cost' => 700,
                'description' => 'Textová příprava konfliktů mezi vybranými skupinami.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Generování vizuálů vyvolávajících spor',
                'step_id' => 5,
                'type_id' => 3,
                'cost' => 1600,
                'description' => 'Vytvoření obrazových materiálů zvyšujících polarizaci.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vytvoření sady protichůdných tvrzení',
                'step_id' => 5,
                'type_id' => 1,
                'cost' => 900,
                'description' => 'Sestavení série narativů podporujících protistrany.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Publikace rozdělujícího obsahu',
                'step_id' => 5,
                'type_id' => 2,
                'cost' => 350,
                'description' => 'Zveřejnění polarizačního obsahu do cílových komunit.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}