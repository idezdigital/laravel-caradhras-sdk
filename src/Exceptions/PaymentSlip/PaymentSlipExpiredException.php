<?php

namespace Idez\Caradhras\Exceptions\PaymentSlip;

use Idez\Caradhras\Exceptions\CaradhrasException;

class PaymentSlipExpiredException extends CaradhrasException
{
    protected $code = 400;
}
