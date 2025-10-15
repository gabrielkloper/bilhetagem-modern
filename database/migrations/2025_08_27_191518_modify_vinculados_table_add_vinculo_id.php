<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro, vamos mapear os tipos atuais para IDs de vínculos
        // Assumindo que os vínculos já foram criados na migração anterior
        
        Schema::table('vinculados', function (Blueprint $table) {
            // Adicionar o novo campo vinculo_id
            $table->foreignId('vinculo_id')->after('responsavel_id')->nullable()->constrained('vinculos');
        });
        
        // Migrar os dados existentes
        // Primeiro precisamos garantir que os vínculos existem
        $vinculos = [
            'crianca' => 'Filho(a)',
            'adolescente' => 'Filho(a)', 
            'adulto' => 'Cônjuge/Companheiro(a)',
            'idoso' => 'Pai/Mãe',
            'pcd' => 'Outro'
        ];
        
        foreach ($vinculos as $tipoAntigo => $descricaoVinculo) {
            // Buscar ou criar o vínculo
            $vinculo = DB::table('vinculos')->where('descricao', $descricaoVinculo)->first();
            if (!$vinculo) {
                $vinculoId = DB::table('vinculos')->insertGetId([
                    'descricao' => $descricaoVinculo,
                    'ativo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $vinculoId = $vinculo->id;
            }
            
            // Atualizar os vinculados com o novo vinculo_id
            DB::table('vinculados')
                ->where('tipo', $tipoAntigo)
                ->update(['vinculo_id' => $vinculoId]);
        }
        
        // Remover o campo tipo antigo (ENUM) e tornar vinculo_id obrigatório
        Schema::table('vinculados', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->foreignId('vinculo_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vinculados', function (Blueprint $table) {
            // Recriar o campo tipo
            $table->enum('tipo', ['crianca', 'adolescente', 'adulto', 'idoso', 'pcd'])->default('crianca');
        });
        
        // Migrar dados de volta
        $vinculosMap = [
            'Filho(a)' => 'crianca',
            'Cônjuge/Companheiro(a)' => 'adulto', 
            'Pai/Mãe' => 'idoso',
            'Outro' => 'pcd'
        ];
        
        $vinculados = DB::table('vinculados')
            ->join('vinculos', 'vinculados.vinculo_id', '=', 'vinculos.id')
            ->select('vinculados.id', 'vinculos.descricao')
            ->get();
            
        foreach ($vinculados as $vinculado) {
            $tipoAntigo = $vinculosMap[$vinculado->descricao] ?? 'crianca';
            DB::table('vinculados')
                ->where('id', $vinculado->id)
                ->update(['tipo' => $tipoAntigo]);
        }
        
        Schema::table('vinculados', function (Blueprint $table) {
            $table->dropForeign(['vinculo_id']);
            $table->dropColumn('vinculo_id');
        });
    }
};
