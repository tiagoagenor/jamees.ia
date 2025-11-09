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
        Schema::table('movimentacao', function (Blueprint $table) {
            $table->string('juros_tipo', 20)->nullable()->after('juros')->comment('Tipo de juros: fixo ou por_dia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacao', function (Blueprint $table) {
            $table->dropColumn('juros_tipo');
        });
    }
};
