<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vinculo;

class VinculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vinculos = [
            'Filho(a)',
            'Cônjuge/Companheiro(a)',
            'Pai/Mãe',
            'Irmão/Irmã',
            'Avô/Avó',
            'Neto/Neta',
            'Primo/Prima',
            'Tio/Tia',
            'Sobrinho/Sobrinha',
            'Amigo(a)',
            'Outro'
        ];

        foreach ($vinculos as $descricao) {
            Vinculo::firstOrCreate([
                'descricao' => $descricao
            ], [
                'ativo' => true
            ]);
        }
    }
}