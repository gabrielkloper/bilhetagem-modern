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
        Schema::table('caixa_movimentos', function (Blueprint $table) {
            // Change integer columns to bigint to match the foreign key table
            $table->unsignedBigInteger('tipo_despesa_id')->nullable()->change();
            $table->unsignedBigInteger('caixa_abertura_id')->nullable()->change();

            $table->foreign('tipo_despesa_id')
                ->references('id')
                ->on('tipo_despesas')
                ->onDelete('set null');

            $table->foreign('caixa_abertura_id')
                ->references('id')
                ->on('caixa_aberturas')
                ->onDelete('set null');

            $table->index('tipo_despesa_id');
            $table->index('caixa_abertura_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caixa_movimentos', function (Blueprint $table) {
            $table->dropForeign(['tipo_despesa_id']);
            $table->dropForeign(['caixa_abertura_id']);
            $table->dropIndex(['tipo_despesa_id']);
            $table->dropIndex(['caixa_abertura_id']);

            // Revert back to integer
            $table->integer('tipo_despesa_id')->nullable()->change();
            $table->integer('caixa_abertura_id')->nullable()->change();
        });
    }
};
