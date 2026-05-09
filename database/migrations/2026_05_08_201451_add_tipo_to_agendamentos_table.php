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
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->enum('tipo', [
                'orcamento',  // consulta para fazer orçamento
                'consulta',   // consulta vinculada a orçamento
                'retorno',    // retorno/revisão
                'avaliacao',  // avaliação inicial
            ])->default('consulta')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
