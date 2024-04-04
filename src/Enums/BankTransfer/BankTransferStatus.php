<?php

namespace Idez\Caradhras\Enums\BankTransfer;

enum BankTransferStatus: string
{
    case CR_STATUS_PROCESSING = 'WAITING_PROCESSING';
    case CR_STATUS_COMPLETED = 'CREDIT_DONE';
    case CR_STATUS_FAILED = 'FAILED_CREDIT_NOT_DONE';
    case CR_STATUS_EXECUTED = 'EXECUTED';
}
