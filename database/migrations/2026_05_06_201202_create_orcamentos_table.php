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
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('restrict');
            $table->foreignId('dentista_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('convenio_id')->nullable()->constrained('convenios')->onDelete('set null');
            $table->enum('status', ['rascunho', 'aprovado', 'recusado', 'cancelado'])->default('rascunho');
            $table->enum('desconto_tipo', ['nenhum', 'percentual', 'valor_fixo', 'convenio'])->default('nenhum');
            $table->decimal('desconto_valor', 10, 2)->default(0);
            $table->decimal('total_bruto', 10, 2)->default(0);
            $table->decimal('total_liquido', 10, 2)->default(0);
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
        Schema::dropIfExists('orcamentos');
    }
};
