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
        Schema::table('funcionario', function (Blueprint $table) {
            // Adicionar índice único em usuario_id para garantir que cada funcionário só pode ter um usuário
            // E que cada usuário só pode estar vinculado a um funcionário
            $table->unique('usuario_id', 'funcionario_usuario_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('funcionario', function (Blueprint $table) {
            $table->dropUnique('funcionario_usuario_id_unique');
        });
    }
};
