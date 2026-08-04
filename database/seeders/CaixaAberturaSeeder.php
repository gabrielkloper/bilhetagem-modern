<?php

namespace Database\Seeders;

use App\Models\CaixaAbertura;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Database\Seeder;

class CaixaAberturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Caixa Aberturas...');

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
            $this->command->info("Creating cash openings for evento: {$evento->titulo}");

            CaixaAbertura::where('evento_id', $evento->id)->delete();

            for ($i = 0; $i < 3; $i++) {
                $dataAbertura = now()->subDays(rand(1, 30));
                $user = $users->random();

                $caixaAbertura = CaixaAbertura::create([
                    'evento_id' => $evento->id,
                    'user_id' => $user->id,
                    'data_abertura' => $dataAbertura->format('Y-m-d'),
                    'hora_abertura' => $dataAbertura->format('H:i:s'),
                    'datahora_abertura' => $dataAbertura,
                    'valor_inicial' => rand(100, 500),
                    'status' => rand(0, 1) ? 'fechado' : 'aberto',
                    'observacoes_abertura' => 'Abertura de caixa - Seeder',
                    'ativo' => true,
                ]);

                if ($caixaAbertura->status === 'fechado') {
                    $dataFechamento = $dataAbertura->copy()->addHours(rand(8, 12));
                    $caixaAbertura->update([
                        'valor_final' => $caixaAbertura->valor_inicial + rand(100, 1000),
                        'datahora_fechamento' => $dataFechamento,
                        'user_fechamento_id' => $users->random()->id,
                        'observacoes_fechamento' => 'Fechamento de caixa - Seeder',
                    ]);
                }

                $this->command->line("  Created cash opening: {$caixaAbertura->data_abertura} - {$caixaAbertura->status}");
            }
        }

        $totalAberturas = CaixaAbertura::count();
        $this->command->info("Successfully created {$totalAberturas} cash openings!");
    }
}
