<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivityUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('activity_user')->insert([
            
            // CAMPAIGN 1 - Operace Nedůvěra
            // Dostupní workers: user 6 (Petr)
            // Coordinator: user 3 (Jana)
            
            // Activity 1: Zpracování úvodní analytické zprávy (Step 1, coordinator: Jana-3)
            [
                'activity_id' => 1,
                'user_id' => 6, // Petr (worker)
                'is_confirmed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 2: Příprava struktury narativu (Step 2, coordinator: Lucie-4)
            [
                'activity_id' => 2,
                'user_id' => 6, // Petr (worker)
                'is_confirmed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 3: Grafická tvorba doprovodných vizuálů (Step 2, coordinator: Lucie-4)
            [
                'activity_id' => 3,
                'user_id' => 6, // Petr (worker)
                'is_confirmed' => false, // čeká na potvrzení koordinátora
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 4: Příprava testovacího příspěvku (Step 3, coordinator: David-5)
            [
                'activity_id' => 4,
                'user_id' => 6, // Petr (worker)
                'is_confirmed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 5: Vytvoření grafiky pro testování (Step 3, coordinator: David-5)
            [
                'activity_id' => 5,
                'user_id' => 6, // Petr (worker)
                'is_confirmed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 6: Publikace pilotního obsahu (Step 3, coordinator: David-5)
            [
                'activity_id' => 6,
                'user_id' => 6, // Petr (worker)
                'is_confirmed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // CAMPAIGN 2 - Projekt Rozdělení
            // Dostupní workers: user 7 (Marek)
            
            // Activity 7: Shromáždění konfliktních příkladů (Step 5, coordinator: David-5)
            [
                'activity_id' => 7,
                'user_id' => 7, // Marek (worker)
                'is_confirmed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 8: Generování vizuálů vyvolávajících spor (Step 5, coordinator: David-5)
            [
                'activity_id' => 8,
                'user_id' => 7, // Marek (worker)
                'is_confirmed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 9: Vytvoření sady protichůdných tvrzení (Step 5, coordinator: David-5)
            [
                'activity_id' => 9,
                'user_id' => 7, // Marek (worker)
                'is_confirmed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 10: Publikace rozdělujícího obsahu (Step 5, coordinator: David-5)
            [
                'activity_id' => 10,
                'user_id' => 7, // Marek (worker)
                'is_confirmed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}