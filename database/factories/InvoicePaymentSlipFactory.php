<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Clients\CaradhrasPaymentSlipClient;
use Idez\Caradhras\Data\InvoicePaymentSlip;
use Idez\Caradhras\Data\RechargePaymentSlipPayer;
use Idez\Caradhras\Enums\PaymentSlip\PaymentSlipInvoiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoicePaymentSlipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoicePaymentSlip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /** @var Account $account */
        $account = Account::factory()->create();
        $documentType = $account->holder_type === Person::class ? 'F' : 'J';

        $idBankNumber = $this->faker->regexify('\d{10}');

        return [
            'idAccount' => $this->faker->numberBetween(100, 9999),
            'covenantNumber' => $this->faker->regexify('\d{4}'),
            'issuerBankNumber' => $idBankNumber,
            'idBankNumber' => $idBankNumber,
            'uniqueId' => $this->faker->regexify('\d{25}'),
            'dateDocument' => today()->toDateString(),
            'paymentslip' => (object) [
                'type' => $this->faker->randomElement(PaymentSlipInvoiceType::cases()),
                'dueDate' => $this->faker->date(),
                'amount' => $this->faker->randomFloat(2, 100, 1000),
            ],
            'payer' => new RechargePaymentSlipPayer([
                'documentType' => $documentType,
                'documentNumber' => $account->document,
                'name' => $account->name,
            ]),
            'beneficiary' => [
                'documentType' => $documentType,
                'documentNumber' => $account->document,
                'name' => $account->name,
            ],
            'coBeneficiary' => [
                'documentType' => 'J',
                'documentNumber' => $this->faker->cnpj(false),
                'name' => $this->faker->company(),
            ],
            'others' => (object) [
                'deadlineAutomaticCancellation' => 120,
            ],
            'bankBranchNumber' => 1111,
            'bankNumber' => CaradhrasPaymentSlipClient::DEFAULT_BANK_NUMBER,
            'instructions' => 'Não receber após vencimento',
            'acceptance' => 'N',
            'status' => BankSlip::CR_STATUS_CODE_REGISTERED,
            'barCode' => $this->faker->regexify('\d{44}'),
            'barCodeNumber' => $this->faker->regexify('\d{47}'),
            'type' => BankSlip::CR_TYPES_INVOICE,
            'fine' => [],
            'interest' => [],
            'discount' => [],
        ];
    }

    public function payer(string $name, string $document): InvoicePaymentSlipFactory
    {
        return $this->state([
            'payer' => new RechargePaymentSlipPayer([
                'documentType' => match (strlen($document)) {
                    11 => 'F',
                    14 => 'J',
                },
                'documentNumber' => $document,
                'name' => $name,
            ]),
        ]);
    }
}
