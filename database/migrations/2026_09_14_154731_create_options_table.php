<?php
// database/migrations/2026_09_14_000005_create_options_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')
                ->constrained('produits')
                ->onDelete('cascade');
            $table->string('nom', 100);                 // Taille, Couleur, Poids, Saveur
            $table->string('valeur', 100);              // S, M, L, Rouge, 1kg, Poulet
            $table->decimal('prix_supplement', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('ordre')->default(0);
            $table->timestamps();

            $table->index('produit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};