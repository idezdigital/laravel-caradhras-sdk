<?php

namespace Idez\Caradhras\Data;

use Idez\Caradhras\Enums\Pix\QrCodeTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PixDecodedDynamicDueDateQr
 *
 * @property string $status
 * @property string $idEndToEnd
 * @property QrCodeTypes $codeType
 * @property PixDecodedDynamicQrPayee $payee
 * @property PixDecodedDynamicQrPayer $payer
 * @property string $city
 * @property string $zipCode
 * @property string $address
 * @property string $state
 * @property object $amount
 * @property string $idTx
 * @property string $dueDate
 * @property string $dateExpiration
 * @property string $dateCreated
 * @property string $datePresentation
 * @property int $review
 * @property array $details
 */
class PixDecodedDynamicDueDateQr extends Data
{
    use HasFactory;
}
