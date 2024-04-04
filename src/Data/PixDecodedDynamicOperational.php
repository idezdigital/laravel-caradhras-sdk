<?php

namespace Idez\Caradhras\Data;

use Idez\Caradhras\Enums\Pix\DynamicQrTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PixDecodedDynamicQr
 *
 * @property string $idEndToEnd
 * @property int $codeType
 * @property DynamicQrTypes $pixType
 * @property int $transferType
 * @property PixDecodedDynamicQrPayee $payee
 * @property ?PixDecodedDynamicQrPayer $payer
 * @property string $city
 * @property object $amount
 * @property string $dateExpiration
 * @property string $dateCreated
 * @property string $datePresentation
 * @property string $idTx
 * @property int $review
 * @property array $details
 * @property string $dueDate
 * @property bool $allowChange
 * @property bool $allowAcceptance
 */
class PixDecodedDynamicOperational extends Data
{
    use HasFactory;
}
