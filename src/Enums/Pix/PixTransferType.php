<?php

namespace Idez\Caradhras\Enums\Pix;

enum PixTransferType: int
{
    case Manual = 0;
    case Key = 1;
    case StaticQRCode = 2;
    case DynamicQRCode = 3;

    public static function qrCode()
    {
        return [
            self::StaticQRCode,
            self::DynamicQRCode,
        ];
    }
}
