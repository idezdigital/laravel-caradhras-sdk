<?php

namespace Idez\Caradhras\Exceptions;

use Idez\Caradhras\Contracts\ExceptionWithLevel;
use Sentry\Severity;

class FraudDetectorException extends BaseException implements ExceptionWithLevel
{
    public function __construct()
    {
        parent::__construct(
            trans('errors.fraud_detection'),
            400,
            'errors.fraud_detection',
        );
    }

    public function level(): Severity
    {
        return Severity::info();
    }
}
