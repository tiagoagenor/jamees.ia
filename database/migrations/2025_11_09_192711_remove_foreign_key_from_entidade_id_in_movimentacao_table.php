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
        // Schema::table('movimentacao', function (Blueprint $table) {
        //     // Remover a foreign key constraint de entidade_id
        //     $table->dropForeign(['entidade_id']);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacao', function (Blueprint $table) {
            // Recriar a foreign key caso seja necessário reverter
            $table->foreign('entidade_id')->references('id')->on('entidade')->onDelete('set null');
        });
    }
};
