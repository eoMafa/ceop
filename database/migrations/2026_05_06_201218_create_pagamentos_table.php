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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('restrict');
            $table->foreignId('orcamento_id')->nullable()->constrained('orcamentos')->onDelete('set null');
            $table->string('descricao');
            $table->decimal('valor_total', 10, 2);
            $table->integer('numero_parcelas')->default(1);
            $table->enum('forma_pagamento', [
                'dinheiro',
                'cartao_credito',
                'cartao_debito',
                'pix',
                'convenio',
                'boleto',
            ]);
            $table->enum('status', ['pendente', 'pago', 'parcial', 'cancelado'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
