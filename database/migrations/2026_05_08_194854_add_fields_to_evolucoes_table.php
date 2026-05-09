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
        Schema::table('evolucoes', function (Blueprint $table) {
             $table->foreignId('procedimento_id')->nullable()->after('agendamento_id')
                ->constrained('procedimentos')->onDelete('set null');
            $table->foreignId('orcamento_id')->nullable()->after('procedimento_id')
                ->constrained('orcamentos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evolucoes', function (Blueprint $table) {
            $table->dropForeign(['procedimento_id']);
            $table->dropForeign(['orcamento_id']);
            $table->dropColumn(['procedimento_id', 'orcamento_id']);
        });
    }
};
