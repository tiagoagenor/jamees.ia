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
        Schema::create('empresa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('whitelabel_id');
            $table->uuid('empresa_id')->nullable();
            $table->string('nome_referencia')->nullable();
            $table->string('tipo')->nullable();
            $table->string('nome_fantasia')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('inscricao_estadual')->nullable();
            $table->string('inscricao_estadual_isenta')->nullable();
            $table->string('inscricao_municipal')->nullable();
            $table->string('cnae')->nullable();
            $table->string('regime_tributario')->nullable();
            $table->string('regime_especial')->nullable();
            $table->string('nome')->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->integer('principal')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('atualizado_em')->nullable();
            $table->timestamp('criado_em')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
