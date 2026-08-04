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
        Schema::create('caixa_aberturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('data_abertura');
            $table->time('hora_abertura');
            $table->timestamp('datahora_abertura');
            $table->decimal('valor_inicial', 10, 2);
            $table->decimal('valor_final', 10, 2)->nullable();
            $table->timestamp('datahora_fechamento')->nullable();
            $table->foreignId('user_fechamento_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['aberto', 'fechado'])->default('aberto');
            $table->text('observacoes_abertura')->nullable();
            $table->text('observacoes_fechamento')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->index(['evento_id', 'data_abertura']);
            $table->index(['evento_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixa_aberturas');
    }
};
