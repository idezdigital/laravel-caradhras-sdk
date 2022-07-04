<?php

namespace Idez\Caradhras\Enums\Pix;

use Illuminate\Support\Str;

enum ClaimType: string
{
    case Portability = 'portability';
    case KeyTransfer = 'key_transfer';

    public function toCaradhras(): string
    {
        return Str::of($this->value)->upper();
    }
}
