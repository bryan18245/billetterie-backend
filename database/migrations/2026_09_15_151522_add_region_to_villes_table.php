<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('villes', function (Blueprint $table) {
            // Ajoute UNIQUEMENT les colonnes manquantes
            if (!Schema::hasColumn('villes', 'region')) {
                $table->string('region', 100)->nullable()->after('nom');
            }
            if (!Schema::hasColumn('villes', 'chef_lieu_region')) {
                $table->boolean('chef_lieu_region')->default(false)->after('region');
            }
            if (!Schema::hasColumn('villes', 'actif')) {
                $table->boolean('actif')->default(true)->after('chef_lieu_region');
            }
        });
    }

    public function down(): void
    {
        Schema::table('villes', function (Blueprint $table) {
            $table->dropColumn(['region', 'chef_lieu_region', 'actif']);
        });
    }
};