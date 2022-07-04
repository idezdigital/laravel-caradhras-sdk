<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\DynamicQrCodePayer;
use Illuminate\Database\Eloquent\Factories\Factory;

class DynamicQrCodePayerFactory extends Factory
{
    protected $model = DynamicQrCodePayer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payerName' => $this->faker->name(),
            'beneficiaryType' => $this->faker->randomElement(['F', 'J']),
            'nationalRegistration' => $this->faker->cpf(),
            'payerQuestion' => $this->faker->text(20),
        ];
    }
}
