<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property UnprocessedTransaction[] $content
 */
class UnprocessedTransactionCollection extends DataCollection
{
    use HasFactory;

    protected static function getEntity(): string
    {
        return UnprocessedTransaction::class;
    }
}
