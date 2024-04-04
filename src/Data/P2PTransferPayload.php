<?php

namespace Idez\Caradhras\Data;

use Carbon\Carbon;
use Idez\Caradhras\Enums\Transfers\P2PStatus;

class P2PTransferPayload
{
    /** @var string */
    public $transactionCode;

    /** @var int */
    public $originalAccount;

    /** @var int */
    public $destinationAccount;

    /** @var string */
    public $description;

    /** @var int */
    public $idAdjustment;

    /** @var int */
    public $idIssuer;

    /** @var int */
    public $idAdjustmentDestination;

    /** @var mixed */
    public $amount;

    public $transactionDate;

    /** @var P2PStatus */
    public $status;

    /** @var string|null  */
    public $cause;

    private $raw;

    public function __construct(array $payload)
    {
        $this->transactionCode = $payload['transactionCode'];
        $this->originalAccount = $payload['originalAccount'];
        $this->destinationAccount = $payload['destinationAccount'];
        $this->description = $payload['description'];
        $this->idAdjustment = $payload['idAdjustment'];
        $this->idIssuer = $payload['idIssuer'];
        $this->idAdjustmentDestination = $payload['idAdjustmentDestination'];
        $this->amount = $payload['amount'];
        $this->transactionDate = Carbon::parse($payload['transactionDate']);
        $this->status = P2PStatus::tryFrom($payload['status']);
        $this->cause = $payload['cause'] ?? null;
        $this->raw = $payload;
    }

    public function toArray(): array
    {
        return $this->raw;
    }
}
