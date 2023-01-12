<?php

namespace Idez\Caradhras\Clients;

use GuzzleHttp\Middleware;
use Idez\Caradhras\Exceptions\CaradhrasAuthException;
use Idez\Caradhras\Support\SentryBreadcrumbsMiddleware;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Base Api Client for Caradhras.
 */
abstract class BaseApiClient
{
    public const API_PREFIX = 'api';
    public const CARADHRAS_TOKEN_KEY = 'caradhras-token';

    private $accessToken;

    private $retryTimes = 0;
    private $retryInterval = 300;
    private $retryWhen;

    /**
     * BaseApiClient constructor.
     *
     * @param  string  $apiKey
     * @param  string  $apiSecret
     * @return void
     * @throws \Exception
     */
    public function __construct(private $apiKey, private $apiSecret)
    {
        $this->accessToken = $this->getAccessToken();
    }

    public function withRetry(callable $retryPolicy, int $retryTimes = 1, int $retryInterval = 300): self
    {
        $this->retryInterval = $retryInterval;
        $this->retryTimes = $retryTimes;
        $this->retryWhen = $retryPolicy;

        return $this;
    }

    /**
     * Cache refreshed auth token.
     *
     * @return string
     * @throws \Idez\Caradhras\Exceptions\CaradhrasAuthException
     * @throws \Exception
     */
    public function refreshAuthToken(): string
    {
        $auth = $this->getApiClientAuthentication();

        cache()->put(self::CARADHRAS_TOKEN_KEY, $auth->access_token, now()->addMinutes(40));

        return $auth->access_token;
    }

    /**
     * Return API Client with middlewares and auth.
     *
     * @param  bool  $throwsHttpError  (optional). Set as false to disable default exception handling.
     * @param  string|null  $apiPrefix
     * @return PendingRequest
     */
    protected function apiClient(bool $throwsHttpError = true, string $apiPrefix = null): PendingRequest
    {
        $options = $throwsHttpError ? ['http_errors' => true] : [];
        $baseUrl = $this->createApiBaseUri($apiPrefix ?? static::API_PREFIX);

        $client = Http::baseUrl($baseUrl)
            ->withMiddleware(Middleware::httpErrors())
            ->withOptions($options)
            ->withToken($this->accessToken);

        if ($this->retryTimes > 0) {
            $client->retry($this->retryTimes, $this->retryInterval, $this->retryWhen);
        }

        if (config('caradhras.enable_sentry_middleware')) {
            $client = $client->withMiddleware(SentryBreadcrumbsMiddleware::handle());
        }

        return $client;
    }

    /**
     * Create API base URL from prefix.
     *
     * @param  string  $prefix
     * @return string
     */
    protected function createApiBaseUri(string $prefix): string
    {
        $endpoint = config('caradhras.services.endpoint');

        return "https://{$prefix}.{$endpoint}";
    }

    /**
     * Get API base url.
     *
     * @return string
     */
    public function getApiBaseUrl(): string
    {
        return $this->createApiBaseUri(static::API_PREFIX);
    }

    /**
     * Get access token.
     *
     * @return string
     * @throws \Exception
     */
    private function getAccessToken(): string
    {
        /** @phpstan-ignore-next-line */
        if ($token = cache(self::CARADHRAS_TOKEN_KEY)) {
            return $token;
        }

        // TODO: remove this random token
        if (blank($this->apiKey) || blank($this->apiSecret) || app()->environment('testing')) {
            return Str::random(36);
        }

        return $this->refreshAuthToken();
    }

    /**
     * Get Api Client Authentication.
     *
     * @return object
     * @throws \Idez\Caradhras\Exceptions\CaradhrasAuthException
     */
    public function getApiClientAuthentication(): object
    {
        $response = Http::baseUrl($this->createApiBaseUri('auth'))
            ->withBasicAuth($this->apiKey, $this->apiSecret)
            ->asForm()
            ->post('/oauth2/token?grant_type=client_credentials');

        if ($response->failed()) {
            throw new CaradhrasAuthException();
        }

        return $response->object();
    }
}
