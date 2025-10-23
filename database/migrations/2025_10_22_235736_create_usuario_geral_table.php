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
        Schema::create('usuario_geral', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('usuario_id');
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->string('data_nascimento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('obs')->nullable();
            $table->string('comissao')->nullable();
            $table->string('desconto_maximo')->nullable();
            $table->timestamp('atualizado_em')->nullable();
            $table->timestamp('criado_em')->nullable();

            $table->primary(['id', 'usuario_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_geral');
    }
};
