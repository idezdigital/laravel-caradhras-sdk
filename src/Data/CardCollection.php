<?php

namespace Idez\Caradhras\Data;

/**
 * Class CardCollection
 * @property Card[] $content
 */
class CardCollection extends DataCollection
{
    protected static function getEntity(): string
    {
        return Card::class;
    }
}
