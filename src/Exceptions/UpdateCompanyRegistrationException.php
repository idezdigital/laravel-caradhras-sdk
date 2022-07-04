<?php

namespace Idez\Caradhras\Exceptions;

class UpdateCompanyRegistrationException extends CaradhrasException
{
    public function __construct()
    {
        parent::__construct('Failed to update the company registration', 502);
    }
}
