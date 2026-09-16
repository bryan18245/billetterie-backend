<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained('clients')
                ->onDelete('cascade');
            $table->string('libelle', 50);
            $table->text('adresse');
            $table->string('ville', 100);
            $table->string('pays', 100)->default('Côte d\'Ivoire');
            $table->string('code_postal', 20)->nullable();
            $table->string('telephone', 20);
            $table->boolean('principale')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adresses');
    }
};