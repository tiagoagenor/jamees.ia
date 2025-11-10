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
            $table->string('juros_forma', 20)->nullable()->after('sinal_valor')->default('valor')->comment('Forma de juros: porcentagem ou valor');
            $table->decimal('juros', 10, 2)->nullable()->after('juros_forma')->comment('Valor ou percentual de juros');
            $table->string('multa_forma', 20)->nullable()->after('juros')->default('valor')->comment('Forma de multa: porcentagem ou valor');
            $table->decimal('multa', 10, 2)->nullable()->after('multa_forma')->comment('Valor ou percentual de multa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empreendimento', function (Blueprint $table) {
            $table->dropColumn(['juros_forma', 'juros', 'multa_forma', 'multa']);
        });
    }
};
