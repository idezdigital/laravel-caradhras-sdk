<?php

namespace Idez\Caradhras\Data;

/**
 * InvoicePaymentSlipDiscount
 *
 * @property string $code
 * @property string $date
 * @property float $amount
 */
class InvoicePaymentSlipDiscount extends Data
{
    public const CODE_PERCENTAGE_UNTIL_DATE = '1';

    public const CODE_PERCENTAGE_OVER_NOMINAL_ANTICIPATION_BY_DAY = '2';

    public const CODES = [
        self::CODE_PERCENTAGE_UNTIL_DATE,
        self::CODE_PERCENTAGE_OVER_NOMINAL_ANTICIPATION_BY_DAY,
    ];
}
