<?php

return [
     'endpoint' => env('CARADHRAS_ENDPOINT', 'hml.caradhras.io'),
     'client' => env('CARADHRAS_CLIENT'),
     'secret' => env('CARADHRAS_SECRET'),
     'enable_sentry_middleware' => env('CARADHRAS_ENABLE_SENTRY_MIDDLEWARE', true),
     'app' => [
        'product_id' => (int) env('PRODUCT_ID', 1),
        'business_source_id' => (int) env('BUSINESS_SOURCE_ID', 1),
        'plastic_id' => (int) env('PLASTIC_ID', 2),
     ],
];
