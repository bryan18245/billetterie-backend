<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // User de test (ne plante pas s'il existe déjà)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'     => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Données du catalogue
        $this->call([
            ProduitSeeder::class,
            ImageSeeder::class,
        ]);
    }
}