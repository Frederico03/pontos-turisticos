<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ponto_id')->constrained('pontos_turisticos')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('nota')->unsigned(); // 1 a 5
            $table->text('comentario')->nullable(); // Comentário justificando a nota
            $table->timestamps();

            // Um usuário pode avaliar um ponto apenas uma vez
            $table->unique(['ponto_id', 'usuario_id']);
            
            $table->index('ponto_id');
            $table->index('nota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
