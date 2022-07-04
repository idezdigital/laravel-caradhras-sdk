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
 * @property string $dateDocument
 * @property object $beneficiary
 * @property object $coBeneficiary
 * @property object $paymentslip
 * @property InvoicePaymentSlipFine $fine
 * @property InvoicePaymentSlipDiscount $discount
 * @property InvoicePaymentSlipInterest $interest
 * @property object $others
 * @property InvoicePaymentSlipPayer $payer
 * @property int $bankBranchNumber
 * @property int $bankNumber
 * @property string $instructions
 * @property int $status
 * @property string $barCode
 * @property string $barCodeNumber
 * @property string $type
 */
class InvoicePaymentSlip extends Data
{
    use HasFactory;

    public function getAmount(): float
    {
        return $this->paymentslip->amount;
    }

    public function getDueDate(): Carbon
    {
        return Carbon::parse($this->paymentslip->dueDate);
    }
}
