<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CaixaMovimento>
 */
class CaixaMovimentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'evento_id' => \App\Models\Evento::factory(),
            'user_id' => \App\Models\User::factory(),
            'tipo_despesa_id' => fake()->boolean(30) ? \App\Models\TipoDespesa::factory() : null,
            'data_caixa' => fake()->dateTimeBetween('-1 month', 'now'),
            'tipo_item' => fake()->randomElement(['entrada', 'saida', 'ajuste']),
            'descricao' => fake()->sentence(),
            'valor' => fake()->randomFloat(2, 10, 500),
            'ativo' => true,
            'caixa_abertura_id' => fake()->boolean(50) ? \App\Models\CaixaAbertura::factory() : null,
        ];
    }
}
