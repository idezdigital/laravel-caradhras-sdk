<?php

namespace Idez\Caradhras\Data;

use Idez\Caradhras\Data\Struct;


/**
 * @property string $origin
 * @property string $id
 * @property string $accountId
 * @property string|null $type
 * @property string|null $createdAt
 */
abstract class ConciergePayload extends Struct
{
    private string $origin;
    private string $id;
    private string $accountId;

    public array $payload = [];

    public function getConciergeId()
    {
        return $this->id;
    }

    public function getConciergeType()
    {
        return $this->type;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function __get($key)
    {
        return $this->payload[$key] ?? parent::__get($key);
    }
}
