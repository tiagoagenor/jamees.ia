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
        Schema::create('usuario_horario_acesso', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('usuario_id');
            $table->boolean('ativo')->default(false);
            $table->time('hora_entrada')->nullable();
            $table->time('hora_almoco_inicio')->nullable();
            $table->time('hora_almoco_fim')->nullable();
            $table->time('hora_saida')->nullable();
            $table->json('dias_permitidos')->nullable();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade');
            $table->unique('usuario_id'); // Um usuário só pode ter um horário de acesso
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_horario_acesso');
    }
};
