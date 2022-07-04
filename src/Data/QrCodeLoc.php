<?php

namespace Idez\Caradhras\Data;

use App\Enums\Pix\LocType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $url
 * @property string $emv
 * @property LocType $locType
 * @property string $id
 * @property string $idTx
 * @property string $loc
 */
class QrCodeLoc extends Data
{
    use HasFactory;
}
