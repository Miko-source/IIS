<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        \DB::table('messages')->insert([
            // Activity 1: Zpracování úvodní analytické zprávy
            [
                'activity_id' => 1,
                'user_id' => 3, // Jana
                'content' => 'Analytický tým dokončil shrnutí nejpalčivějších bodů nedůvěry.',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 2: Příprava struktury narativu
            [
                'activity_id' => 2,
                'user_id' => 6, // Petr
                'content' => 'Struktura narativu byla vytvořena a čeká na schválení koordinátora.',
                'success' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 6: Publikace pilotního obsahu
            [
                'activity_id' => 6,
                'user_id' => 1, // Martin
                'content' => 'Pilotní vlna zasáhla 1 200 účtů, reakce jsou v mezích očekávání.',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 10: Publikace rozdělujícího obsahu
            [
                'activity_id' => 10,
                'user_id' => 2, // Alex
                'content' => 'Provokativní posty vyvolaly 63 komentářů mezi dvěma komunitami.',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 7: Shromáždění konfliktních příkladů
            [
                'activity_id' => 7,
                'user_id' => 4, // Lucie
                'content' => 'Konfliktní příklady byly shromážděny a připraveny k dalšímu použití.',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Activity 8: Generování vizuálů vyvolávajících spor
            [
                'activity_id' => 8,
                'user_id' => 5, // David
                'content' => 'Vizuály jsou připraveny, ale vyžadují technické doplnění detailů.',
                'success' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}