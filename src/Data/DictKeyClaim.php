<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $message
 * @property string $ispb
 * @property string $keyType
 * @property string $key
 * @property string $keyStatus
 * @property \Idez\Caradhras\Data\Pix\PixKeyClaimKeyClaimant $keyClaimant
 * @property string $description
 * @property string $grantorDeadline
 * @property string $claimDeadline
 * @property string $claimUUID
 */
class DictKeyClaim extends Data
{
    use HasFactory;
}
