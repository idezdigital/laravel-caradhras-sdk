<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Enums\Pix\LocType;
use Idez\Caradhras\Data\QrCodeLoc;
use Illuminate\Database\Eloquent\Factories\Factory;

class QrCodeLocFactory extends Factory
{
    protected $model = QrCodeLoc::class;

    public function definition(): array
    {
        return [
            'url' => $this->faker->url(),
            'emv' => $this->faker->paragraph(1),
            'locType' => $this->faker->randomElement(LocType::cases())->value,
            'id' => $this->faker->uuid(),
            'idTx' => $this->faker->md5(),
            'loc' => $this->faker->uuid(),
        ];
    }
}
