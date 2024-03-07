<?php

namespace Idez\Caradhras\Database\Factories;

use App\Models\Account;
use App\Models\PixTransfer;
use App\Services\PixService;
use Idez\Caradhras\Data\PixTransferDetail;
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
            'transactionStatus' => $this->faker->randomElement(\App\Models\Caradhras\PixTransfer::STATUS),
            'transferType' => $this->faker->randomElement(\App\Models\Caradhras\PixTransfer::TYPES),
            'errorType' => null,
            'debitParty' => (object) [
                'ispb' => $this->faker->randomNumber(8, true),
                'bankName' => $this->faker->company(),
                'nationalRegistration' => $this->faker->cpf(),
                'name' => $this->faker->name(),
                'bankBranchNumber' => $this->faker->regexify('\d{4}'),
                'bankAccountType' => $this->faker->randomElement(PixService::CR_BANK_ACCOUNT_TYPES),
                'bankAccountNumber' => $this->faker->regexify('\d{4}'),
            ],
            'creditParty' => (object) [
                'ispb' => $this->faker->randomNumber(8, true),
                'bankName' => $this->faker->company(),
                'nationalRegistration' => $this->faker->cpf(),
                'name' => $this->faker->name(),
                'bankBranchNumber' => $this->faker->regexify('\d{4}'),
                'bankAccountType' => $this->faker->randomElement(PixService::CR_BANK_ACCOUNT_TYPES),
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

    public function creditAccount(Account $account): PixTransferDetailFactory
    {
        return $this->state(fn () => [
            'idAccount' => $account->cr_account_id,
            'debitParty' => (object) [
                'ispb' => $this->faker->randomNumber(8, true),
                'bankName' => $this->faker->company(),
                'nationalRegistration' => $account->document,
                'name' => $account->name,
                'bankBranchNumber' => $this->faker->regexify('\d{4}'),
                'bankAccountType' => $this->faker->randomElement(PixService::CR_BANK_ACCOUNT_TYPES),
                'bankAccountNumber' => $this->faker->regexify('\d{4}'),
            ],
        ]);
    }

    public function forLocalPixTransfer(PixTransfer $pixTransfer)
    {
    }
}
