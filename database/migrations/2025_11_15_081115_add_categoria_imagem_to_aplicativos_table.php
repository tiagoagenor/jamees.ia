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
        Schema::table('aplicativos', function (Blueprint $table) {
            $table->string('categoria')->nullable()->after('codigo');
            $table->string('imagem')->nullable()->after('categoria');
            $table->text('detalhes')->nullable()->after('descricao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aplicativos', function (Blueprint $table) {
            $table->dropColumn(['categoria', 'imagem', 'detalhes']);
        });
    }
};
