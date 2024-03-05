<?php

namespace Idez\Caradhras\Data;

use Idez\Caradhras\Enums\PersonType;

/**
 * @property string $idEndToEnd
 * @property string $transactionDate,
 * @property int $idAdjustment
 * @property string $transactionCode
 * @property string $transactionStatus,
 * @property string $idTx
 */
class PixPayee extends Data
{
    public string $ispb;

    public string $bankAccountNumber;

    public string $bankBranchNumber;

    public string $bankAccountType;

    public PersonType $beneficiaryType;

    public string $nationalRegistration;

    public string $payeeName;

    public ?string $key;
}
