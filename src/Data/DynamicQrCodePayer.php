<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $payerName
 * @property string $beneficiaryType,
 * @property string $nationalRegistration,
 * @property ?string $payerQuestion,
 */
class DynamicQrCodePayer extends Data
{
    use HasFactory;
}
