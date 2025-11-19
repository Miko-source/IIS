<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('activities')->insert([

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

            // STEP 16 – Akce Paralýza (kampaně 6)
            [
                'name' => 'Vypracování mapy kritických kanálů',
                'step_id' => 16,
                'type_id' => 1,
                'cost' => 1100,
                'description' => 'Detailní dokument o tocích informací v cílové infrastruktuře.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Technický audit slabin',
                'step_id' => 16,
                'type_id' => 3,
                'cost' => 950,
                'description' => 'Vizuální znázornění uzlů vhodných pro zahlcení.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // STEP 17
            [
                'name' => 'Balíček paralyzujících sdělení',
                'step_id' => 17,
                'type_id' => 1,
                'cost' => 780,
                'description' => 'Série textových variant zdůrazňujících nejistotu rozhodování.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // STEP 18
            [
                'name' => 'Nasazení zátěžového scénáře',
                'step_id' => 18,
                'type_id' => 2,
                'cost' => 520,
                'description' => 'Testovací publikace k ověření schopnosti zahlcení.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // STEP 19 – Taktika Narušení (kampaně 7)
            [
                'name' => 'Sběr signálů komunikačních center',
                'step_id' => 19,
                'type_id' => 1,
                'cost' => 640,
                'description' => 'Analytická zpráva o časech a místech koordinace.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // STEP 20
            [
                'name' => 'Rušivé instrukce pro operátory',
                'step_id' => 20,
                'type_id' => 2,
                'cost' => 430,
                'description' => 'Krátké zprávy zasílané v kritických okamžicích.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Falešné přistávací stránky',
                'step_id' => 20,
                'type_id' => 3,
                'cost' => 1250,
                'description' => 'Vizuálně identické stránky s odlišnými instrukcemi.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // STEP 21
            [
                'name' => 'Vyhodnocení rušení v terénu',
                'step_id' => 21,
                'type_id' => 1,
                'cost' => 680,
                'description' => 'Souhrn reakcí cílových skupin a návrh úprav.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // STEP 22 – Operace Echo (kampaně 8)
            [
                'name' => 'Kurátorský seznam originálních sdělení',
                'step_id' => 22,
                'type_id' => 1,
                'cost' => 720,
                'description' => 'Výběr příspěvků, které je vhodné převzít do echo smyčky.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // STEP 23
            [
                'name' => 'Audiovizuální echo skripty',
                'step_id' => 23,
                'type_id' => 3,
                'cost' => 1380,
                'description' => 'Skripty převádějící text na sérii videí a grafických postů.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // STEP 24
            [
                'name' => 'Plán publikace odrazů',
                'step_id' => 24,
                'type_id' => 2,
                'cost' => 460,
                'description' => 'Nastavení automatizovaných plánů a sledování výkonu.',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
