<?php

return [
    'services' => [
        'exchangeratesapi' => [
            'base_uri'   => 'http://api.exchangeratesapi.io/',
            'access_key' => getenv('EXCHANGERATESAPI_KEY') ?: '',
        ],
    ],

    'currencies' => [
        'base' => 'eur',

        'precision' => [
            'default' => 2,

            'JPY' => 0,
        ],

        'storage' => [
            'driver' => getenv('CURRENCIES_STORE_DRIVER') ?: 'stub',
        ]
    ],

    'commission' => [
        'strategy' => [
            'private'  => [
                'deposit'  => [
                    'commission' => .0003,
                ],
                'withdraw' => [
                    'commission'           => .003,
                    'free_period'          => 'this week',
                    'free_limit_operation' => 3,
                    'free_amount'          => [
                        'amount'   => 1000,
                        'currency' => 'eur',
                    ],
                ],
            ],
            'business' => [
                'deposit'  => [
                    'commission' => .0003,
                ],
                'withdraw' => [
                    'commission' => .005,
                ],
            ],
        ],
    ],
];
