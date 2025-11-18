<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('types')->insert([
            [
                'name' => 'Tvorba článku',
                'description' => 'Psaní textového obsahu, článků a narativních materiálů.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Publikace příspěvku na sociální síti',
                'description' => 'Sdílení obsahu na platformách typu Facebook, X, Instagram.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Příprava vizuálního materiálu',
                'description' => 'Tvorba obrázků, grafiky, vizuálů nebo videí ke kampani.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
