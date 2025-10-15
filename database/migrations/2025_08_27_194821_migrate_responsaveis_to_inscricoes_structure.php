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
        // Step 1: Migrate existing responsaveis data to inscricoes table
        $responsaveis = DB::table('responsaveis')->get();
        
        foreach ($responsaveis as $responsavel) {
            DB::table('inscricoes')->insert([
                'responsavel_id' => $responsavel->id,
                'evento_id' => $responsavel->evento_id,
                'ativo' => $responsavel->ativo,
                'comunica' => $responsavel->comunica,
                'device_comunica' => $responsavel->device_comunica,
                'data_inscricao' => $responsavel->datahora_input ?? $responsavel->created_at,
                'created_at' => $responsavel->created_at,
                'updated_at' => $responsavel->updated_at,
            ]);
        }
        
        // Step 2: Remove evento-specific fields from responsaveis table
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->dropForeign(['evento_id']);
            $table->dropColumn([
                'evento_id',
                'ativo',
                'comunica', 
                'device_comunica',
                'datahora_input'
            ]);
        });
        
        // Step 3: Add unique constraint on CPF since responsaveis are now global
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->unique('cpf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Add back the removed columns
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->dropUnique(['cpf']);
            $table->foreignId('evento_id')->nullable()->constrained('eventos');
            $table->boolean('ativo')->default(true);
            $table->boolean('comunica')->default(false);
            $table->enum('device_comunica', ['email', 'sms', 'whatsapp', 'todos'])->default('email');
            $table->timestamp('datahora_input')->nullable();
        });
        
        // Step 2: Migrate data back from inscricoes to responsaveis
        $inscricoes = DB::table('inscricoes')->get();
        
        foreach ($inscricoes as $inscricao) {
            DB::table('responsaveis')
                ->where('id', $inscricao->responsavel_id)
                ->update([
                    'evento_id' => $inscricao->evento_id,
                    'ativo' => $inscricao->ativo,
                    'comunica' => $inscricao->comunica,
                    'device_comunica' => $inscricao->device_comunica,
                    'datahora_input' => $inscricao->data_inscricao,
                ]);
        }
        
        // Step 3: Drop inscricoes table
        Schema::dropIfExists('inscricoes');
    }
};
