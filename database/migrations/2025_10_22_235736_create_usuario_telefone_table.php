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
        Schema::create('usuario_telefone', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('usuario_id');
            $table->string('tipo')->nullable();
            $table->string('ddi')->nullable();
            $table->string('ddd')->nullable();
            $table->string('numero')->nullable();
            $table->timestamp('atualizado_em')->nullable();
            $table->timestamp('criado_em')->nullable();

            $table->primary(['id', 'usuario_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_telefone');
    }
};
