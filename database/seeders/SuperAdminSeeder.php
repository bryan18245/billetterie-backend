<?php
// database/seeders/SuperAdminSeeder.php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $roleSuperAdmin = Role::where('libelle', 'super_admin')->first();

        User::firstOrCreate(
            ['email' => 'admin@shopci.ci'],
            [
                'name'              => 'Super',
                'surname'           => 'Admin',
                'phone'             => '+2250700000000',
                'password'          => Hash::make('Admin@2026'),
                'langue'            => 'fr',
                'theme'             => 'clair',
                'statut'            => 'actif',
                'role_id'           => $roleSuperAdmin->id,
                'email_verified_at' => now(),
            ]
        );
    }
}