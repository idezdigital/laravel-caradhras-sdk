<?php

namespace Idez\Caradhras\Enums\PaymentSlip;

enum PaymentSlipType: int
{
    case Recharge = 1;
    case Invoice = 2;
}
