<?php

return [
    'services' => [
        'exchangeratesapi' => [
            /** Free Plan does not support HTTPS Encryption. */
            'base_uri'   => 'http://api.exchangeratesapi.io/',
            'access_key' => '30543a19a43ca9f5eccfff6935258175',
        ],
    ],
];
