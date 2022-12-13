<?php

namespace Idez\Caradhras\Data\Registrations;

use Idez\Caradhras\Data\Data;
use Idez\Caradhras\Database\Factories\Registrations\PersonRegistrationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $preferredName
 * @property string $motherName
 * @property string $birthDate
 * @property string $document
 * @property string|null $fatherName
 * @property string $gender
 * @property string $idNumber
 * @property string $identityIssuingEntity
 * @property string $federativeUnit
 * @property string $issuingDateIdentity
 * @property int $idMaritalStatus
 * @property int $idProfession
 * @property int $idNationality
 * @property int $idOccupationType
 * @property string $branchNumber
 * @property string $accountNumber
 * @property string $email
 * @property string $companyName
 * @property int $idBusinessSource
 * @property int $idProduct
 * @property string $idAccount
 * @property int $dueDate
 * @property string $printedName
 * @property bool $isPep
 * @property string $isPepSince
 * @property float $incomeValue
 * @property PersonRegistrationAddress $address
 * @property PersonRegistrationPhone $phone
 * @property string $registrationId
 * @property string $status
 */
class PersonRegistration extends Data
{
    use HasFactory;

    protected static function newFactory()
    {
        return PersonRegistrationFactory::new();
    }

    public const STATUS_ACTIVE = 'ACTIVE';
}
