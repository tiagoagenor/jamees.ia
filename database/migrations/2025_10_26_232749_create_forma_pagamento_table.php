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
        Schema::create('forma_pagamento', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('empresa_id');
            $table->uuid('conta_empresa_id')->nullable();
            $table->string('nome');
            $table->integer('numero_parcelas')->default(1);
            $table->integer('intercalo_parcelas')->default(30);
            $table->integer('primeira_parcela')->default(0);
            $table->integer('modalidade')->default(1);
            $table->decimal('taxa_banco', 10, 2)->default(0.00);
            $table->decimal('taxa_operadora', 10, 2)->default(0.00);
            $table->decimal('juros_multa', 10, 2)->default(0.00);
            $table->decimal('juros_mora', 10, 2)->default(0.00);
            $table->integer('disponivel')->default(1);
            $table->integer('confirmacao_automatica')->default(0);
            $table->integer('gerar_boleto')->default(0);
            $table->boolean('permite_deletar')->default(true);
            $table->timestamp('atualizado_em');
            $table->timestamp('criado_em');

            // Foreign keys
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->foreign('conta_empresa_id')->references('id')->on('conta_empresa')->onDelete('set null');

            // Indexes
            $table->index('empresa_id');
            $table->index('conta_empresa_id');
            $table->index('disponivel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forma_pagamento');
    }
};
