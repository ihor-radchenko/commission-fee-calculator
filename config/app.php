<?php

return [
    'services' => [
        'exchangeratesapi' => [
            'base_uri'   => 'http://api.exchangeratesapi.io/',
            'access_key' => '30543a19a43ca9f5eccfff6935258175',
        ],
    ],

    'currencies' => [
        'precision' => [
            'default' => 2,

            'JPY' => 0,
        ]
    ]
];
