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
        Schema::create('conciliacoes_bancarias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('conta_empresa_id');
            $table->uuid('empresa_id');
            $table->uuid('usuario_id');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->text('descricao')->nullable();
            $table->string('arquivo_ofx_path')->nullable();
            $table->boolean('conciliado')->default(false);
            $table->timestamps();

            $table->foreign('conta_empresa_id')->references('id')->on('conta_empresa')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conciliacoes_bancarias');
    }
};
