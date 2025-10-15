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
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->foreignId('evento_id')->after('id')->constrained('eventos')->onDelete('cascade');
            $table->index(['evento_id', 'ativo']); // Index para performance nas consultas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->dropForeign(['evento_id']);
            $table->dropIndex(['evento_id', 'ativo']);
            $table->dropColumn('evento_id');
        });
    }
};
