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
            $table->string('juros_forma', 20)->nullable()->after('juros_tipo')->default('valor')->comment('Forma de juros: porcentagem ou valor');
            $table->string('multa_forma', 20)->nullable()->after('multa')->default('valor')->comment('Forma de multa: porcentagem ou valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacao', function (Blueprint $table) {
            $table->dropColumn(['juros_forma', 'multa_forma']);
        });
    }
};
