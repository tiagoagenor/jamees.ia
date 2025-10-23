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
        Schema::create('empresa_contato', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('empresa_id');
            $table->integer('tipo');
            $table->string('dado')->nullable();
            $table->timestamp('atualizado_em')->nullable();
            $table->timestamp('criado_em')->nullable();

            $table->primary(['id', 'empresa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_contato');
    }
};
