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
        Schema::create('evolucoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prontuario_id')->constrained('prontuarios')->onDelete('restrict');
            $table->foreignId('dentista_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('agendamento_id')->nullable()->constrained('agendamentos')->onDelete('set null');
            $table->string('dente')->nullable(); // ex: 11, 36, "superior direito"
            $table->string('face')->nullable(); // ex: vestibular, lingual, oclusal
            $table->text('descricao');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evolucaos');
    }
};
