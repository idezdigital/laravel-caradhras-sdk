<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $flagTitular
 * @property string $timestamp
 * @property string $mensagem
 * @property TariffDetail $payload
 */
class Tariff extends Data
{
    use HasFactory;
}
