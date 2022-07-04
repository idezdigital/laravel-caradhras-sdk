<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class ExpirationDateException extends CaradhrasException
{
    public function __construct()
    {
        $errorKey = 'payments.expiration_date';
        parent::__construct(trans("errors.{$errorKey}"), 502, $errorKey);
    }
}
