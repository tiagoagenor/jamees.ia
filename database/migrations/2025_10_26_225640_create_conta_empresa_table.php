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
        Schema::create('conta_empresa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('banco_id');
            $table->uuid('empresa_id');
            $table->integer('tipo');
            $table->string('nome');
            $table->decimal('saldo_inicial', 10, 2)->default(0);
            $table->integer('status')->default(1);
            $table->timestamp('atualizado_em')->nullable();
            $table->timestamp('criado_em')->nullable();

            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');

            $table->index(['empresa_id', 'status']);
            $table->index(['banco_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conta_empresa');
    }
};
