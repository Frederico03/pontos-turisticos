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
        Schema::create('pontos_turisticos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao');
            $table->string('cidade');
            $table->string('estado');
            $table->string('pais')->default('Brasil');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('endereco')->nullable();
            $table->decimal('nota_media', 3, 2)->default(0);
            $table->foreignId('criado_por')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Índices para melhorar performance das buscas
            $table->index(['cidade', 'estado']);
            $table->index('nota_media');
            
            // Constraint: nome único por cidade
            $table->unique(['nome', 'cidade']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pontos_turisticos');
    }
};
