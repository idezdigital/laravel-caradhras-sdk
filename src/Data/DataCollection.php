<?php

namespace Idez\Caradhras\Data;

use Psr\Http\Message\ResponseInterface;

/**
 * @property int number
 * @property int size
 * @property int totalPages
 * @property int numberOfElements
 * @property int totalElements
 * @property bool firstPage
 * @property bool hasPreviousPage
 * @property bool hasNextPage
 * @property bool hasContent
 * @property bool first
 * @property bool last
 * @property int nextPage
 * @property int previousPage
 */
abstract class DataCollection extends Data
{
    abstract protected static function getEntity(): string;

    protected static function getKey(): string
    {
        return 'content';
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->toArray());
    }

    public function __construct($data = [])
    {
        if ($data instanceof ResponseInterface) {
            $data = $this->toJson($data, true);
        }

        $itemsKey = static::getKey();
        if (isset($data[$itemsKey]) && is_array($data[$itemsKey])) {
            $entity = static::getEntity();
            $data[$itemsKey] = array_map(fn ($val) => new $entity($val), $data[$itemsKey]);
        } else {
            $data[$itemsKey] = [];
        }

        parent::__construct($data);
    }
}
