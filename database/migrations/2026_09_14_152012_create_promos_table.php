<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('type_reduction', 20);
            $table->decimal('valeur', 10, 2);
            $table->decimal('montant_minimum', 10, 2)->default(0);
            $table->timestamp('date_debut')->nullable();
            $table->timestamp('date_fin')->nullable();
            $table->integer('quantite_totale')->nullable();
            $table->integer('quantite_utilisee')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->index('code');
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};