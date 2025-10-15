<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vinculo;

class VinculosSeeder extends Seeder
{
    public function run(): void
    {
        $vinculos = [
            ['descricao' => 'Criança', 'ativo' => true],
            ['descricao' => 'Adolescente', 'ativo' => true],
            ['descricao' => 'Adulto', 'ativo' => true],
            ['descricao' => 'Idoso', 'ativo' => true],
            ['descricao' => 'PCD', 'ativo' => true],
            ['descricao' => 'Cônjuge', 'ativo' => true],
            ['descricao' => 'Familiar', 'ativo' => true],
        ];

        foreach ($vinculos as $vinculo) {
            Vinculo::updateOrCreate(
                ['descricao' => $vinculo['descricao']],
                $vinculo
            );
        }
    }
}