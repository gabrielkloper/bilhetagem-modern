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
        Schema::create('pacotes', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 250); // nome interno do pacote
            $table->string('rotulo_cliente', 250); // nome exibido para o cliente
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->boolean('ativo')->default(true);
            $table->decimal('valor', 8, 2)->unsigned(); // valor em reais
            $table->integer('duracao')->unsigned(); // duração em minutos
            $table->integer('tolerancia')->unsigned()->default(0); // tolerância em minutos
            $table->decimal('valor_minuto_adicional', 6, 2)->unsigned()->default(0); // valor por minuto adicional
            $table->timestamps();
            $table->index(['evento_id', 'ativo']);
            $table->index('valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacotes');
    }
};
