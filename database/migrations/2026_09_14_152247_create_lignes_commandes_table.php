<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lignes_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')
                ->constrained('commandes')
                ->onDelete('cascade');
            $table->foreignId('produit_id')
                ->constrained('produits')
                ->onDelete('restrict');
            $table->string('nom_produit', 255);
            $table->json('options_choisies')->nullable();
            $table->decimal('prix_unitaire', 10, 2);
            $table->integer('quantite');
            $table->decimal('sous_total', 12, 2);
            $table->timestamps();

            $table->index('commande_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_commandes');
    }
};