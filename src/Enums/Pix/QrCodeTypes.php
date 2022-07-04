<?php

namespace Idez\Caradhras\Enums\Pix;

enum QrCodeTypes: int
{
    case Static = 0;
    case DynamicImmediate = 1;
    case DynamicDueDate = 2;

    public function label(): string
    {
        return match ($this) {
            self::Static => 'static',
            self::DynamicDueDate => 'due_date',
            self::DynamicImmediate => 'immediate',
        };
    }
}
