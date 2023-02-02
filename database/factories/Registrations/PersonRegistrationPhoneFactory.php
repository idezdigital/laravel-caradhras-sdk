<?php

namespace Idez\Caradhras\Database\Factories\Registrations;

use Idez\Caradhras\Data\Registrations\PersonRegistrationPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonRegistrationPhoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonRegistrationPhone::class;

    public function definition()
    {
        return [
            'id' => $this->faker->numberBetween(1111, 9999),
            'idPhoneType' => $this->faker->randomElement([1, 2, 3, 4, 5, 13, 17, 18]),
            'areaCode' => $this->faker->regexify('\d{3}'),
            'number' => $this->faker->regexify('\d{8,9}'),
        ];
    }
}
