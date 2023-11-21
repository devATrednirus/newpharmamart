<?php

return [

    'payu' => [
        'mode'      => env('PAYU_MODE', 'test'),
        'merchant_key'  => env('PAYU_MERCHANT_KEY', ''),
        'merchant_salt'  => env('PAYU_MERCHANT_SALT', ''),
        'auth_header' => env('PAYU_AUTH_HEADER', ''),
    ],

];
