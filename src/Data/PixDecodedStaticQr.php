<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PixDecodedStaticQr
 * @package App\Models\Caradhras
 *
 * @property string $idEndToEnd
 * @property int $codeType
 * @property string $ispb
 * @property string $bankName
 * @property string $bankAccountNumber
 * @property string $bankBranchNumber
 * @property string $bankAccountType
 * @property string $key
 * @property string $beneficiaryType
 * @property string $nationalRegistration
 * @property float $finalAmount
 * @property string $payeeName
 * @property string $city
 * @property string $zipCode
 * @property string $details
 * @property string $idTx
 */
class PixDecodedStaticQr extends Data
{
    use HasFactory;
}
