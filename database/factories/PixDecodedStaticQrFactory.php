<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Data\PixDecodedStaticQr;
use Idez\Caradhras\Enums\BankAccount\BankAccountType;
use Idez\Caradhras\Enums\PersonType;
use Idez\Caradhras\Enums\Pix\PixTransferType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PixDecodedStaticQrFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PixDecodedStaticQr::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $bankName = $this->faker->randomElement([
            'Itaú Unibanco S.A.',
            'BCO DO BRASIL S.A.',
            'BCO BRADESCO S.A.',
            'BCO SANTANDER (BRASIL) S.A.',
            'NU PAGAMENTOS S.A.',
            'Banco Inter S.A.',
            'CAIXA ECONOMICA FEDERAL',
        ]);

        return [
            'idTx' => $this->faker->regexify('/[A-Z0-9]{32}/'),
            'idEndToEnd' => $this->faker->regexify('/[A-Z0-9]{32}/'),
            'codeType' => PixTransferType::StaticQRCode->value,
            'ispb' => $this->faker->regexify('/\d{8}/'),
            'bankName' => $bankName,
            'bankAccountNumber' => (string) $this->faker->randomNumber(6),
            'bankBranchNumber' => (string) $this->faker->randomNumber(4),
            'bankAccountType' => BankAccountType::Checking->value,
            'key' => $this->faker->email(),
            'beneficiaryType' => PersonType::Person->value,
            'nationalRegistration' => $this->faker->cpf(false),
            'payeeName' => $this->faker->name(),
            'city' => $this->faker->city(),
            'zipCode' => $this->faker->postcode(),
            'details' => $this->faker->text(15),
        ];
    }

    public function company(?string $name = null): PixDecodedStaticQrFactory
    {
        return $this->state([
            'beneficiaryType' => PersonType::Company->value,
            'nationalRegistration' => $this->faker->cnpj(false),
            'payeeName' => $name ?? $this->faker->company(),
        ]);
    }
}
