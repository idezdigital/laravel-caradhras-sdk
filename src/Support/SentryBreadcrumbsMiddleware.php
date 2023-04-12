<?php

namespace Idez\Caradhras\Support;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sentry\Breadcrumb;
use Sentry\State\HubInterface;

class SentryBreadcrumbsMiddleware
{
    public static function handle(): \Closure
    {
        /** @var HubInterface */
        $sentry = app('sentry');

        return function (callable $handler) use ($sentry) {
            return function (RequestInterface $request, array $options) use ($handler, $sentry) {
                $sentry->addBreadcrumb(new Breadcrumb(
                    Breadcrumb::LEVEL_INFO,
                    Breadcrumb::TYPE_HTTP,
                    'cr.request',
                    null,
                    [
                        'uri' => $request->getUri()->__toString(),
                        'method' => $request->getMethod(),
                        'body' => $request->getBody()->getContents(),
                    ]
                ));

                return $handler($request, $options)->then(function (ResponseInterface $response) use ($sentry) {
                    $level = Breadcrumb::LEVEL_DEBUG;
                    if ($response->getStatusCode() > 400) {
                        $sentry->configureScope(fn($scope) => $scope->setTag('service', $prefix));
                        $level = Breadcrumb::LEVEL_ERROR;
                    }

                    $sentry->addBreadcrumb(new Breadcrumb(
                        $level,
                        Breadcrumb::TYPE_HTTP,
                        'cr.response',
                        null,
                        [
                            'status' => $response->getStatusCode(),
                            'body' => $response->getBody()->getContents(),
                        ]
                    ));

                    $response->getBody()->rewind();

                    return $response;
                });
            };
        };
    }
}
