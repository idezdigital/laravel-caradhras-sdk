<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $previousPage
 * @property int $currentPage
 * @property int $nextPage
 * @property bool $last
 * @property int $totalPages
 * @property int $totalItems
 * @property int $maxItemsPerPage
 * @property int $totalItemsPage
 * @property Limit[] $items
 */
class LimitCollection extends DataCollection
{
    use HasFactory;

    protected static function getEntity(): string
    {
        return Limit::class;
    }

    protected static function getKey(): string
    {
        return 'items';
    }
}
