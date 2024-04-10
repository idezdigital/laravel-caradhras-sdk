<?php

namespace Idez\Caradhras\Enums\PaymentSlip;

enum PaymentSlipStatus: int
{
    case Registered = 1;
    case RegisteredAndConfirmed = 2;
    case NotIssued = 3;
    case PaidAndConfirmed = 4;
    case CancelledAndConfirmed = 5;
    case CancelledAndNotConfirmed = 6;
    case ChangedAndConfirmed = 7; // todo: what do in this case?
    case ChangedAndNotConfirmed = 8; // todo: what do in this case?
    case Other = 9;
    case InstructionsDeclined = 10;
    case Contested = 11;
    case CancelledByContestation = 12;
    case DeniedByAntiFraud = 13; // todo: not sure
}
