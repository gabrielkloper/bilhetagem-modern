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
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsavel_id')->constrained('responsaveis')->onDelete('cascade');
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->boolean('ativo')->default(true);
            $table->boolean('comunica')->default(false);
            $table->enum('device_comunica', ['email', 'sms', 'whatsapp', 'todos'])->default('email');
            $table->timestamp('data_inscricao')->useCurrent();
            $table->timestamps();
            
            // Unique constraint to prevent duplicate inscriptions
            $table->unique(['responsavel_id', 'evento_id']);
            
            // Indexes for better performance
            $table->index(['evento_id', 'ativo']);
            $table->index(['responsavel_id', 'ativo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscricoes');
    }
};
