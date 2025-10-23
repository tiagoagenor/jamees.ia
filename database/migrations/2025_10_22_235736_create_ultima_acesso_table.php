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
        Schema::create('ultima_acesso', function (Blueprint $table) {
            $table->uuid('usuario_id');
            $table->uuid('whitelabel_id');
            $table->uuid('empresa_id');

            $table->primary(['usuario_id', 'whitelabel_id', 'empresa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ultima_acesso');
    }
};
