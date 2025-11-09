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
        Schema::create('funcionario', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('empresa_id');
            $table->uuid('tipo_contribuinte_id')->nullable();
            $table->uuid('usuario_id')->nullable();
            $table->string('nome_fantasia')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('documento')->nullable();
            $table->string('inscricao_suframa')->nullable();
            $table->string('inscricao_estadual')->nullable();
            $table->string('inscricao_estadual_isenta')->nullable();
            $table->string('inscricao_municipal')->nullable();
            $table->string('cnae')->nullable();
            $table->string('regime_tributario')->nullable();
            $table->string('regime_especial')->nullable();
            $table->string('nome')->nullable();
            $table->string('email')->nullable();
            $table->uuid('tipo_contribuinte')->nullable();
            $table->string('rg')->nullable();
            $table->timestamp('nascimento')->nullable();
            $table->string('telefone_comercial')->nullable();
            $table->string('celular')->nullable();
            $table->string('site')->nullable();
            $table->text('observacao')->nullable();
            $table->integer('tipo_pessoa')->nullable(); // 1 = PF, 2 = PJ
            $table->integer('status')->default(1); // 1 = Ativo, 0 = Inativo
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->index(['empresa_id']);
            $table->index(['documento']);
            $table->index(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionario');
    }
};
