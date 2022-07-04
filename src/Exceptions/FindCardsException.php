<?php

namespace Idez\Caradhras\Exceptions;

class FindCardsException extends CaradhrasException
{
    public function __construct()
    {
        parent::__construct(trans('errors.card.failed_find_cards'), 404);
    }
}
