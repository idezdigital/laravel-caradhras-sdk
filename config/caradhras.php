<?php

return [
    'endpoint' => env('CARADHRAS_ENDPOINT', 'hml.caradhras.io'),
    'proxy_endpoint' => env('CARADHRAS_PROXY_ENDPOINT', 'proxy.idez.test/proxies/caradhras/v1'),
    'client' => env('CARADHRAS_CLIENT', ''),
    'secret' => env('CARADHRAS_SECRET', ''),
    'use_proxy' => env('CARADHRAS_USE_PROXY', true),
    'enable_sentry_middleware' => env('CARADHRAS_ENABLE_SENTRY_MIDDLEWARE', true),
    'requests_origin' => env('CARADHRAS_REQUESTS_ORIGIN', '@idez/laravel-caradhras-sdk'),
];
