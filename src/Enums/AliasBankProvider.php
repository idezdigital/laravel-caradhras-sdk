<?php

namespace Idez\Caradhras\Enums;

enum AliasBankProvider: string
{
    case Votorantim = '655';
    case BTG = '208';
    case Itau = '341';
    case Dock = '301';

    public function label(): string
    {
        $bankName = match ($this) {
            self::Votorantim => 'Votorantim',
            self::BTG => 'BTG Pactual',
            self::Itau => 'Itaú',
            self::Dock => 'Dock',
        };

        return "$bankName ($this->value)";
    }
}
