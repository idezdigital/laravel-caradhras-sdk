<?php

namespace Idez\Caradhras\Enums\BankTransfer;

use App\Models\BankTransfer;

enum BankTransferStatus: string
{
    case CR_STATUS_PROCESSING = 'WAITING_PROCESSING';
    case CR_STATUS_COMPLETED = 'CREDIT_DONE';
    case CR_STATUS_FAILED = 'FAILED_CREDIT_NOT_DONE';
    case CR_STATUS_EXECUTED = 'EXECUTED';

    public function mapping(): string
    {
        return match ($this) {
            self::CR_STATUS_PROCESSING => BankTransfer::STATUS_PROCESSING,
            self::CR_STATUS_COMPLETED, self::CR_STATUS_EXECUTED => BankTransfer::STATUS_COMPLETED,
            self::CR_STATUS_FAILED => BankTransfer::STATUS_FAILED,
        };
    }
}
