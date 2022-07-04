<?php

namespace Idez\Caradhras\Exceptions;

class CVVMismatchException extends CaradhrasException
{
    public function __construct()
    {
        parent::__construct(trans('errors.card.cvv_mismatch'), 400, 'card.cvv_mismatch');
    }
}
