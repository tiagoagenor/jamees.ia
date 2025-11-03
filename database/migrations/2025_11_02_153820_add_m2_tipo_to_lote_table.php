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
        Schema::table('lote', function (Blueprint $table) {
            $table->integer('m2_tipo')->default(1)->after('m2')->comment('1: Calculado, 2: Manual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lote', function (Blueprint $table) {
            $table->dropColumn('m2_tipo');
        });
    }
};
