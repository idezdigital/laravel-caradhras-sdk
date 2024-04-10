<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\PixTransferDetail;
use Idez\Caradhras\Enums\BankAccount\BankAccountType;
use Idez\Caradhras\Enums\Pix\PixTransferStatus;
use Idez\Caradhras\Enums\Pix\PixTransferType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PixTransferDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PixTransferDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $amount = $this->faker->randomFloat(2, 10, 1000);

        return [
            'idAccount' => $this->faker->numberBetween(1000, 9999),
            'idEndToEnd' => 'E0874481'.$this->faker->regexify('/[A-Z0-9]{24}/'),
            'transactionDate' => now()->toDateTimeString(),
            'transactionType' => 'TRANSFER_CREDIT',
            'transactionStatus' => $this->faker->randomElement(PixTransferStatus::cases())->value,
            'transferType' => $this->faker->randomElement(PixTransferType::cases())->value,
            'errorType' => null,
            'debitParty' => (object) [
                'ispb' => $this->faker->randomNumber(8, true),
                'bankName' => $this->faker->company(),
                'nationalRegistration' => $this->faker->cpf(),
                'name' => $this->faker->name(),
                'bankBranchNumber' => $this->faker->regexify('\d{4}'),
                'bankAccountType' => $this->faker->randomElement(BankAccountType::cases())->value,
                'bankAccountNumber' => $this->faker->regexify('\d{4}'),
            ],
            'creditParty' => (object) [
                'ispb' => $this->faker->randomNumber(8, true),
                'bankName' => $this->faker->company(),
                'nationalRegistration' => $this->faker->cpf(),
                'name' => $this->faker->name(),
                'bankBranchNumber' => $this->faker->regexify('\d{4}'),
                'bankAccountType' => $this->faker->randomElement(BankAccountType::cases())->value,
                'bankAccountNumber' => $this->faker->regexify('\d{4}'),
                'key' => '03266716450',
            ],
            'amount' => $amount,
            'tariffAmount' => 0,
            'finalAmount' => $amount,
            'idAdjustment' => $this->faker->numberBetween(1111, 9999),
            'transactionCode' => $this->faker->uuid(),
            'idTx' => null,
            'payerAnswer' => $this->faker->sentence(3),
        ];
    }
}
