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
        // Alterar o enum para incluir 'teste'
        DB::statement("ALTER TABLE planos MODIFY COLUMN tipo ENUM('base', 'premium', 'master', 'personalizado', 'teste') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover 'teste' do enum
        DB::statement("ALTER TABLE planos MODIFY COLUMN tipo ENUM('base', 'premium', 'master', 'personalizado') NOT NULL");
    }
};
