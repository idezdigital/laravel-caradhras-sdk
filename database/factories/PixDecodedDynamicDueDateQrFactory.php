<?php

namespace Idez\Caradhras\Database\Factories;

use App\Enums\Caradhras\Pix\QrCodeTypes;
use Idez\Caradhras\Data\PixDecodedDynamicDueDateQr;
use Illuminate\Database\Eloquent\Factories\Factory;

class PixDecodedDynamicDueDateQrFactory extends Factory
{
    protected $model = PixDecodedDynamicDueDateQr::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'status' => 'ACTIVE',
            'idEndToEnd' => $this->faker->regexify('/[A-Z0-9]{32}/'),
            'codeType' => QrCodeTypes::DynamicDueDate->value,
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
            'address' => $this->faker->address,
            'state' => $this->faker->stateAbbr(),
            'amount' => (object) [
                "original" => 100,
                "fees" => [
                    "discount" => 19.6,
                    "abatement" => 2,
                    "fine" => 0,
                    "interest" => 0,
                ],
                "final" => 78.4,
            ],
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
