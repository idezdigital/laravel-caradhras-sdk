<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\StaticPixQrCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaticPixQrCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StaticPixQrCode::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $emv = 'static-qr-code-fake-emv';

        return [
            'emv' => $emv,
            'text' => base64_encode($emv),
            'image' => 'fake-static-qr-code-image-content',
        ];
    }
}
