<?php

namespace Idez\Caradhras\Exceptions;

class CaradhrasException extends \Exception
{
    protected $code = 502;

    public array $data = [];

    public string $error;

    public function __construct(string $message = '', int $code = 0, string $error = 'error', array $data = [])
    {
        parent::__construct($message, $code);

        $this->error = $error;
        $this->data = $data;
    }
}
