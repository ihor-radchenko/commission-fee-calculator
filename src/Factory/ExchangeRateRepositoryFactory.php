<?php

namespace App\Factory;

use GuzzleHttp\Client;
use App\Repository\ApiExchangeRateRepository;
use App\Contract\Repository\ExchangeRateRepository;

class ExchangeRateRepositoryFactory
{
    public static function create(): ExchangeRateRepository
    {
        return self::createApiRepository();
    }

    private static function createApiRepository(): ExchangeRateRepository
    {
        $config = require __DIR__ . '/../../config/app.php';

        $client = new Client([
            'base_uri' => $config['services']['exchangeratesapi']['base_uri']
        ]);

        return new ApiExchangeRateRepository($client, $config['services']['exchangeratesapi']['access_key']);
    }
}