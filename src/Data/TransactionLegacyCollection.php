<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property TransactionLegacy[] $content
 */
class TransactionLegacyCollection extends DataCollection
{
    use HasFactory;

    protected static function getEntity(): string
    {
        return TransactionLegacy::class;
    }
}
