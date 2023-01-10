<?php

namespace Idez\Caradhras\Exceptions;

class TransferFailedException extends CaradhrasException
{
    public function __construct(string $originalError)
    {
        parent::__construct(trans('errors.transfer_failed'), 502, 'transfer_failed', [
            'original_error' => $originalError,
        ]);
    }
}
