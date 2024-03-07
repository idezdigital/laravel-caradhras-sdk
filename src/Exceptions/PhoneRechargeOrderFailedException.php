<?php

namespace Idez\Caradhras\Exceptions;

class PhoneRechargeOrderFailedException extends CaradhrasException
{
    public function __construct(array $data = [])
    {
        parent::__construct(trans('errors.phone-recharge.order'), 502, 'phone-recharge.order', $data);
    }
}
