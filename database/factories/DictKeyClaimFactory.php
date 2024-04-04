<?php

namespace Idez\Caradhras\Database\Factories;

use Idez\Caradhras\Enums\PersonType;
use Idez\Caradhras\Enums\Pix\DictKeyType;
use Idez\Caradhras\Data\DictKeyClaim;
use Illuminate\Database\Eloquent\Factories\Factory;

class DictKeyClaimFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DictKeyClaim::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $keyClaimantBeneficiaryType = $this->faker->randomElement(PersonType::cases());

        return [
            'message' => 'The portability request was created.',
            'ispb' => $this->faker->randomNumber(8, true),
            'keyType' => DictKeyType::Email,
            'key' => $this->faker->email(),
            'keyStatus' => 'WAITING_RESOLUTION',
            'keyClaimant' => (object) [
                'ispb' => $this->faker->randomNumber(8, true),
                'idAccount' => $this->faker->numberBetween(1111, 9999),
                'beneficiaryType' => $keyClaimantBeneficiaryType->value,
                'nationalRegistration' => match ($keyClaimantBeneficiaryType) {
                    PersonType::Company => $this->faker->cnpj(false),
                    PersonType::Person => $this->faker->cpf(false),
                },
                'bankAccountNumber' => $this->faker->regexify('\d{7}'),
            ],
            'description' => $this->faker->text(20),
            'grantorDeadline' => now()->addWeek()->toIso8601String(),
            'claimDeadline' => now()->addWeeks(2)->toIso8601String(),
            'claimUUID' => $this->faker->uuid(),
        ];
    }
}
