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
        Schema::create('lote', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('quadra_id')->nullable();
            $table->uuid('empreendimento_id');
            $table->uuid('lote_status_id')->nullable();
            $table->string('nome')->nullable();
            $table->float('frente')->nullable();
            $table->float('fundo')->nullable();
            $table->float('lateral_direita')->nullable();
            $table->float('lateral_esquerda')->nullable();
            $table->float('valor_m2')->nullable();
            $table->float('m2')->nullable();
            $table->float('valor')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamp('atualizado_em')->nullable();
            $table->timestamp('criado_em')->nullable();

            $table->foreign('quadra_id')->references('id')->on('quadra')->onDelete('set null');
            $table->foreign('empreendimento_id')->references('id')->on('empreendimento')->onDelete('cascade');
            $table->foreign('lote_status_id')->references('id')->on('lote_status')->onDelete('set null');

            $table->index('empreendimento_id');
            $table->index('quadra_id');
            $table->index('lote_status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lote');
    }
};
