<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoDespesa>
 */
class TipoDespesaFactory extends Factory
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
            'nome' => fake()->randomElement([
                'Material de Limpeza',
                'Manutenção',
                'Alimentação',
                'Transporte',
                'Materiais',
                'Serviços Terceirizados',
                'Energia',
                'Água',
                'Telefone',
                'Internet',
            ]),
            'descricao' => fake()->sentence(),
            'ativo' => true,
            'ordem' => fake()->numberBetween(1, 10),
        ];
    }
}
