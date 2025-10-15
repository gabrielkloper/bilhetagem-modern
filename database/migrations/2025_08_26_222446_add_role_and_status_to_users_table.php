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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'operador', 'caixa', 'supervisor'])->default('operador')->after('email');
            $table->enum('status', ['ativo', 'inativo', 'suspenso', 'bloqueado'])->default('ativo')->after('role');
            $table->foreignId('evento_id')->nullable()->after('status')->constrained('eventos')->onDelete('set null');
            $table->index(['status', 'role']);
            $table->index('evento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['evento_id']);
            $table->dropIndex(['status', 'role']);
            $table->dropIndex(['evento_id']);
            $table->dropColumn(['role', 'status', 'evento_id']);
        });
    }
};
