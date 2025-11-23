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
        Schema::create('ideia_comentarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ideia_id');
            $table->uuid('usuario_id');
            $table->text('comentario');
            $table->timestamps();

            $table->foreign('ideia_id')->references('id')->on('ideias')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuario')->onDelete('cascade');
            $table->index('ideia_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideia_comentarios');
    }
};
