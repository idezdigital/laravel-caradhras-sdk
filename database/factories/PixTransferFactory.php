<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\PixTransfer;
use Illuminate\Database\Eloquent\Factories\Factory;

class PixTransferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PixTransfer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'idEndToEnd' => 'E0874481' . $this->faker->regexify('/[A-Z0-9]{24}/'),
            'transactionDate' => now()->toDateTimeString(),
            'idAdjustment' => $this->faker->numberBetween(1111, 9999),
            'transactionCode' => $this->faker->uuid(),
            'transactionStatus' => PixTransfer::STATUS_PENDING,
            'idTx' => null,
        ];
    }
}
