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
        Schema::create('empresa_plano', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('empresa_id');
            $table->uuid('plano_id');
            $table->enum('periodo', ['mensal', 'trimestral', 'semestral', 'anual']);
            $table->enum('status', ['ativo', 'inativo', 'teste', 'expirado', 'cancelado'])->default('teste');
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->decimal('desconto_aplicado', 5, 2)->default(0);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->date('data_cancelamento')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('teste_gratuito')->default(false);
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->foreign('plano_id')->references('id')->on('planos')->onDelete('cascade');

            $table->index(['empresa_id', 'status']);
            $table->index(['data_fim']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_plano');
    }
};
