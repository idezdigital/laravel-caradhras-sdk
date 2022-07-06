<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class InvalidPaymentBarcodeException extends CaradhrasException
{
    protected $code = 400;
}
