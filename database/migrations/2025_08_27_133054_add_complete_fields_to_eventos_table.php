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
        Schema::table('eventos', function (Blueprint $table) {
            // Remover campo modo_pgto enum atual
            $table->dropColumn('modo_pgto');
            
            // Campos de pagamento (múltiplos)
            $table->boolean('aceita_dinheiro')->default(false)->after('preco_padrao');
            $table->boolean('aceita_cartao')->default(false)->after('aceita_dinheiro');
            $table->boolean('aceita_pix')->default(false)->after('aceita_cartao');
            $table->boolean('aceita_gratuito')->default(false)->after('aceita_pix');
            
            // Campo de ativação automática
            $table->boolean('ativo')->default(true)->after('status');
            
            // Campo observações (não existe ainda)
            $table->text('observacoes')->nullable()->after('regras_comunica');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Reverter campos de pagamento
            $table->dropColumn([
                'aceita_dinheiro',
                'aceita_cartao', 
                'aceita_pix',
                'aceita_gratuito',
                'ativo',
                'observacoes'
            ]);
            
            // Restaurar campo modo_pgto
            $table->enum('modo_pgto', ['dinheiro', 'cartao', 'pix', 'gratuito'])->default('dinheiro')->after('status');
        });
    }
};
