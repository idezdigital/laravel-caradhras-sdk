<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\PixTransferReversal;
use App\Models\PixTransfer;
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
     *
     * @return array
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

    public function forPixTransfer(PixTransfer $pixTransfer): PixTransferReversalFactory
    {
        return $this->state([
            'idEndToEndOriginal' => $pixTransfer->e2e_id,
        ]);
    }
}
