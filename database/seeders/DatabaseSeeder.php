<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core entities first
            UserSeeder::class, // Users and events must exist first
            VinculoSeeder::class, // Vinculos before vinculados
            ResponsavelSeeder::class, // Responsaveis before vinculados 
            VinculadoSeeder::class, // Vinculados need responsaveis and vinculos
            
            // Event-related entities (require events to exist)
            PerfilAcessoSeeder::class, // Access profiles for events
            PacotesSeeder::class, // Packages for events
            PrevendasSeeder::class, // Prevendas need responsaveis and events
        ]);

        // Create default admin user if it doesn't exist
        if (!User::where('email', 'admin@bilhetagem.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@bilhetagem.com',
                'role' => 'admin',
                'status' => 'ativo',
            ]);
        }
    }
}
