<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Clients\CaradhrasPaymentSlipClient;
use Idez\Caradhras\Data\RechargePaymentSlip;
use Idez\Caradhras\Data\RechargePaymentSlipPayer;
use Idez\Caradhras\Enums\PaymentSlip\PaymentSlipStatus;
use Idez\Caradhras\Enums\PaymentSlip\PaymentSlipType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RechargePaymentSlipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RechargePaymentSlip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $documentType = $this->faker->randomElement(['F', 'J']);
        $idBankNumber = $this->faker->regexify('\d{10}');

        return [
            'idAccount' => $this->faker->numberBetween(100, 9999),
            'covenantNumber' => $this->faker->regexify('\d{4}'),
            'issuerBankNumber' => $idBankNumber,
            'idBankNumber' => $idBankNumber,
            'uniqueId' => $this->faker->regexify('\d{25}'),
            'dueDate' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'dateDocument' => today()->toDateString(),
            'payer' => new RechargePaymentSlipPayer([
                'documentType' => $documentType,
                'documentNumber' => $documentType === 'F' ? $this->faker->cpf(false) : $this->faker->cnpj(false),
                'name' => $this->faker->name,
            ]),
            'beneficiary' => [
                'documentType' => $documentType,
                'documentNumber' => $documentType === 'F' ? $this->faker->cpf(false) : $this->faker->cnpj(false),
                'name' => $this->faker->name,
            ],
            'coBeneficiary' => [
                'documentType' => 'J',
                'documentNumber' => $this->faker->cnpj(false),
                'name' => $this->faker->company(),
            ],
            'bankBranchNumber' => 1111,
            'bankNumber' => CaradhrasPaymentSlipClient::DEFAULT_BANK_NUMBER,
            'instructions' => 'Não receber após vencimento',
            'acceptance' => 'N',
            'status' => PaymentSlipStatus::Registered->value,
            'barCode' => $this->faker->regexify('\d{44}'),
            'barCodeNumber' => $this->faker->regexify('\d{47}'),
            'type' => PaymentSlipType::Recharge->value,
        ];
    }
}
