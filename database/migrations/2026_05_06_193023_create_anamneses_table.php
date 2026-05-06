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
        Schema::create('anamneses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prontuario_id')->unique()->constrained('prontuarios')->onDelete('cascade');
            $table->boolean('alergia')->default(false);
            $table->text('alergia_descricao')->nullable();
            $table->boolean('medicamentos_uso')->default(false);
            $table->text('medicamentos_descricao')->nullable();
            $table->boolean('pressao_alta')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('cardiopatia')->default(false);
            $table->boolean('gestante')->default(false);
            $table->boolean('fumante')->default(false);
            $table->boolean('alcool')->default(false);
            $table->boolean('doenca_renal')->default(false);
            $table->boolean('doenca_hepatica')->default(false);
            $table->boolean('problemas_coagulacao')->default(false);
            $table->text('outras_doencas')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anamneses');
    }
};
