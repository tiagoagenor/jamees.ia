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
        Schema::create('fornecedor_contato', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('fornecedor_id');
            $table->uuid('entidade_tipo_id')->nullable();
            $table->string('nome');
            $table->string('contato'); // telefone, email, etc.
            $table->string('cargo')->nullable();
            $table->string('observacao')->nullable();
            $table->timestamps();

            $table->foreign('fornecedor_id')->references('id')->on('fornecedor')->onDelete('cascade');
            $table->index(['fornecedor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornecedor_contato');
    }
};
