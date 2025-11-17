<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->insert([
            [
                'name' => 'Martin',
                'surname' => 'Bures',
                'email' => 'martin@example.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Alex',
                'surname' => 'Soska',
                'email' => 'alex@example.com',
                'password' => bcrypt('1234'),
                'role' => 'campaign_manager',
            ],
            [
                'name' => 'Jana',
                'surname' => 'Nováková',
                'email' => 'JanaNovakova@example.com',
                'password' => bcrypt('jana'),
                'role' => 'coordinator',
            ],
            [
                'name' => 'Petr',
                'surname' => 'Landa',
                'email' => 'PetrLanda@example.com',
                'password' => bcrypt('petrLanda'),
                'role' => 'worker',
            ],
            [
                'name' => 'Marek',
                'surname' => 'Svoboda',
                'email' => 'marek.svoboda@example.com',
                'password' => bcrypt('marek123'),
                'role' => 'worker',
            ],
            [
                'name' => 'Karel',
                'surname' => 'Hruška',
                'email' => 'karel.hruska@example.com',
                'password' => bcrypt('karelhr'),
                'role' => 'worker',
            ],
            [
                'name' => 'Lukáš',
                'surname' => 'Váňa',
                'email' => 'lukas.vana@example.com',
                'password' => bcrypt('lukasvana'),
                'role' => 'worker',
            ],
            [
                'name' => 'Filip',
                'surname' => 'Král',
                'email' => 'filip.kral@example.com',
                'password' => bcrypt('filipkral'),
                'role' => 'worker',
            ],
            [
                'name' => 'David',
                'surname' => 'Pokorný',
                'email' => 'david.pokorny@example.com',
                'password' => bcrypt('davidpok'),
                'role' => 'worker',
            ],
        ]);
    }

}

