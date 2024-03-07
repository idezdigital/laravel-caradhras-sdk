<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $bankBranchNumber
 * @property string|null $lastUnlockedDate
 * @property string $bankBranchDigit
 * @property string|null $closedDate
 * @property string $bankAccountNumber
 * @property string $createdDate
 * @property int $idAccount
 * @property string $bankAccountDigit
 * @property string $bankAccountType
 * @property string $bankNumber
 * @property string $bankAccountStatus
 * @property string|null $lastLockedDate
 */
class AliasBankAccount extends Data
{
    use HasFactory;
}
