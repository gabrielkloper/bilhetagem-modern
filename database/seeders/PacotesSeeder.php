<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pacote;
use App\Models\Evento;

class PacotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Pacotes...');

        // Get all active eventos
        $eventos = Evento::where('ativo', true)->get();

        if ($eventos->isEmpty()) {
            $this->command->warn('No active eventos found. Please seed eventos first.');
            return;
        }

        foreach ($eventos as $evento) {
            $this->command->info("Creating packages for evento: {$evento->titulo}");

            // Clear existing packages for this evento
            Pacote::where('evento_id', $evento->id)->delete();

            // Create realistic packages based on the evento's base price
            $basePrice = (float) $evento->preco_padrao;
            
            $packages = [
                [
                    'descricao' => '1 Hora',
                    'rotulo_cliente' => '1 hora de diversão',
                    'evento_id' => $evento->id,
                    'ativo' => true,
                    'valor' => $basePrice + 5,
                    'duracao' => 60, // minutes
                    'tolerancia' => 10, // minutes
                    'valor_minuto_adicional' => 0.50
                ],
                [
                    'descricao' => '2 Horas',
                    'rotulo_cliente' => '2 horas de diversão',
                    'evento_id' => $evento->id,
                    'ativo' => true,
                    'valor' => $basePrice + 10,
                    'duracao' => 120, // minutes
                    'tolerancia' => 15, // minutes
                    'valor_minuto_adicional' => 0.40
                ],
                [
                    'descricao' => '3 Horas',
                    'rotulo_cliente' => '3 horas de diversão',
                    'evento_id' => $evento->id,
                    'ativo' => true,
                    'valor' => $basePrice + 15,
                    'duracao' => 180, // minutes
                    'tolerancia' => 20, // minutes
                    'valor_minuto_adicional' => 0.35
                ],
                [
                    'descricao' => 'Meio Período',
                    'rotulo_cliente' => '4 horas de diversão',
                    'evento_id' => $evento->id,
                    'ativo' => true,
                    'valor' => $basePrice + 20,
                    'duracao' => 240, // minutes
                    'tolerancia' => 30, // minutes
                    'valor_minuto_adicional' => 0.30
                ],
                [
                    'descricao' => 'Dia Todo',
                    'rotulo_cliente' => 'Acesso por todo o dia',
                    'evento_id' => $evento->id,
                    'ativo' => true,
                    'valor' => $basePrice + 30,
                    'duracao' => 480, // 8 hours in minutes
                    'tolerancia' => 60, // minutes
                    'valor_minuto_adicional' => 0.25
                ],
                [
                    'descricao' => 'Promocional 30min',
                    'rotulo_cliente' => '30 minutos promocional',
                    'evento_id' => $evento->id,
                    'ativo' => true,
                    'valor' => max(5, $basePrice - 3), // Minimum R$ 5
                    'duracao' => 30, // minutes
                    'tolerancia' => 5, // minutes
                    'valor_minuto_adicional' => 0.60
                ]
            ];

            foreach ($packages as $packageData) {
                Pacote::create($packageData);
                $this->command->line("  Created package: {$packageData['descricao']} - R$ {$packageData['valor']}");
            }
        }

        $totalPackages = Pacote::count();
        $this->command->info("Successfully created {$totalPackages} packages!");
    }
}