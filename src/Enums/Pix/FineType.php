<?php

namespace Idez\Caradhras\Enums\Pix;

use App\Contracts\Caradhras\CrType;

enum FineType: string implements CrType
{
    case Fixed = 'fixed';
    case Percentual = 'percentual';

    public function crType(): string
    {
        return match ($this) {
            self::Fixed => 'FIXED_VALUE',
            self::Percentual => 'PERCENT',
        };
    }
}
