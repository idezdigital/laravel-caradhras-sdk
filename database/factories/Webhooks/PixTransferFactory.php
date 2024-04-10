<?php

namespace Database\Factories\Caradhras\Webhooks;

use Idez\Caradhras\Data\DynamicQrCodePayer;
use Idez\Caradhras\Data\Webhooks\PixTransfer;
use Idez\Caradhras\Enums\Pix\PixTransferStatus;
use Idez\Caradhras\Enums\Pix\PixTransferType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PixTransferFactory extends Factory
{
    protected $model = PixTransfer::class;

    public function definition()
    {
        return [
            'idAccount' => $this->faker->numberBetween(100, 9999),
            'idEndToEnd' => $this->faker->regexify('/[A-Z0-9]{32}'),
            'transactionCode' => $this->faker->uuid(),
            'transactionStatus' => PixTransferStatus::Pending->value,
            'transactionType' => PixTransferType::Key->value,
            'transferType' => PixTransferType::Key->value,
            'transactionDate' => now()->toDateTimeString(),
            'payer' => DynamicQrCodePayer::factory()->make(),
            'finalAmount' => $this->faker->randomFloat(2),
            'payerAnswer' => $this->faker->text(20),
        ];
    }
}
