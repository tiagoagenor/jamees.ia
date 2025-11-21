<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remover a foreign key pelo nome exato da constraint
        // Schema::table('movimentacao', function (Blueprint $table) {
        //     $table->dropForeign('movimentacao_entidade_id_foreign');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacao', function (Blueprint $table) {
            // Recriar a foreign key caso seja necessário reverter
            $table->foreign('entidade_id', 'movimentacao_entidade_id_foreign')
                  ->references('id')
                  ->on('entidade')
                  ->onDelete('set null');
        });
    }
};
