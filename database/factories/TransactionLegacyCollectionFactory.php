<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\TransactionLegacy;
use Idez\Caradhras\Data\TransactionLegacyCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionLegacyCollectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionLegacyCollection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $transactions = [];
        $totalAmount = 0;
        for ($i = 0; $i <= $this->faker->randomNumber(2); $i++) {
            $state = $this->faker->randomElement([
                'withdrawal',
                'purchaseNational',
                'purchaseInternational',
                'payment',
                'deposit',
                'transferTedSent',
                'transferP2pSent',
                'transferP2pReceived',
            ]);

            /** @var TransactionLegacy $transaction */
            $transaction = TransactionLegacy::factory()->{$state}()->make();
            $totalAmount += $transaction->valorBRL * ($transaction->flagCredito ? 1 : -1);
            $transactions[] = $transaction;
        }

        if ($totalAmount > 0) {
            $state = $this->faker->randomElement([
                'withdrawal',
                'purchaseNational',
                'payment',
                'transferTedSent',
                'transferP2pSent',
            ]);
        } elseif ($totalAmount < 0) {
            $state = $this->faker->randomElement([
                'deposit',
                'transferP2pReceived',
            ]);
        }

        $transactions[] = TransactionLegacy::factory()->{$state}()->make([
            'valorBRL' => abs($totalAmount),
        ]);

        return [
            'number' => 0,
            'size' => 1,
            'totalPages' => 1,
            'numberOfElements' => count($transactions),
            'totalElements' => count($transactions),
            'firstPage' => 0,
            'hasPreviousPage' => false,
            'hasContent' => true,
            'first' => true,
            'last' => true,
            'nextPage' => 1,
            'previousPage' => 0,
            'content' => $transactions,
        ];
    }
}
