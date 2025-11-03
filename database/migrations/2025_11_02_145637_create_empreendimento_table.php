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
        Schema::create('empreendimento', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('empresa_id');
            $table->string('nome');
            $table->string('imagem')->nullable();
            $table->float('valor_m2')->nullable();
            $table->integer('maximo_parcelas')->nullable();
            $table->integer('sinal')->comment('1 sim, 2 não');
            $table->integer('sinal_tipo')->nullable()->comment('1: fixo, 2 porcentagem');
            $table->float('sinal_valor')->nullable();
            $table->integer('status')->default(1)->comment('0 inativo, 1 ativo');
            $table->timestamp('atualizado_em')->nullable();
            $table->timestamp('criado_em')->nullable();

            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->index('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empreendimento');
    }
};
