<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('mode_paiement', 30)->default('livraison')->after('mode');
            $table->string('statut_paiement', 30)->default('en_attente')->after('mode_paiement');
            $table->timestamp('date_paiement')->nullable()->after('statut_paiement');
            $table->string('numero_recu', 50)->nullable()->after('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['mode_paiement', 'statut_paiement', 'date_paiement', 'numero_recu']);
        });
    }
};