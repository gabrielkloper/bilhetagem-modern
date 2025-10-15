<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evento;
use App\Models\PerfilAcesso;

class PerfilAcessoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventos = Evento::where('status', 'ativo')->get();
        
        foreach ($eventos as $evento) {
            // Skip if already has profiles
            if (PerfilAcesso::where('evento_id', $evento->id)->exists()) {
                continue;
            }
            
            // Create common access profiles for each event
            $perfis = [
                [
                    'titulo' => 'Menor de 3 anos',
                    'descricao' => 'Gratuito para crianças menores de 3 anos',
                    'tipo' => 'idade',
                    'idade_minima' => 0,
                    'idade_maxima' => 2,
                    'preco_base' => 0.00,
                    'padrao_evento' => false
                ],
                [
                    'titulo' => 'Criança (3-12 anos)',
                    'descricao' => 'Perfil padrão para crianças de 3 a 12 anos',
                    'tipo' => 'idade',
                    'idade_minima' => 3,
                    'idade_maxima' => 12,
                    'preco_base' => 15.00,
                    'padrao_evento' => true
                ],
                [
                    'titulo' => 'Adolescente (13-17 anos)',
                    'descricao' => 'Perfil para adolescentes de 13 a 17 anos',
                    'tipo' => 'idade',
                    'idade_minima' => 13,
                    'idade_maxima' => 17,
                    'preco_base' => 20.00,
                    'padrao_evento' => false
                ],
                [
                    'titulo' => 'Menor de 1,20m',
                    'descricao' => 'Gratuito para crianças menores de 1,20m',
                    'tipo' => 'altura',
                    'altura_minima' => 0.00,
                    'altura_maxima' => 1.19,
                    'preco_base' => 0.00,
                    'padrao_evento' => false
                ],
                [
                    'titulo' => 'PCD - Pessoa com Deficiência',
                    'descricao' => 'Perfil especial para pessoas com deficiência',
                    'tipo' => 'especial',
                    'preco_base' => 0.00,
                    'padrao_evento' => false
                ],
                [
                    'titulo' => 'Acompanhante/Responsável',
                    'descricao' => 'Perfil para responsáveis que acompanham',
                    'tipo' => 'especial',
                    'preco_base' => 10.00,
                    'padrao_evento' => false
                ]
            ];
            
            foreach ($perfis as $perfilData) {
                PerfilAcesso::create(array_merge($perfilData, [
                    'evento_id' => $evento->id,
                    'ativo' => true
                ]));
            }
        }
    }
}
