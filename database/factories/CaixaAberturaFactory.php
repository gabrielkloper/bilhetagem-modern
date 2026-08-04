<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CaixaAbertura>
 */
class CaixaAberturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dataAbertura = fake()->dateTimeBetween('-1 month', 'now');

        return [
            'evento_id' => \App\Models\Evento::factory(),
            'user_id' => \App\Models\User::factory(),
            'data_abertura' => $dataAbertura->format('Y-m-d'),
            'hora_abertura' => $dataAbertura->format('H:i:s'),
            'datahora_abertura' => $dataAbertura,
            'valor_inicial' => fake()->randomFloat(2, 100, 1000),
            'status' => 'aberto',
            'observacoes_abertura' => fake()->optional()->sentence(),
            'ativo' => true,
        ];
    }
}
