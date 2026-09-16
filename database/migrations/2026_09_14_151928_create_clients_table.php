<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->string('nom', 150);
            $table->string('email', 255)->nullable();
            $table->string('telephone', 20)->unique();
            $table->string('pays', 100)->default('Côte d\'Ivoire');
            $table->boolean('est_guest')->default(false);
            $table->timestamps();

            $table->index('telephone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};