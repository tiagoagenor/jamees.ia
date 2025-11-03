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
        Schema::create('bancos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome_institucional');
            $table->string('nome_normalizado');
            $table->string('numero_banco');
            $table->string('imagem')->nullable();
            $table->string('url')->nullable();
            $table->integer('status')->default(1);
            $table->timestamp('criado_em')->nullable();
            $table->timestamp('atualizado_em')->nullable();

            $table->index(['status']);
            $table->index(['numero_banco']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos');
    }
};
