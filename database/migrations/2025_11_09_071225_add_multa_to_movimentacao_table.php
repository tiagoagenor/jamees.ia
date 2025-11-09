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
            $table->decimal('multa', 10, 2)->nullable()->after('juros_tipo')->comment('Multa por atraso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacao', function (Blueprint $table) {
            $table->dropColumn('multa');
        });
    }
};
