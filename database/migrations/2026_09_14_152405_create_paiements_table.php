<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')
                ->constrained('commandes')
                ->onDelete('cascade');
            $table->string('moyen', 30);
            $table->string('reference', 100)->unique()->nullable();
            $table->decimal('montant', 12, 2);
            $table->string('statut', 20)->default('en_attente');
            $table->timestamp('date_paiement')->nullable();
            $table->timestamps();

            $table->index('statut');
            $table->index('moyen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};