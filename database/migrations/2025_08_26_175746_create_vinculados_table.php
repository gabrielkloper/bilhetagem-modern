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
        Schema::create('vinculados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsavel_id')->constrained('responsaveis')->onDelete('cascade');
            $table->string('nome', 250);
            $table->date('nascimento');
            $table->enum('tipo', ['crianca', 'adolescente', 'adulto', 'idoso', 'pcd'])->default('crianca');
            $table->boolean('lembrar')->default(false); // lembrar nos próximos acessos
            $table->timestamps();
            $table->index(['responsavel_id', 'tipo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinculados');
    }
};
