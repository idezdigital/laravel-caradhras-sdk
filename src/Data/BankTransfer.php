<?php

namespace Idez\Caradhras\Data;

use App\Models\Caradhras\Struct;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $transactionId
 * @property int $adjustmentId
 * @property int $transferType
 * @property float $amount
 * @property string $description
 * @property string $status
 * @property string $reason
 * @property string $transactionDate
 * @property ?string $schedulingDate
 * @property object $consignor
 * @property object $beneficiary
 */
class BankTransfer extends Data
{
}
