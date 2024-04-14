<?php

namespace Idez\Caradhras\Exceptions\Cards;

use Idez\Caradhras\Exceptions\CaradhrasException;
use Sentry\Severity;

class CardAlreadyCanceledException extends CaradhrasException
{
    public function __construct()
    {
        parent::__construct(trans('errors.services.caradhras.card.already_canceled'), 400);
    }

    public function level(): Severity
    {
        return Severity::info();
    }
}
