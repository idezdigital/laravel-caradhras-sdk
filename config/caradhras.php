<?php

return [
    'endpoint' => env('CARADHRAS_ENDPOINT', 'hml.caradhras.io'),
    'client' => env('CARADHRAS_CLIENT'),
    'secret' => env('CARADHRAS_SECRET'),
    'enable_sentry_middleware' => env('CARADHRAS_ENABLE_SENTRY_MIDDLEWARE', true),
];
