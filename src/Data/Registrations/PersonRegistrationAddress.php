<?php

namespace Idez\Caradhras\Data\Registrations;

use Idez\Caradhras\Data\Data;
use Idez\Caradhras\Database\Factories\Registrations\PersonRegistrationAddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $idAddressType
 * @property string $zipCode
 * @property string $street
 * @property int $number
 * @property string $complement
 * @property string|null $referencePoint
 * @property string $neighborhood
 * @property string $city
 * @property string $federativeUnit
 * @property string $country
 */
class PersonRegistrationAddress extends Data
{
    use HasFactory;

    protected static function newFactory()
    {
        return PersonRegistrationAddressFactory::new();
    }
}
