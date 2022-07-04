<?php

namespace Idez\Caradhras\Enums;

enum SPDStatusCode: int
{
    case BlockedDueToIncompleteRegistration = 1;
    case BlockedDueToInvalidDocuments = 2;
    case BlockingDueToOutdatedRegistration = 3;
    case BlockingByJudicialOrder = 4;
    case BlockingDueToDataPending = 5;
    case SelfPendingBlock = 6;
    case BlockingPendingDocuments = 7;
    case PreventFraudBlocking = 8;
    case BlockingByIrregularCpf = 9;
    case BlockingByCourtNotification = 10;
    case WaitingDocumentsCashInAllowed = 11;
    case BlockedByDocumentAnalysisCashInAllowed = 12;
    case BlockedByDocumentAnalysisCashFullBlock = 13;
    case BlockedDueInactivity = 24;
}
