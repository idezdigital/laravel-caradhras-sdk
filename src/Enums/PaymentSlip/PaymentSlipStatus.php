<?php

namespace Idez\Caradhras\Enums\PaymentSlip;

enum PaymentSlipStatus: string
{
    case Registered = 'registered';
    case RegisteredAndConfirmed = 'registered_and_confirmed';
    case NotIssued = 'not_issued';
    case PaidAndConfirmed = 'paid_and_confirmed';
    case CancelledAndConfirmed = 'cancelled_and_confirmed';
    case CancelledAndNotConfirmed = 'cancelled_and_not_confirmed';
    case ChangedAndConfirmed = 'changed_and_confirmed';
    case ChangedAndNotConfirmed = 'changed_and_not_confirmed';
    case InstructionsDeclined = 'instructions_declined';
    case Contested = 'contestated';
    case CancelledByContestation = 'cancelled_by_contestation';
    case DeniedByAntiFraud = 'denied_by_anti_fraud';
    case Other = 'other';
}
