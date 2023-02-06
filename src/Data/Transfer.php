<?php

namespace Idez\Caradhras\Data;

use Idez\Caradhras\Database\Factories\TransferFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int accountId
 * @property float amount
 * @property int transferType
 * @property string externalId
 * @property string transactionId
 * @property string transactionDate
 * @property string transactionCode
 * @property string adjustmentId
 * @property string status
 * @property string reason
 */

class Transfer extends Data
{
    use HasFactory;

    protected static function newFactory()
    {
        return TransferFactory::new();
    }
}
