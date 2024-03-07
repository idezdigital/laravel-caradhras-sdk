<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Enums\AliasBankProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class AliasBankAccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idAccount' => $this->faker->randomNumber(5),
            'bankBranchNumber' => $this->faker->randomNumber(4),
            'bankBranchDigit' => $this->faker->randomNumber(1),
            'bankAccountNumber' => $this->faker->randomNumber(10),
            'bankAccountType' => $this->faker->randomElement(['CC', 'PA']),
            'bankNumber' => AliasBankProvider::Dock->value,
            'bankAccountStatus' => $this->faker->randomElement(['ACTIVE', 'PENDING', 'CLOSED', 'BLOCKED']),
        ];
    }
}
