<?php

namespace Idez\Caradhras\Exceptions;

class InsufficientBalanceException extends CaradhrasException
{
    public function __construct()
    {
        parent::__construct(
            trans('errors.insufficient_balance_to_transaction'),
            400,
            'errors.insufficient_balance_to_transaction',
        );
    }
}
