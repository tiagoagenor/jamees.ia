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
        Schema::create('permissoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('modulo'); // Ex: 'usuarios', 'empresas', 'produtos'
            $table->string('acao'); // Ex: 'listar', 'criar', 'editar', 'visualizar', 'deletar'
            $table->string('nome'); // Ex: 'Listar usuários', 'Criar empresa'
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissoes');
    }
};
