<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class PaymentTimeoutException extends CaradhrasException
{
    public function __construct()
    {
        $errorKey = 'payments.timeout';
        parent::__construct(trans("errors.{$errorKey}"), 504, $errorKey);
    }
}
