<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $idEndToEnd
 * @property string $transactionDate,
 * @property int $idAdjustment
 * @property string $transactionCode
 * @property string $transactionStatus,
 * @property string $idTx
 */
class PixTransfer extends Data
{
    use HasFactory;

    public const TYPE_MANUAL = 0;

    public const TYPE_KEY = 1;

    public const TYPE_STATIC_QRCODE = 2;

    public const TYPE_DYNAMIC_QRCODE = 3;

    public const TYPES = [
        self::TYPE_MANUAL,
        self::TYPE_KEY,
        self::TYPE_STATIC_QRCODE,
        self::TYPE_DYNAMIC_QRCODE,
    ];

    public const STATUS_EXECUTED = 'EXECUTED';

    public const STATUS_NOT_EXECUTED = 'NOT_EXECUTED';

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_REVERSAL_EXECUTED = 'REVERSAL_EXECUTED';

    public const STATUS = [
        self::STATUS_EXECUTED,
        self::STATUS_NOT_EXECUTED,
        self::STATUS_PENDING,
        self::STATUS_REVERSAL_EXECUTED,
    ];
}
