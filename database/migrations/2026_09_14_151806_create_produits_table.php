<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')
                ->nullable()
                ->constrained('categories')
                ->onDelete('set null');
            $table->string('nom', 255);
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            $table->decimal('prix_promo', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('sku', 100)->unique()->nullable();
            $table->boolean('actif')->default(true);
            $table->decimal('note_moyenne', 3, 2)->default(0);
            $table->integer('nb_ventes')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('actif');
            $table->index('categorie_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};