<?php

namespace Tests\Feature;

use App\Models\Entrada;
use App\Models\Evento;
use App\Models\Inscricao;
use App\Models\Pacote;
use App\Models\Responsavel;
use App\Models\Vinculado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntradaInscricaoValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nao_pode_criar_entrada_sem_inscricao_do_responsavel()
    {
        $evento = Evento::factory()->create(['status' => 'ativo']);
        $responsavel = Responsavel::factory()->create();
        $vinculado = Vinculado::factory()->create(['responsavel_id' => $responsavel->id]);
        $pacote = Pacote::factory()->create(['evento_id' => $evento->id]);

        // Tentar criar entrada SEM ter inscrição
        $response = $this->postJson(route('admin.entradas.store'), [
            'evento_id' => $evento->id,
            'vinculado_id' => $vinculado->id,
            'pacote_id' => $pacote->id,
            'payment_method' => 'dinheiro',
            'amount_paid' => 'R$ 50,00',
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'error' => 'O responsável não está inscrito neste evento ou a inscrição está inativa. Por favor, realize a inscrição primeiro.',
        ]);

        $this->assertDatabaseMissing('entradas', [
            'evento_id' => $evento->id,
            'vinculado_id' => $vinculado->id,
        ]);
    }

    /** @test */
    public function nao_pode_criar_entrada_com_inscricao_inativa()
    {
        $evento = Evento::factory()->create(['status' => 'ativo']);
        $responsavel = Responsavel::factory()->create();
        $vinculado = Vinculado::factory()->create(['responsavel_id' => $responsavel->id]);
        $pacote = Pacote::factory()->create(['evento_id' => $evento->id]);

        // Criar inscrição INATIVA
        Inscricao::create([
            'responsavel_id' => $responsavel->id,
            'evento_id' => $evento->id,
            'ativo' => false,
            'data_inscricao' => now(),
        ]);

        $response = $this->postJson(route('admin.entradas.store'), [
            'evento_id' => $evento->id,
            'vinculado_id' => $vinculado->id,
            'pacote_id' => $pacote->id,
            'payment_method' => 'dinheiro',
            'amount_paid' => 'R$ 50,00',
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'error' => 'O responsável não está inscrito neste evento ou a inscrição está inativa. Por favor, realize a inscrição primeiro.',
        ]);
    }

    /** @test */
    public function pode_criar_entrada_com_inscricao_ativa()
    {
        $evento = Evento::factory()->create(['status' => 'ativo', 'capacidade' => 100]);
        $responsavel = Responsavel::factory()->create();
        $vinculado = Vinculado::factory()->create(['responsavel_id' => $responsavel->id]);
        $pacote = Pacote::factory()->create(['evento_id' => $evento->id, 'valor' => 50.00]);

        // Criar inscrição ATIVA
        Inscricao::create([
            'responsavel_id' => $responsavel->id,
            'evento_id' => $evento->id,
            'ativo' => true,
            'data_inscricao' => now(),
        ]);

        $response = $this->postJson(route('admin.entradas.store'), [
            'evento_id' => $evento->id,
            'vinculado_id' => $vinculado->id,
            'pacote_id' => $pacote->id,
            'payment_method' => 'dinheiro',
            'amount_paid' => 'R$ 50,00',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('entradas', [
            'evento_id' => $evento->id,
            'vinculado_id' => $vinculado->id,
            'status' => 'ativo',
        ]);
    }
}
