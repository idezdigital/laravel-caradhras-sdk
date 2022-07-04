<?php

namespace Idez\Caradhras\Database\Factories;

use App\Enums\Caradhras\Pix\QrCodeTypes;
use Idez\Caradhras\Data\PixDecodedDynamicImmediateQr;
use Illuminate\Database\Eloquent\Factories\Factory;

class PixDecodedDynamicImmediateQrFactory extends Factory
{
    protected $model = PixDecodedDynamicImmediateQr::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'idEndToEnd' => $this->faker->regexify('/[A-Z0-9]{32}/'),
            'codeType' => QrCodeTypes::DynamicImmediate->value,
            'payee' => (object) [
                'ispb' => $this->faker->regexify('/\d{8}/'),
                'bankName' => bankNameFromId($this->faker->randomElement(config('constants.banks'))['id']),
                'bankAccountNumber' => (string)$this->faker->randomNumber(6),
                'bankBranchNumber' => (string)$this->faker->randomNumber(4),
                'bankAccountType' => 'CC',
                'beneficiaryType' => 'F',
                'nationalRegistration' => $this->faker->cpf(false),
                'payeeName' => $this->faker->name,
                'key' => $this->faker->uuid,
            ],
            'payer' => (object) [
                'beneficiaryType' => 'F',
                'nationalRegistration' => $this->faker->cpf(false),
                'payerName' => $this->faker->name,
                'payerRequest' => 'Vou pagar o aluguel, eu juro',
            ],
            'city' => $this->faker->city,
            'zipCode' => str_replace('-', '', $this->faker->postcode()),
            'amount' => 78.4,
            'allowChange' => false,
            'allowPayerChange' => false,
            'idTx' => $this->faker->text(30),
            'dueDate' => today()->addWeek(),
            'dateExpiration' => today()->addWeeks(2),
            'dateCreated' => today(),
            'datePresentation' => now(),
            'review' => 2,
            'details' => (object)[
                0 => (object) [
                    'title' => $this->faker->text(30),
                    'content' => $this->faker->text(30),
                ],
            ],
        ];
    }
}
