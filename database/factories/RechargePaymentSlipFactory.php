<?php

namespace Idez\Caradhras\Database\Factories;

use App\Models\Account;
use App\Models\BankSlip;
use Idez\Caradhras\Clients\CaradhrasPaymentSlipClient;
use Idez\Caradhras\Data\RechargePaymentSlip;
use Idez\Caradhras\Data\RechargePaymentSlipPayer;
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
        /** @var Account $account */
        $account = Account::factory()->create();
        $documentType = $account->holder_type === \App\Models\Person::class ? 'F' : 'J';

        $idBankNumber = $this->faker->regexify('\d{10}');

        return [
            'idAccount' => $account->cr_account_id,
            'covenantNumber' => $this->faker->regexify('\d{4}'),
            'issuerBankNumber' => $idBankNumber,
            'idBankNumber' => $idBankNumber,
            'uniqueId' => $this->faker->regexify('\d{25}'),
            'dueDate' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'dateDocument' => today()->toDateString(),
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
            'bankBranchNumber' => 1111,
            'bankNumber' => CaradhrasPaymentSlipClient::DEFAULT_BANK_NUMBER,
            'instructions' => 'Não receber após vencimento',
            'acceptance' => 'N',
            'status' => BankSlip::CR_STATUS_CODE_REGISTERED,
            'barCode' => $this->faker->regexify('\d{44}'),
            'barCodeNumber' => $this->faker->regexify('\d{47}'),
            'type' => BankSlip::CR_TYPES_RECHARGE,
        ];
    }

    public function fromAccount(Account $account)
    {
        $documentType = $account->holder_type === \App\Models\Person::class ? 'F' : 'J';

        return $this->state([
            'idAccount' => $account->cr_account_id,
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
        ]);
    }
}
