<?php

namespace Idez\Caradhras\Database\Factories;

use App\Enums\Caradhras\PersonType;
use App\Enums\Pix\PixMethod;
use Idez\Caradhras\Data\PixDecodedStaticQr;
use App\Services\PixService;
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
     *
     * @return array
     */
    public function definition(): array
    {
        $bankId = $this->faker->randomElement(config('constants.banks'))['id'];

        return [
            'idTx' => $this->faker->regexify('/[A-Z0-9]{32}/'),
            'idEndToEnd' => $this->faker->regexify('/[A-Z0-9]{32}/'),
            'codeType' => PixMethod::StaticQrCode->value,
            'ispb' => $this->faker->regexify('/\d{8}/'),
            'bankName' => bankNameFromId($bankId),
            'bankAccountNumber' => (string)$this->faker->randomNumber(6),
            'bankBranchNumber' => (string)$this->faker->randomNumber(4),
            'bankAccountType' => PixService::CR_BANK_ACCOUNT_CHECKING,
            'key' => $this->faker->email(),
            'beneficiaryType' => PersonType::Person->value,
            'nationalRegistration' => $this->faker->cpf(false),
            'payeeName' => $this->faker->name(),
            'city' => $this->faker->city(),
            'zipCode' => $this->faker->postcode(),
            'details' => $this->faker->text(15),
        ];
    }

    /**
     * @param  string|null  $name
     * @return PixDecodedStaticQrFactory
     */
    public function company(?string $name = null): PixDecodedStaticQrFactory
    {
        return $this->state([
            'beneficiaryType' => PersonType::Company->value,
            'nationalRegistration' => $this->faker->cnpj(false),
            'payeeName' => $name ?? $this->faker->company(),
        ]);
    }
}
