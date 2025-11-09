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
        Schema::create('lote_comentario', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('lote_id', 36);
            $table->string('usuario_id', 36)->nullable();
            $table->text('comentario');
            $table->datetime('criado_em')->nullable();
            $table->datetime('atualizado_em')->nullable();
            
            $table->foreign('lote_id')->references('id')->on('lote')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('set null');
            
            $table->index('lote_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lote_comentario');
    }
};
