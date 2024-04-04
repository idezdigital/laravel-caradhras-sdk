<?php

namespace Idez\Caradhras\Enums\Pix;

enum DictKeyType: string
{
    case Evp = 'EVP';
    case Email = 'email';
    case Phone = 'phoneNumber';
    case Document = 'nationalRegistration';

    public static function claimable()
    {
        return [
            self::Phone,
            self::Email,
            self::Document,
        ];
    }
}
