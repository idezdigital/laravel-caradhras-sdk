<?php

namespace Idez\Caradhras\Data\Registrations;

use Idez\Caradhras\Data\Data;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Idez\Caradhras\Database\Factories\Registrations\PersonRegistrationPhoneFactory;

/**
 * @property int $id
 * @property int $idPhoneType
 * @property string $areaCode
 * @property string $number
 */
class PersonRegistrationPhone extends Data
{
    use HasFactory;

    protected static function newFactory()
    {
        return PersonRegistrationPhoneFactory::new();
    }
}
