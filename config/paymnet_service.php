<?php

return [
    'vnpay' => [
        'vnp_TmnCode' => env('VNP_TMNCODE'),
        'vnp_HashSecret' => env('VNP_HASHSECRET'),
        'vnp_Url' => env('VNP_URL'),
    ],
    'paypal' => [
        'paypal_client_id' => env('PAYPAL_CLIENT_ID'),
        'paypal_client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'paypal_mode' => env('PAYPAL_MODE', 'sandbox'),
    ],
    'percent_received' => env('PERCENT_RECEIVED', 0.8)
];
