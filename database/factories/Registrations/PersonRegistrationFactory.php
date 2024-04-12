<?php

namespace Idez\Caradhras\Database\Factories\Registrations;

use Idez\Caradhras\Data\Registrations\PersonRegistration;
use Idez\Caradhras\Data\Registrations\PersonRegistrationAddress;
use Idez\Caradhras\Data\Registrations\PersonRegistrationPhone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class PersonRegistrationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonRegistration::class;

    public function definition()
    {
        return [
            'id' => $this->faker->numberBetween(1111, 9999),
            'name' => $this->faker->name(),
            'preferredName' => $this->faker->name(),
            'motherName' => $this->faker->name('female'),
            'birthDate' => $this->faker->dateTimeInInterval('-30 years', '-18 years')->format('Y-m-d'),
            'document' => $this->faker->cpf(false),
            'fatherName' => $this->faker->name('male'),
            'gender' => $this->faker->randomElement(['F', 'M']),
            'idNumber' => (string) $this->faker->numberBetween(1111, 9999),
            'identityIssuingEntity' => 'SSP',
            'federativeUnit' => $this->faker->stateAbbr(),
            'issuingDateIdentity' => $this->faker->dateTimeInInterval('-10 years', '-5 years')->format('Y-m-d'),
            'idMaritalStatus' => $this->faker->numberBetween(1, 10),
            'idProfession' => (string) $this->faker->numberBetween(1, 86),
            'idNationality' => $this->faker->numberBetween(1111, 9999),
            'idOccupationType' => $this->faker->numberBetween(1111, 9999),
            'branchNumber' => $this->faker->numberBetween(1, 32767),
            'accountNumber' => (string) $this->faker->numberBetween(1111, 9999),
            'email' => $this->faker->email(),
            'companyName' => $this->faker->company(),
            'idBusinessSource' => $this->faker->numberBetween(1111, 9999),
            'idProduct' => $this->faker->numberBetween(1111, 9999),
            'idAccount' => $this->faker->numberBetween(1111, 9999),
            'dueDate' => 10,
            'printedName' => $this->faker->name(),
            'incomeValue' => $this->faker->numberBetween(1000, 100000),
            'address' => PersonRegistrationAddress::factory()->make(),
            'phone' => PersonRegistrationPhone::factory()->make(),
            'registrationId' => $this->faker->uuid(),
            'status' => $this->faker->randomElement(['WAITING_DOCUMENTS', 'WAITING_ANALYSIS', 'ACTIVE', 'DENIED']),
        ];
    }
}
