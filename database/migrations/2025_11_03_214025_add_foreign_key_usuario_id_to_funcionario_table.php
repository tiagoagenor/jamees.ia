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
            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('set null');
            $table->index(['usuario_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('funcionario', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropIndex(['usuario_id']);
        });
    }
};
