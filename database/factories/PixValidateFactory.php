<?php

namespace Idez\Caradhras\Database\Factories;

use App\Enums\Caradhras\PersonType;
use App\Enums\Caradhras\Pix\DictKeyType;
use Idez\Caradhras\Data\PixValidate;
use Illuminate\Database\Eloquent\Factories\Factory;

class PixValidateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PixValidate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $bankId = $this->faker->randomElement(config('constants.banks'))['id'];

        return [
            'ispb' => $this->faker->regexify('/\d{8}/'),
            'bankName' => bankNameFromId($bankId),
            'bankAccountNumber' => (string)$this->faker->randomNumber(6),
            'bankBranchNumber' => (string)$this->faker->randomNumber(4),
            'bankAccountType' => 'CC',
            'dateAccountCreated' => $this->faker->iso8601(),
            'beneficiaryType' => 'F',
            'nationalRegistration' => $this->faker->cpf(false),
            'name' => $this->faker->name(),
            'tradeName' => '',
            'key' => $this->faker->uuid(),
            'keyType' => $this->faker->randomElement(DictKeyType::cases())->value,
            'dateKeyCreated' => $this->faker->iso8601(),
            'dateKeyOwnership' => $this->faker->iso8601(),
            'idEndToEnd' => $this->faker->regexify('/[A-Z0-9]{32}/'),
        ];
    }

    public function company(): PixValidateFactory
    {
        return $this->state([
            'beneficiaryType' => PersonType::Company->value,
            'nationalRegistration' => $this->faker->cnpj(false),
            'name' => $this->faker->company(),
            'tradeName' => '',
        ]);
    }

    public function type(DictKeyType $keyType)
    {
        return match ($keyType) {
            DictKeyType::Phone => $this->phoneNumber(),
            DictKeyType::Email => $this->email(),
            DictKeyType::Evp => $this->evp(),
            DictKeyType::Document => $this->nationalRegistration()
        };
    }

    public function phoneNumber(): PixValidateFactory
    {
        return $this->state([
            'key' => '+55' . $this->faker->regexify('/\d{9}/'),
            'keyType' => DictKeyType::Phone->value,
        ]);
    }

    public function nationalRegistration(): PixValidateFactory
    {
        // TODO: Add dynamic person or company types.
        $document = $this->faker->cpf(false);

        return $this->state([
            'nationalRegistration' => $document,
            'key' => $document,
            'keyType' => DictKeyType::Document->value,
        ]);
    }

    public function evp(): PixValidateFactory
    {
        return $this->state([
            'key' => $this->faker->uuid(),
            'keyType' => DictKeyType::Evp->value,
        ]);
    }

    public function email(): PixValidateFactory
    {
        return $this->state([
            'key' => $this->faker->email(),
            'keyType' => DictKeyType::Email->value,
        ]);
    }
}
