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
        Schema::create('prevendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsavel_id')->constrained('responsaveis')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->date('data_acesso'); // data pretendida de acesso
            $table->enum('status', ['pendente', 'confirmado', 'cancelado', 'utilizado', 'expirado', 'finalizado'])->default('pendente');
            $table->timestamp('datahora_solicita')->useCurrent(); // quando foi solicitado
            $table->timestamp('datahora_efetiva')->nullable(); // quando foi confirmado/efetivado
            $table->timestamp('datahora_efetiva_saida')->nullable(); // quando saiu
            $table->enum('tipo_pagamento', ['dinheiro', 'cartao_debito', 'cartao_credito', 'pix', 'gratuito'])->nullable();
            $table->decimal('valor_pagamento', 10, 2)->default(0); // valor pago
            $table->timestamp('datahora_pagamento')->nullable(); // quando foi pago
            $table->timestamp('datahora_reserva')->nullable(); // prazo da reserva
            $table->enum('origem', ['site', 'app', 'balcao', 'telefone'])->default('site');
            $table->timestamps();
            $table->index(['responsavel_id', 'evento_id']);
            $table->index(['data_acesso', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prevendas');
    }
};
