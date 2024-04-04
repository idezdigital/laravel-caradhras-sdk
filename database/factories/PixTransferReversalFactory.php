<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\PixTransferReversal;
use Illuminate\Database\Eloquent\Factories\Factory;

class PixTransferReversalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PixTransferReversal::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'idEndToEndOriginal' => $this->faker->regexify('/[A-Z0-9]{32}/'),
            'transactionDate' => now()->toIso8601String(),
            'idAdjustment' => $this->faker->numberBetween(100, 9999),
            'transactionCode' => $this->faker->uuid(),
            'transactionStatus' => 'PENDING',
        ];
    }
}
