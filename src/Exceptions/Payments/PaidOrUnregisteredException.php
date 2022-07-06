<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class PaidOrUnregisteredException extends CaradhrasException
{
    protected $code = 400;
}
