<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
{
    $this->call([
        UserSeeder::class,
        TopicSeeder::class,
        CampaignSeeder::class,
        StepSeeder::class,
        TypeSeeder::class,
        ActivitySeeder::class,
        ActivityUserSeeder::class,
        CampaignUserSeeder::class,
        MessageSeeder::class,
    ]);
}

}
