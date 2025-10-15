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
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('prevenda_id')->nullable()->constrained('prevendas')->onDelete('set null');
            $table->foreignId('vinculado_id')->constrained('vinculados')->onDelete('cascade');
            $table->foreignId('pacote_id')->constrained('pacotes')->onDelete('cascade');
            $table->enum('perfil_acesso', ['crianca', 'adolescente', 'adulto', 'idoso', 'pcd', 'funcionario'])->default('crianca');
            $table->enum('status', ['ativo', 'finalizado', 'cancelado', 'excedido'])->default('ativo');
            $table->enum('motivo_cancela', ['desistencia', 'problema_pagamento', 'excesso_tempo', 'outro'])->nullable();
            $table->timestamp('datahora_entrada')->useCurrent();
            $table->timestamp('datahora_saida')->nullable();
            $table->integer('tempo_excedido')->unsigned()->default(0); // em segundos
            $table->integer('tempo_permanencia')->unsigned()->default(0); // em segundos
            $table->boolean('pgto_extra')->default(false);
            $table->decimal('pgto_extra_valor', 10, 2)->default(0);
            $table->boolean('ativo')->default(true);
            // campos do pacote no momento da entrada (snapshot)
            $table->decimal('pacote_valor', 10, 2);
            $table->integer('pacote_duracao'); // em minutos
            $table->decimal('pacote_valor_adicional', 10, 2)->default(0);
            $table->integer('pacote_qtde_compra')->default(1);
            $table->integer('pacote_minutos_compra'); // total de minutos comprados
            $table->integer('pacote_tolerancia')->default(0); // em minutos
            $table->string('pacote_nome', 150);
            $table->boolean('autorizado')->default(false);
            $table->timestamp('datahora_autorizacao')->nullable();
            $table->timestamps();
            $table->index(['vinculado_id', 'status']);
            $table->index(['datahora_entrada', 'status']);
            $table->index('pacote_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};
