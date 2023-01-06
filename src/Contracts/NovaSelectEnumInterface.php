<?php

namespace Idez\Caradhras\Contracts;

interface NovaSelectEnumInterface
{
    public function label(): string;

    public static function novaOptions(): array;
}
