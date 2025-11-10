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
        Schema::create('lote_venda_parcela', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lote_id');
            $table->integer('numero')->comment('Número da parcela');
            $table->string('tipo', 20)->comment('Tipo: Mensal ou Anual');
            $table->decimal('valor_sem_juros', 15, 2)->comment('Valor da parcela sem juros');
            $table->decimal('valor_com_juros', 15, 2)->comment('Valor da parcela com juros');
            $table->date('vencimento')->comment('Data de vencimento da parcela');
            $table->timestamp('criado_em')->nullable();
            $table->timestamp('atualizado_em')->nullable();

            $table->foreign('lote_id')->references('id')->on('lote')->onDelete('cascade');
            $table->index('lote_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lote_venda_parcela');
    }
};
