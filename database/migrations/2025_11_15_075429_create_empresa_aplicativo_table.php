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
        Schema::create('empresa_aplicativo', function (Blueprint $table) {
            $table->uuid('empresa_id');
            $table->uuid('aplicativo_id');
            $table->timestamps();

            $table->primary(['empresa_id', 'aplicativo_id']);
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->foreign('aplicativo_id')->references('id')->on('aplicativos')->onDelete('cascade');
            
            $table->index(['empresa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_aplicativo');
    }
};
