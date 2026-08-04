<?php

namespace Database\Seeders;

use App\Models\Evento;
use App\Models\TipoDespesa;
use Illuminate\Database\Seeder;

class TipoDespesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Tipo Despesas...');

        $eventos = Evento::where('ativo', true)->get();

        if ($eventos->isEmpty()) {
            $this->command->warn('No active eventos found. Please seed eventos first.');

            return;
        }

        foreach ($eventos as $evento) {
            $this->command->info("Creating expense types for evento: {$evento->titulo}");

            TipoDespesa::where('evento_id', $evento->id)->delete();

            $tiposDespesas = [
                [
                    'evento_id' => $evento->id,
                    'nome' => 'Material de Limpeza',
                    'descricao' => 'Produtos e materiais de limpeza',
                    'ativo' => true,
                    'ordem' => 1,
                ],
                [
                    'evento_id' => $evento->id,
                    'nome' => 'Manutenção',
                    'descricao' => 'Serviços de manutenção e reparos',
                    'ativo' => true,
                    'ordem' => 2,
                ],
                [
                    'evento_id' => $evento->id,
                    'nome' => 'Alimentação',
                    'descricao' => 'Despesas com alimentação',
                    'ativo' => true,
                    'ordem' => 3,
                ],
                [
                    'evento_id' => $evento->id,
                    'nome' => 'Transporte',
                    'descricao' => 'Despesas com transporte',
                    'ativo' => true,
                    'ordem' => 4,
                ],
                [
                    'evento_id' => $evento->id,
                    'nome' => 'Materiais',
                    'descricao' => 'Materiais diversos',
                    'ativo' => true,
                    'ordem' => 5,
                ],
                [
                    'evento_id' => $evento->id,
                    'nome' => 'Serviços Terceirizados',
                    'descricao' => 'Serviços de terceiros',
                    'ativo' => true,
                    'ordem' => 6,
                ],
            ];

            foreach ($tiposDespesas as $tipoDespesa) {
                TipoDespesa::create($tipoDespesa);
                $this->command->line("  Created expense type: {$tipoDespesa['nome']}");
            }
        }

        $totalTipos = TipoDespesa::count();
        $this->command->info("Successfully created {$totalTipos} expense types!");
    }
}
