<?php

namespace Idez\Caradhras\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

abstract class BaseException extends Exception implements HttpExceptionInterface
{
    public const DEFAULT_ERROR_KEY = 'caradhras.generic_error';
    public const DEFAULT_STATUS_CODE = 500;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->getCode();
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [];
    }

    private string $key;

    protected array $data;

    public function __construct(?string $message, ?int $httpCode = null, ?string $key = null, ?array $data = null)
    {
        parent::__construct();

        $this->message = $message ?? 'Ocorreu um erro ao realizar a requisição.';
        $this->code = $httpCode ?? self::DEFAULT_STATUS_CODE;
        $this->key = $key ?? self::DEFAULT_ERROR_KEY;
        $this->data = $data ?? [];
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Render the exception.
     *
     * @return null|JsonResponse
     */
    public function render(): ?JsonResponse
    {
        // Necessary to not render the exception as JSON in Jetstream.
        if (! request()->expectsJson()) {
            return null;
        }

        return response()->json([
            'code' => $this->getKey(),
            'error' => get_class($this),
            'message' => $this->getMessage(),
            'data' => $this->getData(),
        ], $this->getCode());
    }
}
