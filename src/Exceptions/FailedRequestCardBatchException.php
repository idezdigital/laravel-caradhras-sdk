<?php

namespace Idez\Caradhras\Exceptions;

class FailedRequestCardBatchException extends CaradhrasException
{
    public function __construct()
    {
        parent::__construct(trans('errors.card.failed_request_card_batch'), 502);
    }
}
