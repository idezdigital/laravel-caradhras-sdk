<?php

namespace Idez\Caradhras\Exceptions\PaymentSlip;

use Idez\Caradhras\Exceptions\CaradhrasException;

class PaymentSlipNotFoundException extends CaradhrasException
{
    protected $code = 404;
}
