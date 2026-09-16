<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParametreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('parametres')->insert([
            ['cle' => 'devise',          'valeur' => 'FCFA',             'type' => 'string',  'categorie' => 'general',   'description' => 'Devise',               'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'frais_livraison', 'valeur' => '1500',             'type' => 'decimal', 'categorie' => 'livraison', 'description' => 'Frais de livraison',   'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'nom_plateforme',  'valeur' => 'ShopCI',           'type' => 'string',  'categorie' => 'general',   'description' => 'Nom de la plateforme', 'created_at' => now(), 'updated_at' => now()],
            ['cle' => 'email_contact',   'valeur' => 'contact@shopci.ci', 'type' => 'string', 'categorie' => 'general',   'description' => 'Email de contact',     'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}