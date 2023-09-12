<?php

namespace Idez\Caradhras\Exceptions\Payments;

use Idez\Caradhras\Exceptions\CaradhrasException;

class ConvenantNotAuthorizedException extends CaradhrasException
{
    protected $code = 400;
}
