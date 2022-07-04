<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class NotRegisteredAtCipException extends CaradhrasException
{
    public function __construct()
    {
        $errorKey = 'payments.not_registered_at_cip';
        parent::__construct(trans("errors.{$errorKey}"), 502, $errorKey);
    }
}
