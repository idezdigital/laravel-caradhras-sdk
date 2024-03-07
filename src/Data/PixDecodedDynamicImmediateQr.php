<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PixDecodedDynamicQr
 *
 * @property string $idEndToEnd
 * @property int $codeType
 * @property PixDecodedDynamicQrPayee $payee
 * @property ?PixDecodedDynamicQrPayer $payer
 * @property string $city
 * @property string $zipCode
 * @property float $amount
 * @property bool $allowChange
 * @property bool $allowPayerChange
 * @property string $dateExpiration
 * @property bool $allowAcceptance
 * @property string $dueDate
 * @property string $idTx
 * @property string $dateCreated
 * @property string $datePresentation
 * @property int $review
 * @property array $details
 */
class PixDecodedDynamicImmediateQr extends Data
{
    use HasFactory;
}
