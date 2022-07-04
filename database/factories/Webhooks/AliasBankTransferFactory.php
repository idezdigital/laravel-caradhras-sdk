<?php

namespace Database\Factories\Caradhras\Webhooks;

use Idez\Caradhras\Data\Webhooks\AliasBankTransfer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class AliasBankTransferFactory extends Factory
{
    protected $model = AliasBankTransfer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idAccount' => rand(1000, 9999),
            'amount' => $this->faker->randomFloat(2, 10, 100),
            'transactionDate' => today()->addDay(),
            'transactionCode' => $this->faker->uuid(),
            'idAdjustment' => rand(1000, 9999),
        ];
    }
}
