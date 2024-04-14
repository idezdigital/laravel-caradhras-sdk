<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\CardDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CardDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $cardNumber = $this->faker->creditCardNumber('Visa');

        return [
            'numeroCartao' => $cardNumber,
            'dataValidade' => now()->addYears(5)->toIso8601String(),
            'cvv2' => $this->faker->regexify('/\d{3}/'),
            'nomePlastico' => $this->faker->name(),
            'idConta' => $this->faker->randomNumber(3),
            'idCartao' => $this->faker->randomNumber(3),
            'numeroAgencia' => 0,
            'numeroContaCorente' => '',
            'idStatusConta' => 0,
            'statusConta' => 'Normal',
            'idStatusCartao' => 1,
            'statusCartao' => 'Normal',
            'idMifare' => null,
            'matriculaMifare' => null,
            'idProduto' => $this->faker->randomDigitNotNull(),
            'flagVirtual' => 1,
        ];
    }
}
