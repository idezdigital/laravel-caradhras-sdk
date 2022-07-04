<?php

namespace Idez\Caradhras\Exceptions;

class TransferFailedException extends CaradhrasException
{
    public function __construct(string $originalError)
    {
        parent::__construct(trans('errors.transfer.failed'), 502, 'transfer.failed', [
            'original_error' => $originalError,
        ]);
    }
}
