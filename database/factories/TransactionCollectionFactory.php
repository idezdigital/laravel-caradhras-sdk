<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\Transaction;
use Idez\Caradhras\Data\TransactionCollection;
use Idez\Caradhras\Data\TransactionLegacy;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionCollectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionCollection::class;

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
            $transaction = Transaction::factory()->{$state}()->make();
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

        $transactions[] = Transaction::factory()->{$state}()->make([
            'amountTotalTransaction' => abs($totalAmount),
        ]);

        return [
            'previousPage' => 0,
            'currentPage' => 0,
            'nextPage' => 1,
            'last' => false,
            'totalPages' => 1,
            'totalItems' => count($transactions),
            'maxItemsPerPage' => 50,
            'totalItemsPage' => count($transactions),
            'items' => $transactions,
        ];
    }
}
