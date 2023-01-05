<?php

namespace Idez\Caradhras\Exceptions;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Response as HttpResponse;

class CaradhrasException extends \Exception
{
    protected $code = 502;
    public array $data = [];
    public string $error;

    public function __construct(string $message = "", int $code = 0, string $error = 'error', array $data = [])
    {
        parent::__construct($message, $code);

        $this->error = $error;
        $this->data = $data;
    }

    public static function failedGetIndividual(PromiseInterface|Response $response): self
    {
        $message = $response->status() === 404 ? 'Individual not found.' : 'Failed to get individual.';
        $statusCode = $response->status() === 404 ? HttpResponse::HTTP_NOT_FOUND : HttpResponse::HTTP_BAD_GATEWAY;

        return new self(__($message), $statusCode);
    }
}
