<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        \DB::table('topics')->insert([
            [
                'name' => 'Zpochybňování institucí',
                'target_group' => 'Lidé nespokojení se státní správou',
                'description' => 'Modelový narrativ zaměřený na systematické snižování důvěry v oficiální instituce. Obsahuje simulované postupy, jak se testují reakce veřejnosti na nejistotu a chaos.',
                'sources' => 'Fiktivní analytické dokumenty A1–A3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Polarizace společnosti',
                'target_group' => 'Skupiny se silně rozdílnými názory',
                'description' => 'Téma zaměřené na rozehrávání konfliktů mezi vybranými komunitami. Používá se pro testování dopadů konfliktů na sociální soudržnost v modelových scénářích.',
                'sources' => 'Simulační studie S-2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Krizová panika',
                'target_group' => 'Široká veřejnost během krizových situací',
                'description' => 'Fiktivní model krizové paniky využívající vágní a neúplné informace ke sledování šíření neověřených tvrzení. Slouží výhradně k výzkumnému modelování.',
                'sources' => 'Výzkumný prototyp K-Model-5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plochá země',
                'target_group' => 'Jednotlivci skeptičtí vůči vědeckému konsensu',
                'description' => 'Kampaň simuluje šíření tvrzení, že Země není kulatá, ale plochá.',
                'sources' => 'Simulace AgentNet 3.2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Umělá Inteligence a kontrola mysli',
                'target_group' => 'Lidé citliví na technologické konspirační teorie',
                'description' => 'Kampaň modeluje šíření myšlenky, že umělá inteligence je používána k ovládání lidské mysli prostřednictvím různých technologií. Inspirováno filmem Matrix',
                'sources' => 'Studie F-CON-2024',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}


