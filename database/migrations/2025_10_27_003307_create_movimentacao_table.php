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
        Schema::create('movimentacao', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('empresa_id');
            $table->uuid('plano_conta_id');
            $table->uuid('centro_custo_id')->nullable();
            $table->uuid('forma_pagamento_id');
            $table->uuid('conta_empresa_id');
            $table->integer('situacao');
            $table->integer('tipo');
            $table->uuid('parcela_codigo')->nullable();
            $table->integer('numero_parcela')->nullable();
            $table->integer('entidade_tipo')->nullable();
            $table->uuid('entidade_id')->nullable();
            $table->string('descricao');
            $table->date('vencimento');
            $table->text('observacao')->nullable();
            $table->text('informacao_complementar')->nullable();
            $table->decimal('valor', 10, 2);
            $table->decimal('juros', 10, 2)->nullable();
            $table->decimal('desconto', 10, 2)->nullable();
            $table->decimal('valor_total', 10, 2);
            $table->date('data_compensacao')->nullable();
            $table->timestamp('atualizado_em')->useCurrent();
            $table->timestamp('criado_em')->useCurrent();

            // Foreign keys
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->foreign('plano_conta_id')->references('id')->on('plano_conta')->onDelete('cascade');
            $table->foreign('centro_custo_id')->references('id')->on('centro_custo')->onDelete('set null');
            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamento')->onDelete('cascade');
            $table->foreign('conta_empresa_id')->references('id')->on('conta_empresa')->onDelete('cascade');
            $table->foreign('entidade_id')->references('id')->on('entidade')->onDelete('set null');

            // Indexes
            $table->index('empresa_id');
            $table->index('plano_conta_id');
            $table->index('centro_custo_id');
            $table->index('forma_pagamento_id');
            $table->index('conta_empresa_id');
            $table->index('situacao');
            $table->index('tipo');
            $table->index('parcela_codigo');
            $table->index('entidade_id');
            $table->index('vencimento');
            $table->index('data_compensacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacao');
    }
};
