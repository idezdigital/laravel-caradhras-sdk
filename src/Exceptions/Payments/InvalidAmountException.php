<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class InvalidAmountException extends CaradhrasException
{
    public function __construct()
    {
        $errorKey = 'payments.invalid_amount';
        parent::__construct(trans("errors.{$errorKey}"), 502, $errorKey);
    }
}
