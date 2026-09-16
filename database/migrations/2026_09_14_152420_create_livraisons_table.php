<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')
                ->constrained('commandes')
                ->onDelete('cascade');
            $table->text('adresse_livraison');
            $table->string('ville', 100);
            $table->string('statut', 30)->default('en_preparation');
            $table->decimal('frais', 10, 2)->default(0);
            $table->timestamp('date_expedition')->nullable();
            $table->timestamp('date_livraison')->nullable();
            $table->string('numero_suivi', 100)->nullable();
            $table->timestamps();

            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livraisons');
    }
};