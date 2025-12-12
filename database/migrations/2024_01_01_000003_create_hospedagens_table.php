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
        Schema::create('hospedagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ponto_id')->constrained('pontos_turisticos')->onDelete('cascade');
            $table->string('nome');
            $table->string('endereco')->nullable();
            $table->string('telefone')->nullable();
            $table->decimal('preco_medio', 10, 2)->nullable();
            $table->enum('tipo', ['hotel', 'pousada', 'hostel', 'resort', 'apartamento'])->default('hotel');
            $table->string('link_reserva')->nullable();
            $table->timestamps();

            $table->index('ponto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospedagens');
    }
};
