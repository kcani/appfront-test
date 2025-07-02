<?php

return [
    'base_url' => env('EXCHANGE_RATE_BASE_URL', 'https://open.er-api.com'),
    'base_from_currency' => env('EXCHANGE_RATE_BASE_FROM_CURRENCY', 'USD'),
    'base_to_currency' => env('EXCHANGE_RATE_BASE_TO_CURRENCY', 'EUR'),
];
