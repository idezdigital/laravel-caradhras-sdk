<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string emv
 * @property string text
 * @property string image
 * @property string payloadURL
 * @property string idDocument
 * @property string idTx
 */
class DynamicQrcode extends Data
{
    use HasFactory;
}
