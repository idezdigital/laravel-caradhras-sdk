<?php

namespace Idez\Caradhras\Data;

/**
 * InvoicePaymentSlipInterest
 *
 * @property string $code
 * @property string $date
 * @property float $amountPerDay
 */
class InvoicePaymentSlipInterest extends Data
{
    public const CODE_FIXED_AMOUNT = '1';

    public const CODE_PERCENTUAL = '2';

    public const CODES = [
        self::CODE_FIXED_AMOUNT,
        self::CODE_PERCENTUAL,
    ];
}
