<?php

namespace Idez\Caradhras\Enums\Pix;

use App\Models\PixTransfer;

enum PixTransferStatus: string
{
    case Executed = 'EXECUTED';
    case NotExecuted = 'NOT_EXECUTED';
    case Pending = 'PENDING';

    public function mapping(): string
    {
        return match ($this) {
            self::Executed => PixTransfer::STATUS_EXECUTED,
            self::NotExecuted => PixTransfer::STATUS_FAILED,
            self::Pending => PixTransfer::STATUS_PENDING,
        };
    }
}
