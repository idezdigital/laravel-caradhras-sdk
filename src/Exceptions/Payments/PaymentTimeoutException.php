<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class PaymentTimeoutException extends CaradhrasException
{
    protected $code = 504;
}
