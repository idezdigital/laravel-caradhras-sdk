<?php

namespace Idez\Caradhras\Exceptions;

class GetCardDetailsException extends CaradhrasException
{
    public function __construct(array $response = [])
    {
        parent::__construct(trans('errors.card.failed_get_details'), 502, 'card.failed_get_details', $response);
    }
}
