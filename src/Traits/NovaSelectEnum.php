<?php

namespace Idez\Caradhras\Traits;

trait NovaSelectEnum
{
    public static function novaOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
