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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_estoque_id')->nullable()->constrained('categoria_estoques')->onDelete('set null');
            $table->foreignId('fornecedor_id')->nullable()->constrained('fornecedores')->onDelete('set null');
            $table->string('nome');
            $table->string('codigo')->nullable()->unique();
            $table->text('descricao')->nullable();
            $table->enum('unidade', ['un', 'cx', 'ml', 'g', 'kg', 'l', 'pct', 'rolo'])->default('un');
            $table->decimal('estoque_atual', 10, 2)->default(0);
            $table->decimal('estoque_minimo', 10, 2)->default(0);
            $table->decimal('valor_custo', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
