<?php

namespace Idez\Caradhras\Enums\Transfers;

enum P2PStatus: string
{
    case Approved = 'APPROVED';
    case Completed = 'COMPLETED';
    case CompletedByTerm = 'COMPLETED_BY_TERM';
    case CompletedManually = 'COMPLETED_MANUALLY';
    case Canceled = 'CANCELED';
    case CanceledFraudReferred = 'CANCELED_FRAUD_REFERRED';
    case CanceledFraudDenied = 'CANCELED_FRAUD_DENIED';
    case Processed = 'PROCESSED';
    case NotProcessed = 'NOT_PROCESSED';
    case Undone = 'UNDONE';
    case Reversed = 'REVERSED';
    case Pending = 'PENDING';
    case PendingGlobalAvailability = 'PENDING_GLOBAL_AVAILABILITY';
    case DailyAmountExceeded = 'DAILY_AMOUNT_EXCEEDED';
}
