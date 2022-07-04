<?php

namespace Database\Factories\Caradhras\Webhooks;

use Idez\Caradhras\Data\DynamicQrCodePayer;
use Idez\Caradhras\Data\Webhooks\PixTransfer;
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
            'transactionStatus' => \App\Models\Caradhras\PixTransfer::STATUS_PENDING,
            'transactionType' => \App\Models\Caradhras\PixTransfer::TYPE_KEY,
            'transferType' => \App\Models\Caradhras\PixTransfer::TYPE_KEY,
            'transactionDate' => now()->toDateTimeString(),
            'payer' => DynamicQrCodePayer::factory()->make(),
            'finalAmount' => $this->faker->randomFloat(2),
            'payerAnswer' => $this->faker->text(20),
        ];
    }
}
