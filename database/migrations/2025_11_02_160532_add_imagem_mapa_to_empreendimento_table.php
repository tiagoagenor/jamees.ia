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
        Schema::table('empreendimento', function (Blueprint $table) {
            $table->string('imagem_mapa')->nullable()->after('imagem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empreendimento', function (Blueprint $table) {
            $table->dropColumn('imagem_mapa');
        });
    }
};
