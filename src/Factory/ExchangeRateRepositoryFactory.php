<?php

namespace App\Factory;

use GuzzleHttp\Client;
use App\Repository\ApiExchangeRateRepository;
use App\Repository\StubExchangeRateRepository;
use App\Contract\Repository\ExchangeRateRepository;

class ExchangeRateRepositoryFactory
{
    public static function create(): ExchangeRateRepository
    {
        return self::createStubRepository();
    }

    private static function createApiRepository(): ExchangeRateRepository
    {
        $client = new Client([
            'base_uri' => ConfigFactory::create()->get('services.exchangeratesapi.base_uri'),
        ]);

        return new ApiExchangeRateRepository(
            $client,
            ConfigFactory::create()->get('services.exchangeratesapi.access_key')
        );
    }

    private static function createStubRepository(): ExchangeRateRepository
    {
        return new StubExchangeRateRepository();
    }
}