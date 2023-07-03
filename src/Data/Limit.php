<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $idAccount
 * @property float $requestLimit
 * @property int $limitType
 * @property int $idRequest
 * @property ?int $idBatch
 * @property string $status
 * @property int $idServicesGroup
 * @property ?string $beneficiaryType
 * @property string $requestResultDate
 * @property string $requestDate
 * @property string $requestDeadline
 * @property string $requestMinApprovalDate
 * @property array $trustedDestination
 */
class Limit extends Data
{
    //
}
