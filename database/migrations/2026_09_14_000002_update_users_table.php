<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ÉTAPE 1 : Ajouter les colonnes
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname', 100)->after('name')->nullable();
            $table->string('phone', 20)->unique()->nullable()->after('email');
            $table->string('langue', 5)->default('fr')->after('password');
            $table->string('theme', 10)->default('clair')->after('langue');
            $table->string('statut', 20)->default('actif')->after('theme');
            $table->timestamp('date_derniere_connexion')->nullable();
            $table->softDeletes();
        });

        // ÉTAPE 2 : Ajouter la colonne role_id seule
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('id');
        });

        // ÉTAPE 3 : Ajouter la contrainte FK seule
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'phone',
                'langue',
                'theme',
                'statut',
                'date_derniere_connexion',
                'role_id',
                'deleted_at',
            ]);
        });
    }
};