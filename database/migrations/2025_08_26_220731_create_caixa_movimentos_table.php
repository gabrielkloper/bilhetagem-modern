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
        Schema::create('caixa_movimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('tipo_despesa_id')->nullable();
            $table->timestamp('datahora_insercao')->useCurrent();
            $table->date('data_caixa');
            $table->enum('tipo_item', ['entrada', 'saida', 'ajuste'])->default('entrada');
            $table->string('descricao', 250);
            $table->decimal('valor', 10, 2);
            $table->boolean('ativo')->default(true);
            $table->foreignId('usuario_exclusao')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('datahora_exclusao')->nullable();
            $table->integer('caixa_abertura_id')->nullable();
            $table->timestamps();
            $table->index(['evento_id', 'data_caixa']);
            $table->index(['data_caixa', 'tipo_item']);
            $table->index('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixa_movimentos');
    }
};
