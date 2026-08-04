<?php

namespace Database\Seeders;

use App\Models\CaixaAbertura;
use App\Models\CaixaMovimento;
use App\Models\Evento;
use App\Models\TipoDespesa;
use App\Models\User;
use Illuminate\Database\Seeder;

class CaixaMovimentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Caixa Movimentos...');

        $eventos = Evento::where('ativo', true)->get();

        if ($eventos->isEmpty()) {
            $this->command->warn('No active eventos found. Please seed eventos first.');

            return;
        }

        $users = User::where('status', 'ativo')->get();

        if ($users->isEmpty()) {
            $this->command->warn('No active users found. Please seed users first.');

            return;
        }

        foreach ($eventos as $evento) {
            $this->command->info("Creating cash movements for evento: {$evento->titulo}");

            CaixaMovimento::where('evento_id', $evento->id)->delete();

            $caixaAberturas = CaixaAbertura::where('evento_id', $evento->id)->get();
            $tiposDespesas = TipoDespesa::where('evento_id', $evento->id)->get();

            if ($caixaAberturas->isEmpty()) {
                $this->command->warn("  No cash openings found for evento {$evento->titulo}. Skipping movements.");

                continue;
            }

            foreach ($caixaAberturas as $caixaAbertura) {
                for ($i = 0; $i < rand(5, 15); $i++) {
                    $tipoItem = ['entrada', 'saida', 'ajuste'][rand(0, 2)];
                    $tipoDespesa = $tiposDespesas->isNotEmpty() && $tipoItem === 'saida' ? $tiposDespesas->random() : null;

                    CaixaMovimento::create([
                        'evento_id' => $evento->id,
                        'user_id' => $users->random()->id,
                        'tipo_despesa_id' => $tipoDespesa?->id,
                        'data_caixa' => $caixaAbertura->data_abertura,
                        'tipo_item' => $tipoItem,
                        'descricao' => match ($tipoItem) {
                            'entrada' => 'Entrada de caixa - '.['Venda', 'Pagamento', 'Recebimento'][rand(0, 2)],
                            'saida' => 'Saída de caixa - '.($tipoDespesa?->nome ?? 'Despesa geral'),
                            'ajuste' => 'Ajuste de caixa - '.['Correção', 'Acerto', 'Diferença'][rand(0, 2)],
                        },
                        'valor' => rand(10, 500),
                        'ativo' => true,
                        'caixa_abertura_id' => $caixaAbertura->id,
                    ]);
                }

                $this->command->line("  Created movements for cash opening: {$caixaAbertura->data_abertura}");
            }
        }

        $totalMovimentos = CaixaMovimento::count();
        $this->command->info("Successfully created {$totalMovimentos} cash movements!");
    }
}
