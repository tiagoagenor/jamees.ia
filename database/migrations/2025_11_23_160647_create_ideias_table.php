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
        Schema::create('ideias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('usuario_id');
            $table->string('titulo');
            $table->text('descricao');
            $table->string('categoria');
            $table->integer('status'); // Enum: Em Aberto, Em Análise, Em Desenvolvimento, Concluído, Sem Previsão
            $table->integer('votos')->default(0);
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade');
            $table->index(['status', 'created_at']);
            $table->index('votos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideias');
    }
};
