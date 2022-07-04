<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $idAdjustment
 * @property string $description
 * @property int $idAccount
 * @property bool $idTransactionReversal
 * @property bool $creditFlag
 * @property int $status
 * @property int $idAdjustmentType
 * @property string $maskedCard
 * @property string $bearerName
 * @property string $transactionDate
 * @property float $amountBrl
 * @property float|null $amountUsd
 * @property float|null $usdExchangeRate
 * @property float $amountIof
 * @property float $feeService
 * @property float $amountTotalTransaction
 * @property string $dateUsdExchangeRate
 * @property string $sourceCurrencyCode
 * @property string $destinationCurrencyCode
 * @property string|null $authorizationCode
 * @property int|null $mccCode
 * @property int|null $groupMcc
 * @property int|null $idPurchaseEvent
 * @property string|null $groupDescriptionMcc
 * @property int $idEstablishment
 * @property string|null $nameEstablishment
 * @property string|null $tradeNameEstablishment
 * @property string|null $placeEstablishment
 * @property string $adjustmentExternalId
 * @property string|null $transactionDetails
 */
class Transaction extends Data
{
    use HasFactory;

    public const STATUS_NORMAL = 0;
    public const STATUS_CANCELED = 1;
    public const STATUS_PROCESSED = 2;
    public const STATUS_PENDING = 3;
    public const STATUS_UNDONE = 4;
    public const STATUS_REVERSED = 5;
    public const STATUS_NON_PROCESSED_ADJUSTMENT = 9;
    public const STATUS_PENDING_AVAILABLE_GLOBAL = 15;
    public const STATUS_WRITE_OFF_BY_TIME_LIMIT = 20;
    public const STATUS_FRAUD_REFERRED = 21;
    public const STATUS_FRAUD_DENIED = 22;
    public const STATUS_WRITE_OFF_MANUALLY = 23;
    public const STATUS_DAILY_NUMBER_EXCEEDED = 24;

    public const STATUSES = [
        self::STATUS_NORMAL,
        self::STATUS_CANCELED,
        self::STATUS_PROCESSED,
        self::STATUS_PENDING,
        self::STATUS_UNDONE,
        self::STATUS_REVERSED,
        self::STATUS_NON_PROCESSED_ADJUSTMENT,
        self::STATUS_PENDING_AVAILABLE_GLOBAL,
        self::STATUS_WRITE_OFF_BY_TIME_LIMIT,
        self::STATUS_FRAUD_REFERRED,
        self::STATUS_FRAUD_DENIED,
        self::STATUS_WRITE_OFF_MANUALLY,
        self::STATUS_DAILY_NUMBER_EXCEEDED,
    ];
}
