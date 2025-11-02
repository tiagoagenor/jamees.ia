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
            $table->integer('quadra_numeracao_tipo')->default(1)->after('status')->comment('1: Numérica, 2: Alfanumérica');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empreendimento', function (Blueprint $table) {
            $table->dropColumn('quadra_numeracao_tipo');
        });
    }
};
