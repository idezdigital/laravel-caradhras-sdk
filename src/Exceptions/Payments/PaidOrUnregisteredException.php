<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class PaidOrUnregisteredException extends CaradhrasException
{
    public function __construct()
    {
        $errorKey = 'payments.unregistered_barcode_or_already_paid';
        parent::__construct(trans("errors.{$errorKey}"), 502, $errorKey);
    }
}
