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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 250);
            $table->string('local', 250);
            $table->string('cidade', 250);
            $table->timestamp('inicio')->nullable();
            $table->timestamp('fim')->nullable();
            $table->enum('status', ['ativo', 'inativo', 'finalizado'])->default('ativo');
            $table->enum('modo_pgto', ['dinheiro', 'cartao', 'pix', 'gratuito'])->default('dinheiro');
            $table->string('hash', 300)->unique();
            $table->text('regras_home');
            $table->text('regras_cadastro');
            $table->text('regras_parque');
            $table->text('msg_fimreserva');
            $table->integer('capacidade')->unsigned();
            $table->integer('tempo_atualiza')->unsigned()->default(10); // segundos
            $table->boolean('mostra_tempo')->default(true);
            $table->integer('tempo_tela')->unsigned()->default(3600); // segundos
            $table->timestamp('last_atualiza')->nullable();
            $table->foreignId('user_atualiza')->nullable()->constrained('users')->onDelete('set null');
            $table->text('regras_comunica');
            $table->string('timezone', 50)->default('America/Sao_Paulo');
            $table->timestamps();
            $table->index(['status', 'inicio']);
            $table->index('cidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
