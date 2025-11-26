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
        Schema::create('transacoes_ofx', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('conciliacao_bancaria_id');
            $table->string('tipo'); // 'CREDIT' ou 'DEBIT'
            $table->date('data');
            $table->decimal('valor', 15, 2);
            $table->string('descricao')->nullable();
            $table->string('numero_documento')->nullable();
            $table->string('fitid')->nullable(); // ID único da transação no OFX
            $table->boolean('conciliado')->default(false);
            $table->uuid('conta_pagar_id')->nullable();
            $table->uuid('conta_receber_id')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->foreign('conciliacao_bancaria_id')->references('id')->on('conciliacoes_bancarias')->onDelete('cascade');
            $table->index('conciliacao_bancaria_id');
            $table->index('data');
            $table->index('conciliado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacoes_ofx');
    }
};
