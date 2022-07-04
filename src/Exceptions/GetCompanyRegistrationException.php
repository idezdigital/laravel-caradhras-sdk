<?php

namespace Idez\Caradhras\Exceptions;

class GetCompanyRegistrationException extends CaradhrasException
{
    public function __construct()
    {
        parent::__construct('Failed to find the company registration', 404);
    }
}
