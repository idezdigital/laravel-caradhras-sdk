<?php

namespace Idez\Caradhras\Contracts;

use Sentry\Severity;

interface ExceptionWithLevel
{
    public function level(): Severity;
}
