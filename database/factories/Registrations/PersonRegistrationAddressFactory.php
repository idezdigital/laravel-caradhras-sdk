<?php

namespace Idez\Caradhras\Database\Factories\Registrations;

use Idez\Caradhras\Data\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonRegistrationAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Card::class;

    public function definition()
    {
        return [
            'id' => $this->faker->numberBetween(1111, 9999),
            'idAddressType' => $this->faker->numberBetween(1, 4),
            'zipCode' => $this->faker->randomNumber(8, true),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->numberBetween(1, 9999),
            'complement' => '-',
            'neighborhood' => 'neighborhood',
            'city' => $this->faker->city(),
            'federativeUnit' => $this->faker->state(),
            'country' => $this->faker->country(),
        ];
    }
}
