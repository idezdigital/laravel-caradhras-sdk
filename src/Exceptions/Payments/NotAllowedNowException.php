<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class NotAllowedNowException extends CaradhrasException
{
    public function __construct()
    {
        $errorKey = 'payments.not_allowed_now';
        parent::__construct(trans("errors.{$errorKey}"), 502, $errorKey);
    }
}
