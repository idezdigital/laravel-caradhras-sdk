<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class PaymentParseException extends CaradhrasException
{
    public function __construct()
    {
        $errorKey = 'payments.failed_to_parse';
        parent::__construct(trans("errors.{$errorKey}"), 502, $errorKey);
    }
}
