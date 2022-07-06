<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class NotAllowedNowException extends CaradhrasException
{
    protected $code = 502;
}
