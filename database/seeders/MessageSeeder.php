<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('messages')->insert([
            [
                'activity_id' => 1,
                'user_id' => 3, // Jana
                'content' => 'Analytický tým dokončil shrnutí nejpalčivějších bodů nedůvěry.',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => 2,
                'user_id' => 6, // Petr
                'content' => 'Struktura narativu byla vytvořena a čeká na schválení koordinátora.',
                'success' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => 6,
                'user_id' => 1, // Martin
                'content' => 'Pilotní vlna zasáhla 1 200 účtů, reakce jsou v mezích očekávání.',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => 10,
                'user_id' => 2, // Alex
                'content' => 'Provokativní posty vyvolaly 63 komentářů mezi dvěma komunitami.',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => 7,
                'user_id' => 4, // Lucie
                'content' => 'Konfliktní příklady byly shromážděny a připraveny k dalšímu použití.',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => 8,
                'user_id' => 5, // David
                'content' => 'Vizuály jsou připraveny, ale vyžadují technické doplnění detailů.',
                'success' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => 12,
                'user_id' => 9, // Lukáš
                'content' => 'Technický audit odhalil tři přetížené uzly, čeká se na potvrzení zásahu.',
                'success' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => 15,
                'user_id' => 4, // Lucie
                'content' => 'Monitoring zachytil dvě nové koordinační místnosti, data sdílím v příloze.',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => 19,
                'user_id' => 5, // David
                'content' => 'Kurátorský seznam obsahuje 18 vhodných příspěvků k replikaci.',
                'success' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'activity_id' => 21,
                'user_id' => 2, // Alex
                'content' => 'Plán publikace byl schválen, čekáme na metriky prvních odrazů.',
                'success' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
