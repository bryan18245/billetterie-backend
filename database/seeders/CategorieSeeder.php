<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['libelle' => 'Mode',         'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Électronique', 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Maison',       'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Beauté',       'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Alimentation', 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Sport',        'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Animaux',      'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Livres',       'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}