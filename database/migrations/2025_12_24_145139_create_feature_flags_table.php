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
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('codigo')->unique()->comment('Código único da feature flag (ex: novo_dashboard, exportacao_pdf)');
            $table->string('nome')->comment('Nome descritivo da feature flag');
            $table->text('descricao')->nullable()->comment('Descrição da feature flag');
            $table->enum('escopo', ['global', 'empresa', 'usuario'])->default('global')->comment('Escopo da feature flag: global, empresa ou usuario');
            $table->uuid('empresa_id')->nullable()->comment('ID da empresa (se escopo for empresa)');
            $table->uuid('usuario_id')->nullable()->comment('ID do usuário (se escopo for usuario)');
            $table->boolean('ativo')->default(false)->comment('Se a feature flag está ativa');
            $table->json('configuracao')->nullable()->comment('Configurações adicionais em JSON');
            $table->timestamps();

            // Índices
            $table->index('codigo');
            $table->index(['escopo', 'empresa_id', 'usuario_id']);
            $table->index('ativo');

            // Foreign keys
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
    }
};
