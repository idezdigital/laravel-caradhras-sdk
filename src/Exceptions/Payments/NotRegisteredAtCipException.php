<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class NotRegisteredAtCipException extends CaradhrasException
{
    protected $code = 400;
}
