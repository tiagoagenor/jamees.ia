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
        Schema::create('aplicativos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('codigo')->unique(); // Ex: 'loteamento'
            $table->decimal('preco_mensal', 10, 2)->default(0);
            $table->decimal('preco_trimestral', 10, 2)->default(0);
            $table->decimal('preco_semestral', 10, 2)->default(0);
            $table->decimal('preco_anual', 10, 2)->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicativos');
    }
};
