<?php

namespace Idez\Caradhras\Database\Factories;

use App\Enums\Caradhras\PersonType;
use App\Enums\Caradhras\Pix\DictKeyType;
use App\Models\Account;
use Idez\Caradhras\Data\DictKeyClaim;
use App\Models\Company;
use App\Models\Person;
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
     *
     * @return array
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
            'keyClaimant' => (object)[
                "ispb" => $this->faker->randomNumber(8, true),
                "idAccount" => $this->faker->numberBetween(1111, 9999),
                "beneficiaryType" => $keyClaimantBeneficiaryType->value,
                "nationalRegistration" => match ($keyClaimantBeneficiaryType) {
                    PersonType::Company => $this->faker->cnpj(false),
                    PersonType::Person => $this->faker->cpf(false),
                },
                "bankAccountNumber" => $this->faker->regexify('\d{7}'),
            ],
            'description' => $this->faker->text(20),
            'grantorDeadline' => now()->addWeek()->toIso8601String(),
            'claimDeadline' => now()->addWeeks(2)->toIso8601String(),
            'claimUUID' => $this->faker->uuid(),
        ];
    }

    public function keyClaimantAccount(Account $account): DictKeyClaimFactory
    {
        return $this->state([
            'keyClaimant' => (object)[
                "ispb" => $this->faker->randomNumber(8, true),
                "idAccount" => $account->cr_account_id,
                "beneficiaryType" => match ($account->holder_type) {
                    Company::class => PersonType::Company->value,
                    Person::class => PersonType::Person->value,
                },
                "nationalRegistration" => $account->document,
                "bankAccountNumber" => $this->faker->regexify('\d{7}'),
            ],
        ]);
    }
}
