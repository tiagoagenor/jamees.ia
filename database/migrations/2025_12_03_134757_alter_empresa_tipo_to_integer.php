<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Converter valores string para integer antes de alterar a coluna
        DB::statement("UPDATE empresa SET tipo = CASE 
            WHEN tipo = 'PJ' THEN 1 
            WHEN tipo = 'PF' THEN 2 
            ELSE NULL 
        END");

        // Alterar a coluna de string para integer
        Schema::table('empresa', function (Blueprint $table) {
            $table->integer('tipo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Alterar a coluna de integer para string
        Schema::table('empresa', function (Blueprint $table) {
            $table->string('tipo')->nullable()->change();
        });

        // Converter valores integer para string
        DB::statement("UPDATE empresa SET tipo = CASE 
            WHEN tipo = 1 THEN 'PJ' 
            WHEN tipo = 2 THEN 'PF' 
            ELSE NULL 
        END");
    }
};
