<?php

namespace Idez\Caradhras\Exceptions;

class PhoneRechargeConfirmationFailedException extends CaradhrasException
{
    public function __construct(array $data = [])
    {
        parent::__construct(trans("errors.phone-recharge.confirm"), 502, "phone-recharge.confirm", $data);
    }
}
