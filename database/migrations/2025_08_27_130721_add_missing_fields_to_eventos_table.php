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
            $table->date('data_inicio')->nullable()->after('titulo');
            $table->date('data_fim')->nullable()->after('data_inicio');
            $table->time('hora_inicio')->nullable()->after('data_fim');
            $table->time('hora_fim')->nullable()->after('hora_inicio');
            $table->text('descricao')->nullable()->after('titulo');
            $table->string('endereco', 500)->nullable()->after('cidade');
            $table->decimal('preco_padrao', 10, 2)->default(0)->after('capacidade');
            $table->boolean('publico')->default(false)->after('status');
            $table->boolean('permite_prevenda')->default(false)->after('publico');
            $table->integer('idade_minima')->unsigned()->nullable()->after('permite_prevenda');
            $table->integer('idade_maxima')->unsigned()->nullable()->after('idade_minima');
            $table->unsignedBigInteger('user_criacao')->nullable()->after('user_atualiza');
            
            $table->foreign('user_criacao')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['user_criacao']);
            $table->dropColumn([
                'data_inicio',
                'data_fim', 
                'hora_inicio',
                'hora_fim',
                'descricao',
                'endereco',
                'preco_padrao',
                'publico',
                'permite_prevenda',
                'idade_minima',
                'idade_maxima',
                'user_criacao'
            ]);
        });
    }
};
