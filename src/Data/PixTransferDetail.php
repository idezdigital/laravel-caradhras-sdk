<?php

namespace Idez\Caradhras\Data;

use App\Contracts\Caradhras\PixTransferCreditParty;
use App\Contracts\Caradhras\PixTransferDebitParty;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PixTransferDetail
 * @package App\Models\Caradhras
 *
 * @property int $idAccount
 * @property string $idEndToEnd
 * @property string $transactionDate
 * @property string $transactionType
 * @property string $transactionStatus
 * @property int $transferType
 * @property null|string $errorType
 * @property PixTransferDebitParty $debitParty
 * @property PixTransferCreditParty $creditParty
 * @property float $amount
 * @property float $tariffAmount
 * @property float $finalAmount
 * @property int $idAdjustment
 * @property string $transactionCode
 * @property null|string $idTx
 * @property string $payerAnswer
 */
class PixTransferDetail extends Data
{
    use HasFactory;
}
