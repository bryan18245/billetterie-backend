<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('cle', 100)->unique();
            $table->text('valeur');
            $table->string('type', 20)->default('string');
            $table->string('categorie', 50)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('modifie_par')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamps();

            $table->index('categorie');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};