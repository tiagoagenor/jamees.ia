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
        Schema::table('quadra', function (Blueprint $table) {
            $table->uuid('empreendimento_id')->nullable()->after('nome');
            $table->foreign('empreendimento_id')->references('id')->on('empreendimento')->onDelete('cascade');
            $table->index('empreendimento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quadra', function (Blueprint $table) {
            $table->dropForeign(['empreendimento_id']);
            $table->dropIndex(['empreendimento_id']);
            $table->dropColumn('empreendimento_id');
        });
    }
};
