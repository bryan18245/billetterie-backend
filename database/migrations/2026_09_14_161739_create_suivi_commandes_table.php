<?php
// database/migrations/2026_09_14_XXXXXX_create_suivi_commandes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suivi_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')
                ->constrained('commandes')
                ->onDelete('cascade');
            $table->string('code', 6);
            $table->string('token', 100)->unique();       // Token du lien
            $table->string('canal', 20);                   // sms, email
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->integer('tentatives')->default(0);
            $table->timestamps();

            $table->index('token');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suivi_commandes');
    }
};