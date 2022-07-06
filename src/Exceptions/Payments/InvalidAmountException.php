<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class InvalidAmountException extends CaradhrasException
{
    protected $code = 400;
}
