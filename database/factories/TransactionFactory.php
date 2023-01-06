<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Enums\Transaction\TransactionOperation;
use Idez\Caradhras\Data\Transaction;
use Idez\Caradhras\Data\TransactionLegacy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    private function getDateSequence()
    {
        for ($i = strtotime('-200 days'); $i < time(); $i += rand(600, 604800)) {
            yield $i;
        }
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dateSequence = $this->getDateSequence();
        $dateSequence->next();

        $placeName = substr($this->faker->company(), 0, 25);
        $cityName = substr($this->faker->city(), 0, 13);

        return [
            'idAdjustment' => $this->faker->randomNumber(5),
            'description' => TransactionOperation::PurchaseNational->getDescription(),
            'idAccount' => $this->faker->randomNumber(5),
            'idTransactionReversal' => 0,
            'creditFlag' => 0,
            'status' => 2,
            'idAdjustmentType' => 1088,
            'maskedCard' => substr_replace($this->faker->creditCardNumber(), '000000', 4, 6),
            'bearerName' => Str::upper(substr($this->faker->name(), 0, 21)),
            'transactionDate' => Carbon::createFromTimestamp($dateSequence->current())->toDateTimeString(),
            'amountBrl' => $this->faker->randomFloat(2, 10, 100),
            'amountUsd' => null,
            'usdExchangeRate' => null,
            'amountIof' => 0.0,
            'feeService' => 0.0,
            'amountTotalTransaction' => $this->faker->randomFloat(2, 10, 100),
            'dateUsdExchangeRate' => null,
            'sourceCurrencyCode' => 'BRL',
            'destinationCurrencyCode' => 'BRL',
            'authorizationCode' => null,
            'mccCode' => null,
            'groupMcc' => null,
            'groupDescriptionMcc' => null,
            'idEstablishment' => 0,
            'nameEstablishment' => Str::upper(str_pad($placeName, 25).$cityName."BR"),
            'tradeNameEstablishment' => $placeName,
            'placeEstablishment' => str_pad($cityName, 13)."/BR",
            'adjustmentExternalId' => '231167789-7964-515584893 822072-621446-558735',
            'transactionDetails' => null,
        ];
    }

    public function base1(): self
    {
        return $this->state([
            "status" => 3,
        ]);
    }

    public function base2(): self
    {
        return $this->state([
            "status" => 2,
        ]);
    }

    public function credit(): self
    {
        return $this->state([
            "creditFlag" => 1,
        ]);
    }

    public function deposit(): self
    {
        return $this->credit()->state([
            "description" => TransactionOperation::Deposit->getDescription(),
        ]);
    }

    public function payment(): self
    {
        return $this->state([
            "idTipoTransacao" => TransactionLegacy::PAYMENT,
            "description" => TransactionOperation::PaymentSent->getDescription(),
        ]);
    }

    public function purchaseInternational(): self
    {
        $usdAmt = $this->faker->randomFloat(2, 1, 5);
        $usdRate = $this->faker->randomFloat(2, 5, 6);

        return $this->state(fn (array $attributes) => [
            "description" => TransactionOperation::PurchaseInternational->getDescription(),
            "amountBrl" => $usdAmt * $usdRate,
            "amountUsd" => $usdAmt,
            "usdExchangeRate" => $usdRate,
            "amountIof" => $usdAmt * 0.38,
            "feeService" => 0.0,
            "amountTotalTransaction" => ($usdAmt + $usdAmt * 0.38) * $usdRate,
            "transactionDate" => Carbon::parse($this->faker->dateTimeThisMonth())->toDateTimeString(),
            "sourceCurrencyCode" => 'USD',
            "destinationCurrencyCode" => 'USD',
        ]);
    }

    public function purchaseNational(): self
    {
        return $this->state(fn (array $attributes) => [
            'description' => TransactionOperation::PurchaseNational->getDescription(),
        ]);
    }

    public function transferP2pSent(): self
    {
        return $this->state([
            'description' => TransactionOperation::TransferSent->getDescription(),
        ]);
    }

    public function transferP2pReceived(): self
    {
        return $this->state([
            'description' => TransactionOperation::TransferReceived->getDescription(),
        ]);
    }

    public function transferTedSent(): self
    {
        return $this->state([
            "description" => "Transf Bancaria Enviada",
            "idAdjustmentType" => 1086,
        ]);
    }

    public function withdrawal(): self
    {
        return $this->state([
            'description' => TransactionOperation::Withdrawal->getDescription(),
        ]);
    }
}
