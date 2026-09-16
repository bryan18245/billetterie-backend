<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')
                ->constrained('produits')
                ->onDelete('cascade');
            $table->foreignId('client_id')
                ->constrained('clients')
                ->onDelete('cascade');
            $table->integer('note');
            $table->text('commentaire')->nullable();
            $table->string('statut', 20)->default('en_attente');
            $table->timestamps();

            $table->unique(['produit_id', 'client_id']);
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};