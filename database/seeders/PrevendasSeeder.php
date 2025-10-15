<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prevenda;
use App\Models\Responsavel;
use App\Models\Evento;
use Carbon\Carbon;

class PrevendasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Prevendas...');

        // Get existing responsaveis and eventos
        $responsaveis = Responsavel::all();
        $eventos = Evento::where('ativo', true)->get();

        if ($responsaveis->isEmpty()) {
            $this->command->warn('No responsaveis found. Please seed responsaveis first.');
            return;
        }

        if ($eventos->isEmpty()) {
            $this->command->warn('No active eventos found. Please seed eventos first.');
            return;
        }

        // Clear existing prevendas (use delete instead of truncate due to foreign key constraints)
        Prevenda::query()->delete();

        $statusOptions = ['pendente', 'confirmado', 'cancelado', 'utilizado', 'expirado', 'finalizado'];
        $paymentTypes = ['dinheiro', 'cartao_debito', 'cartao_credito', 'pix', 'gratuito'];
        $origins = ['site', 'app', 'balcao', 'telefone'];

        $createdCount = 0;

        foreach ($eventos as $evento) {
            $this->command->info("Creating prevendas for evento: {$evento->titulo}");
            
            // Create 3-5 prevendas per evento
            $prevendasCount = rand(3, 5);
            
            for ($i = 0; $i < $prevendasCount; $i++) {
                // Pick random responsavel
                $responsavel = $responsaveis->random();
                
                // Generate realistic dates
                $eventoStart = Carbon::parse($evento->data_inicio);
                $solicita = $eventoStart->copy()->subDays(rand(1, 15))->setTime(rand(8, 20), rand(0, 59));
                
                $status = fake()->randomElement($statusOptions);
                $paymentType = fake()->randomElement($paymentTypes);
                $origin = fake()->randomElement($origins);
                
                // Set realistic payment values
                $baseValue = (float) $evento->preco_padrao;
                $valorPagamento = $baseValue + rand(5, 25); // Add 5-25 to base price
                
                $prevendaData = [
                    'responsavel_id' => $responsavel->id,
                    'evento_id' => $evento->id,
                    'data_acesso' => $evento->data_inicio,
                    'status' => $status,
                    'datahora_solicita' => $solicita,
                    'tipo_pagamento' => $paymentType,
                    'valor_pagamento' => $valorPagamento,
                    'origem' => $origin,
                ];

                // Add additional dates based on status
                if (in_array($status, ['confirmado', 'utilizado'])) {
                    $prevendaData['datahora_efetiva'] = $solicita->copy()->addMinutes(rand(5, 120));
                    $prevendaData['datahora_pagamento'] = $solicita->copy()->addMinutes(rand(1, 60));
                    
                    if ($status === 'utilizado') {
                        $prevendaData['datahora_efetiva_saida'] = $eventoStart->copy()->addHours(rand(1, 6));
                    }
                }

                // Add reservation datetime for confirmed/utilized
                if (in_array($status, ['confirmado', 'utilizado', 'pendente'])) {
                    $prevendaData['datahora_reserva'] = $solicita;
                }

                Prevenda::create($prevendaData);
                
                $this->command->line("  Created prevenda: {$responsavel->nome} - R$ {$valorPagamento} ({$status})");
                $createdCount++;
            }
        }

        $this->command->info("Successfully created {$createdCount} prevendas!");
    }
}