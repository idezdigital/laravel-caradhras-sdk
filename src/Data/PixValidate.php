<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $ispb
 * @property string $bankName
 * @property string $bankAccountNumber
 * @property string $bankBranchNumber
 * @property string $bankAccountType
 * @property string $dateAccountCreated
 * @property string $beneficiaryType
 * @property string $nationalRegistration
 * @property string $name
 * @property string $tradeName
 * @property string $key
 * @property string $keyType
 * @property string $dateKeyCreated
 * @property string $dateKeyOwnership
 * @property string $idEndToEnd
 */
class PixValidate extends Data
{
    use HasFactory;
}
