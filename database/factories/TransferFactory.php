<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\Transfer;
use Idez\Caradhras\Enums\Transfers\P2PStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transfer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $externalId = $this->faker->randomNumber(3);
        return [
            "accountId" => $this->faker->randomNumber(5),
            "adjustmentId" => $this->faker->randomNumber(7),
            "amount" => $this->faker->randomFloat(2, 1, 999),
            "externalId" => $externalId,
            "reason" => $this->faker->text(),
            "status" => $this->faker->randomElement(P2PStatus::class),
            "transactionId" => $this->faker->uuid(),
            "transferType" => $this->faker->randomNumber(1),
            "transactionCode" => $this->faker->uuid(),
            "transactionDate" => "2023-02-03T11:38:09.500",
        ];
    }
}
