<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pacote;
use App\Models\Evento;
use Illuminate\Support\Facades\DB;

class PacotesMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get connection to original database
        $originalDb = 'bdbilhetagem';
        
        // Get all active packages from original database
        $originalPackages = DB::connection('mysql')->select("
            SELECT * FROM {$originalDb}.tbpacotes 
            WHERE ativo = 1 
            ORDER BY id_evento, id_pacote
        ");

        echo "Found " . count($originalPackages) . " active packages in original database\n";

        // Get mapping of original evento IDs to modern evento IDs
        $eventoMapping = $this->getEventoMapping();
        
        $migratedCount = 0;
        $skippedCount = 0;

        foreach ($originalPackages as $originalPackage) {
            // Check if we have a mapping for this event
            if (!isset($eventoMapping[$originalPackage->id_evento])) {
                echo "Skipping package {$originalPackage->id_pacote} - no event mapping for evento_id {$originalPackage->id_evento}\n";
                $skippedCount++;
                continue;
            }

            $modernEventoId = $eventoMapping[$originalPackage->id_evento];

            // Check if package already exists
            $existingPackage = Pacote::where('evento_id', $modernEventoId)
                ->where('descricao', $originalPackage->descricao)
                ->where('valor', $originalPackage->valor)
                ->first();

            if ($existingPackage) {
                echo "Package '{$originalPackage->descricao}' already exists for evento_id {$modernEventoId}\n";
                continue;
            }

            // Create the package in modern system
            Pacote::create([
                'descricao' => $originalPackage->descricao,
                'rotulo_cliente' => $originalPackage->rotulo_cliente,
                'evento_id' => $modernEventoId,
                'ativo' => (bool) $originalPackage->ativo,
                'valor' => $originalPackage->valor,
                'duracao' => $originalPackage->duracao,
                'tolerancia' => $originalPackage->tolerancia,
                'valor_minuto_adicional' => $originalPackage->min_adicional
            ]);

            echo "Migrated package: '{$originalPackage->descricao}' for evento_id {$modernEventoId}\n";
            $migratedCount++;
        }

        echo "\nMigration completed!\n";
        echo "Migrated: {$migratedCount} packages\n";
        echo "Skipped: {$skippedCount} packages\n";
    }

    /**
     * Get mapping between original evento IDs and modern evento IDs
     */
    private function getEventoMapping(): array
    {
        // For now, we'll map based on what we know:
        // Original evento_id=1 maps to modern evento_id=1
        // You can extend this mapping as needed
        return [
            1 => 1, // Original event 1 -> Modern event 1 ("Porque Xpto")
            // Add more mappings as needed:
            // 2 => 2,
            // 3 => 3,
            // etc.
        ];
    }
}