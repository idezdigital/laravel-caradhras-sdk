<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\Individual;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Individual>
 */
class IndividualFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Individual::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomNumber(),
            'nome' => $this->faker->name(),
            'tipo' => $this->faker->word(),
            'cpf' => $this->faker->cpf(false),
            'cnpj' => $this->faker->cnpj(false),
            'dataNascimento' => $this->faker->date(),
            'sexo' => $this->faker->randomElement(['M', 'F']),
            'numeroIdentidade' => $this->faker->rg(false),
            'orgaoExpedidorIdentidade' => $this->faker->word(),
            'unidadeFederativaIdentidade' => $this->faker->stateAbbr(),
            'dataEmissaoIdentidade' => $this->faker->date(),
            'flagDeficienteVisual' => $this->faker->boolean(),
        ];
    }
}
