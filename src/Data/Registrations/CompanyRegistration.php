<?php

namespace Idez\Caradhras\Data\Registrations;

use Idez\Caradhras\Data\Data;

/**
 * @property int $idCompany
 * @property int $idAccount
 * @property string $nationalRegistration
 * @property string $legalName
 * @property string $legalNature
 * @property string $tradeName
 * @property string $dateEstablishment
 * @property string $establishmentFormat
 * @property string $stateRegistration
 * @property string $email
 * @property float $revenue
 * @property bool $partnerChanged
 * @property string $mainCnae
 * @property string[] $cnaes
 * @property object $mainPhone
 * @property object[] $phones
 * @property object $mainAddress
 * @property object[] $addresses
 * @property object $partners
 */
class CompanyRegistration extends Data
{
    public const STATUS_WAITING_DOCUMENTS = 'WAITING_DOCUMENTS';

    public const STATUS_WAITING_ANALYSIS = 'WAITING_ANALYSIS';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_ACTIVE = 'ACTIVE';
}
