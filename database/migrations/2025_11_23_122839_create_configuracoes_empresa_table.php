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
        Schema::create('configuracoes_empresa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('empresa_id');
            $table->text('texto_boas_vindas')->nullable();
            $table->text('frase_empresa')->nullable();
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->unique('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes_empresa');
    }
};
