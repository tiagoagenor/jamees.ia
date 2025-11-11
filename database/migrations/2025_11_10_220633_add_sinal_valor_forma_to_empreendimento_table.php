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
            $table->string('sinal_valor_forma', 20)->nullable()->after('sinal_tipo')->default('porcentagem')->comment('Forma de valor do sinal: porcentagem ou valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empreendimento', function (Blueprint $table) {
            $table->dropColumn('sinal_valor_forma');
        });
    }
};
