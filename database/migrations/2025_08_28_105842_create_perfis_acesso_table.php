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
        Schema::create('perfis_acesso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->string('titulo', 250);
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->boolean('padrao_evento')->default(false);
            $table->decimal('preco_base', 10, 2)->default(0);
            $table->integer('idade_minima')->nullable();
            $table->integer('idade_maxima')->nullable();
            $table->decimal('altura_minima', 4, 2)->nullable(); // em metros: 1.20
            $table->decimal('altura_maxima', 4, 2)->nullable();
            $table->enum('tipo', ['altura', 'idade', 'especial', 'padrao'])->default('padrao');
            $table->timestamps();
            
            $table->index(['evento_id', 'ativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfis_acesso');
    }
};
