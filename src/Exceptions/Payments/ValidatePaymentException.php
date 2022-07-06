<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class ValidatePaymentException extends CaradhrasException
{
    protected $code = 502;
}
