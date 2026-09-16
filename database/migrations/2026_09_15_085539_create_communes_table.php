<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ville_id')
                ->constrained('villes')
                ->onDelete('cascade');   // si la ville est supprimée, ses communes aussi

            $table->string('nom', 150);
            $table->string('code', 20)->nullable()->unique();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->index('nom');
            // une commune peut avoir le même nom dans deux villes différentes,
            // donc on ne met PAS de unique sur (ville_id + nom) ici.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communes');
    }
};