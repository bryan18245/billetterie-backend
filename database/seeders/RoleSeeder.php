<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['libelle' => 'client',      'description' => 'Client acheteur',       'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'admin',       'description' => 'Administrateur',        'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'super_admin', 'description' => 'Super administrateur',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}