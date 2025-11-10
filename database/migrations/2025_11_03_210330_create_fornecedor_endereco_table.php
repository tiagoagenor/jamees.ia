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
        Schema::create('fornecedor_endereco', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('fornecedor_id');
            $table->uuid('entidade_tipo_id')->nullable();
            $table->string('cep')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('complemento')->nullable();
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
        Schema::dropIfExists('fornecedor_endereco');
    }
};
