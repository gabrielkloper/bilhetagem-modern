<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Responsavel;

class ResponsavelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responsaveis = [
            [
                'nome' => 'João Santos Silva',
                'cpf' => '12345678901',
                'email' => 'joao.santos@email.com',
                'telefone1' => '(11) 99999-1234',
                'telefone2' => '(11) 3333-1234',
                'nascimento' => '1985-03-15',
                'datahora_input' => now()->format('Y-m-d H:i:s'),
                'ativo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nome' => 'Maria Oliveira Costa',
                'cpf' => '98765432100',
                'email' => 'maria.costa@email.com',
                'telefone1' => '(11) 98888-5678',
                'telefone2' => null,
                'nascimento' => '1990-07-22',
                'datahora_input' => now()->format('Y-m-d H:i:s'),
                'ativo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nome' => 'Carlos Eduardo Mendes',
                'cpf' => '11122233344',
                'email' => 'carlos.mendes@email.com',
                'telefone1' => '(11) 97777-9012',
                'telefone2' => '(11) 2222-9012',
                'nascimento' => '1978-12-10',
                'datahora_input' => now()->format('Y-m-d H:i:s'),
                'ativo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nome' => 'Ana Paula Rodrigues',
                'cpf' => '55566677788',
                'email' => 'ana.rodrigues@email.com',
                'telefone1' => '(11) 96666-3456',
                'telefone2' => null,
                'nascimento' => '1995-05-08',
                'datahora_input' => now()->format('Y-m-d H:i:s'),
                'ativo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nome' => 'Roberto Lima Santos',
                'cpf' => '33344455566',
                'email' => 'roberto.lima@email.com',
                'telefone1' => '(11) 95555-7890',
                'telefone2' => '(11) 4444-7890',
                'nascimento' => '1982-09-25',
                'datahora_input' => now()->format('Y-m-d H:i:s'),
                'ativo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($responsaveis as $responsavel) {
            Responsavel::create($responsavel);
        }
    }
}