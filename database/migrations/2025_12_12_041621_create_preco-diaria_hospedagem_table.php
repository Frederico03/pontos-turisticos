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
        Schema::table('hospedagens', function (Blueprint $table) {
            $table->renameColumn('preco_medio', 'preco_diaria');
        });
    }

    public function down(): void
    {
        Schema::table('hospedagens', function (Blueprint $table) {
            $table->renameColumn('preco_diaria', 'preco_medio');
        });
    }
};
