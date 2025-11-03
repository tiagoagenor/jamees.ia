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
            $table->integer('zoom_default')->nullable()->default(180)->after('imagem_mapa')->comment('Zoom padrão do mapa (50-300%)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empreendimento', function (Blueprint $table) {
            $table->dropColumn('zoom_default');
        });
    }
};
