<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class ExpirationDateException extends CaradhrasException
{
    protected $code = 400;
}
