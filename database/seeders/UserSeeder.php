<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin principal
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@bilhetagem.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'ativo',
            'evento_id' => null, // Admin tem acesso a todos os eventos
            'email_verified_at' => now(),
        ]);

        // Operador exemplo
        User::create([
            'name' => 'João Operador',
            'email' => 'operador@bilhetagem.com',
            'password' => Hash::make('operador123'),
            'role' => 'operador',
            'status' => 'ativo',
            'evento_id' => null,
            'email_verified_at' => now(),
        ]);

        // Caixa exemplo
        User::create([
            'name' => 'Maria Caixa',
            'email' => 'caixa@bilhetagem.com',
            'password' => Hash::make('caixa123'),
            'role' => 'caixa',
            'status' => 'ativo',
            'evento_id' => null,
            'email_verified_at' => now(),
        ]);

        // Supervisor exemplo
        User::create([
            'name' => 'Carlos Supervisor',
            'email' => 'supervisor@bilhetagem.com',
            'password' => Hash::make('supervisor123'),
            'role' => 'supervisor',
            'status' => 'ativo',
            'evento_id' => null,
            'email_verified_at' => now(),
        ]);

        // Usuário inativo para teste
        User::create([
            'name' => 'Usuário Inativo',
            'email' => 'inativo@bilhetagem.com',
            'password' => Hash::make('inativo123'),
            'role' => 'operador',
            'status' => 'inativo',
            'evento_id' => null,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Usuários criados com sucesso!');
        $this->command->info('Admin: admin@bilhetagem.com / admin123');
        $this->command->info('Operador: operador@bilhetagem.com / operador123');
        $this->command->info('Caixa: caixa@bilhetagem.com / caixa123');
        $this->command->info('Supervisor: supervisor@bilhetagem.com / supervisor123');
    }
}
