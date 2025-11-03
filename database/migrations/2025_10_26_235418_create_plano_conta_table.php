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
        Schema::create('plano_conta', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('empresa_id')->nullable();
            $table->uuid('plano_conta_id')->nullable(); // Para hierarquia (pai/filho)
            $table->uuid('dre_id')->nullable(); // Relacionamento com DRE
            $table->string('nome');
            $table->smallInteger('movimentacao'); // 1 = Débito, 2 = Crédito
            $table->integer('ordem_pai'); // Ordem do item pai
            $table->integer('ordem_filho')->nullable(); // Ordem do item filho
            $table->timestamp('atualizado_em')->useCurrent();
            $table->timestamp('criado_em')->useCurrent();

            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->foreign('plano_conta_id')->references('id')->on('plano_conta')->onDelete('cascade');
            $table->foreign('dre_id')->references('id')->on('dre')->onDelete('set null');

            $table->index('empresa_id');
            $table->index('plano_conta_id');
            $table->index('dre_id');
            $table->index('movimentacao');
            $table->index(['ordem_pai', 'ordem_filho']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plano_conta');
    }
};
