<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->string('code', 20)->nullable()->unique(); // ex: code administratif
            $table->string('district', 100)->nullable();      // ex: Abidjan, Lagunes...
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->index('nom');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villes');
    }
};