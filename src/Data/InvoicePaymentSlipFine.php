<?php

namespace Idez\Caradhras\Data;

/**
 * InvoicePaymentSlipFine
 *
 * @property string $code
 * @property string $date
 * @property float $amount
 */
class InvoicePaymentSlipFine extends Data
{
    public const CODE_FIXED_AMOUNT = '1';

    public const CODE_PERCENTUAL_FINE = '2';

    public const CODES = [
        self::CODE_FIXED_AMOUNT,
        self::CODE_PERCENTUAL_FINE,
    ];
}
