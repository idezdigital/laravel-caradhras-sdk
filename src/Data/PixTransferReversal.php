<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PixTransferReversal
 * @package App\Models\Caradhras
 *
 * @property string $idEndToEndOriginal
 * @property string $transactionDate
 * @property int $idAdjustment
 * @property string $transactionCode
 * @property string $transactionStatus
 */
class PixTransferReversal extends Data
{
    use HasFactory;
}
