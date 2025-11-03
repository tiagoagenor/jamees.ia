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
        Schema::table('grupos', function (Blueprint $table) {
            $table->uuid('empresa_id')->nullable()->after('id');
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
        });
    }
};
