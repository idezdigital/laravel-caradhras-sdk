<?php

namespace Idez\Caradhras\Data;

/**
 * @property int $operationType
 * @property int $person
 * @property int $bonus
 * @property int $leftOver
 */
class TariffDetail extends Data
{
    public const OPERATION_TYPE_NOT_REGISTERED = 0;

    public const OPERATION_TYPE_P2P = 1;

    public const OPERATION_TYPE_PAYMENT = 2;

    public const OPERATION_TYPE_BANK_TRANSFER = 3;

    public const OPERATION_TYPE_BANK_SLIP_ISSUANCE = 4;

    public const OPERATION_TYPE_BANK_SLIP_SETTLEMENT = 7;

    public const OPERATION_TYPE_PHONE_RECHARGE = 5;

    public const OPERATION_TYPE_SPTRANS = 6;

    public const OPERATION_TYPES = [
        self::OPERATION_TYPE_NOT_REGISTERED,
        self::OPERATION_TYPE_P2P,
        self::OPERATION_TYPE_PAYMENT,
        self::OPERATION_TYPE_BANK_TRANSFER,
        self::OPERATION_TYPE_BANK_SLIP_ISSUANCE,
        self::OPERATION_TYPE_BANK_SLIP_SETTLEMENT,
        self::OPERATION_TYPE_PHONE_RECHARGE,
        self::OPERATION_TYPE_SPTRANS,
    ];
}
