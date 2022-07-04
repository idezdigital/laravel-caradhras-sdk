<?php

namespace Idez\Caradhras\Data;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $idAccount
 * @property int $covenantNumber
 * @property string $issuerBankNumber
 * @property string $idBankNumber
 * @property string $uniqueId
 * @property string $dueDate
 * @property float $amount
 * @property string $dateDocument
 * @property RechargePaymentSlipPayer $payer
 * @property object $beneficiary
 * @property object $coBeneficiary
 * @property int $bankBranchNumber
 * @property int $bankNumber
 * @property string $instructions
 * @property string $acceptance
 * @property int $status
 * @property string $barCode
 * @property string $barCodeNumber
 * @property string $type
 */
class RechargePaymentSlip extends Data
{
    use HasFactory;

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDueDate(): Carbon
    {
        return Carbon::parse($this->dueDate);
    }
}
