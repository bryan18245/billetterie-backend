<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->string('action', 100);
            $table->string('table_cible', 100)->nullable();
            $table->unsignedBigInteger('id_cible')->nullable();
            $table->json('details')->nullable();
            $table->timestamp('date_action')->useCurrent();
            $table->timestamps();

            $table->index('date_action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};