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
        Schema::table('lote_comentario', function (Blueprint $table) {
            $table->string('cliente_id', 36)->nullable()->after('lote_id');
            $table->foreign('cliente_id')->references('id')->on('cliente')->onDelete('set null');
            $table->index('cliente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lote_comentario', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropIndex(['cliente_id']);
            $table->dropColumn('cliente_id');
        });
    }
};
