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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id')->nullable(); // ID do usuário que fez a alteração
            $table->string('user_name')->nullable(); // Nome do usuário
            $table->string('user_email')->nullable(); // Email do usuário
            $table->string('empresa_id')->nullable(); // ID da empresa
            $table->string('empresa_nome')->nullable(); // Nome da empresa
            $table->string('action'); // CREATE, UPDATE, DELETE, LOGIN, LOGOUT, etc.
            $table->string('model_type'); // Tipo do modelo (ex: Movimentacao, Usuario, etc.)
            $table->string('model_id')->nullable(); // ID do registro alterado
            $table->string('model_name')->nullable(); // Nome/descrição do registro
            $table->json('old_values')->nullable(); // Valores antes da alteração
            $table->json('new_values')->nullable(); // Valores após a alteração
            $table->string('ip_address')->nullable(); // IP do usuário
            $table->string('user_agent')->nullable(); // User agent do navegador
            $table->string('url')->nullable(); // URL onde a ação foi executada
            $table->text('description')->nullable(); // Descrição da ação
            $table->json('metadata')->nullable(); // Dados adicionais
            $table->timestamps();

            // Índices para melhor performance
            $table->index(['user_id', 'created_at']);
            $table->index(['empresa_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
