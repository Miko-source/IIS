<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;   
use Illuminate\Database\Seeder; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Martin',
                'surname' => 'Bures',
                'email' => 'martin@example.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alex',
                'surname' => 'Soska',
                'email' => 'alex@example.com',
                'password' => bcrypt('1234'),
                'role' => 'campaign_manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jana',
                'surname' => 'Nováková',
                'email' => 'JanaNovakova@example.com',
                'password' => bcrypt('jana'),
                'role' => 'coordinator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lucie',
                'surname' => 'Králová',
                'email' => 'lucie.kralova@example.com',
                'password' => bcrypt('lucie123'),
                'role' => 'coordinator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'David',
                'surname' => 'Marek',
                'email' => 'david.marek@example.com',
                'password' => bcrypt('david123'),
                'role' => 'coordinator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Petr',
                'surname' => 'Landa',
                'email' => 'PetrLanda@example.com',
                'password' => bcrypt('petrLanda'),
                'role' => 'worker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Marek',
                'surname' => 'Svoboda',
                'email' => 'marek.svoboda@example.com',
                'password' => bcrypt('marek123'),
                'role' => 'worker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karel',
                'surname' => 'Hruška',
                'email' => 'karel.hruska@example.com',
                'password' => bcrypt('karelhr'),
                'role' => 'worker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lukáš',
                'surname' => 'Váňa',
                'email' => 'lukas.vana@example.com',
                'password' => bcrypt('lukasvana'),
                'role' => 'worker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Filip',
                'surname' => 'Král',
                'email' => 'filip.kral@example.com',
                'password' => bcrypt('filipkral'),
                'role' => 'worker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'David',
                'surname' => 'Pokorný',
                'email' => 'david.pokorny@example.com',
                'password' => bcrypt('davidpok'),
                'role' => 'worker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
