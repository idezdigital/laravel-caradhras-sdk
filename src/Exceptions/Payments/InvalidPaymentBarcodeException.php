<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class InvalidPaymentBarcodeException extends CaradhrasException
{
    public function __construct()
    {
        $errorKey = 'payments.invalid_barcode';
        parent::__construct(trans("errors.{$errorKey}"), 400, $errorKey);
    }
}
