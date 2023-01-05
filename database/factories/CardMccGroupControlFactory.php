<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\CardMccGroupControl;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardMccGroupControlFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CardMccGroupControl::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->numberBetween(1000, 9999),
            'idCartao' => $this->faker->numberBetween(1000, 9999),
            'grupoMCC' => [
                "id" => $this->faker->numberBetween(1000, 9999),
                "descricao" => $this->faker->text(20),
                "descricaoExtrato" => $this->faker->text(20),
                "duracao" => $this->faker->numberBetween(1, 59),
                "percentualMin" => $this->faker->randomFloat(2, 1, 90),
                "percentualMax" => $this->faker->randomFloat(2, 1, 90),
                "intervaloMatch" => $this->faker->numberBetween(1, 9),
            ],
        ];
    }

    public function cardId(int $cardId): self
    {
        return $this->state([
            'idCartao' => $cardId,
        ]);
    }

    public function mccGroupId(int $mccGroupId): self
    {
        return $this->state([
            'grupoMCC' => (object) [
                "id" => $mccGroupId,
                "descricao" => $this->faker->text(20),
                "descricaoExtrato" => $this->faker->text(20),
                "duracao" => 7,
                "percentualMin" => 10.00,
                "percentualMax" => 10.00,
                "intervaloMatch" => 5,
            ],
        ]);
    }
}
