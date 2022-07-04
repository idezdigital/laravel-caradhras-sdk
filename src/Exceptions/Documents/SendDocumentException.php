<?php

namespace Idez\Caradhras\Exceptions\Documents;

class SendDocumentException extends \Idez\Caradhras\Exceptions\CaradhrasException
{
    public function __construct(array $responseData, int $code = 502)
    {
        parent::__construct('Failed to send document', $code);

        $this->data = $responseData;
    }
}
