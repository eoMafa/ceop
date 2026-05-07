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
        Schema::create('evolucao_materiais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evolucao_id')->constrained('evolucoes')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('restrict');
            $table->decimal('quantidade', 10, 2);
            $table->decimal('valor_unitario', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evolucao_materiais');
    }
};
