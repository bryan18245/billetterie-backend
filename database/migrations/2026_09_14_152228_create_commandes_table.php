<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained('clients')
                ->onDelete('cascade');
            $table->string('mode', 20)->default('guest');
            $table->string('reference_unique', 50)->unique();
            $table->decimal('montant_total', 12, 2);
            $table->decimal('montant_reduction', 12, 2)->default(0);
            $table->string('statut', 30)->default('en_attente');
            $table->foreignId('adresse_id')
                ->nullable()
                ->constrained('adresses')
                ->onDelete('set null');
            $table->foreignId('promo_id')
                ->nullable()
                ->constrained('promos')
                ->onDelete('set null');
            $table->string('email_guest', 255)->nullable();
            $table->timestamp('date_commande')->useCurrent();
            $table->timestamps();

            $table->index('statut');
            $table->index('mode');
            $table->index('reference_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};