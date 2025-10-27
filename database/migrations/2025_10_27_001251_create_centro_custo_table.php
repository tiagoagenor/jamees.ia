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
        Schema::create('centro_custo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('empresa_id')->nullable();
            $table->string('nome');
            $table->integer('status')->default(1); // 1 = Ativo, 0 = Inativo
            $table->timestamp('atualizado_em')->useCurrent();
            $table->timestamp('criado_em')->useCurrent();

            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');

            $table->index('empresa_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centro_custo');
    }
};
