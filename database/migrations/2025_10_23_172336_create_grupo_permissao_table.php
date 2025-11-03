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
        Schema::create('grupo_permissao', function (Blueprint $table) {
            $table->uuid('grupo_id');
            $table->uuid('permissao_id');
            $table->boolean('concedida')->default(true);
            $table->timestamps();

            $table->primary(['grupo_id', 'permissao_id']);
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
            $table->foreign('permissao_id')->references('id')->on('permissoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_permissao');
    }
};
