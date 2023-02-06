<?php

namespace Idez\Caradhras\Enums\Account;

enum AccountStatus: string
{
    case WaitingDocuments = 'waiting_documents';
    case WaitingAnalysis = 'waiting_analysis';
    case WaitingCorrections = 'waiting_corrections';
    case Active = 'active';
    case Declined = 'declined';
    case Canceled = 'canceled';
}
