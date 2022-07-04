<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\DynamicQrcode;
use Illuminate\Database\Eloquent\Factories\Factory;

class DynamicQrcodeFactory extends Factory
{
    protected $model = DynamicQrcode::class;

    public function definition(): array
    {
        return [
            'emv' => $this->faker->text(20),
            'text' => $this->faker->text(20),
            'image' => $this->faker->text(20),
            'payloadURL' => $this->faker->text(20),
            'idDocument' => $this->faker->text(20),
            'idTx' => $this->faker->text(20),
        ];
    }
}
