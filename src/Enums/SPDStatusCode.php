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
    case CommercialDisinterest = 14;
    case CancelledByRiskPolicy = 21;
    case BlockedByRiskyPoliceResendDocuments = 15;
    case BlockedByRiskyPolice = 16;
    case BlockedFraudConfirmed = 17;
    case BlockedBySanitization = 18;
    case BlockedBySanitizationResendDocuments = 19;
    case BlockedBySanitizationDocumentaryAdjustment = 20;
    case BlockedIncompleteDocumentation = 22;
    case BlockedClosingRegistration = 23;
    case BlockedDueInactivity = 24;
    case BlockedViolationAlert = 25;
    case BlockedViolationSuspected = 26;
}
