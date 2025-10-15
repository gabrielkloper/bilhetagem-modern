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
        Schema::create('responsaveis', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 250);
            $table->string('cpf', 11)->unique();
            $table->string('email', 250);
            $table->string('telefone1', 20);
            $table->string('telefone2', 20)->nullable();
            $table->date('nascimento');
            $table->timestamp('datahora_input')->useCurrent();
            $table->boolean('ativo')->default(true);
            $table->boolean('comunica')->default(false); // aceita comunicação
            $table->enum('device_comunica', ['email', 'sms', 'whatsapp', 'todos'])->default('email');
            $table->timestamps();
            $table->index(['ativo', 'email']);
            $table->index('cpf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsaveis');
    }
};
