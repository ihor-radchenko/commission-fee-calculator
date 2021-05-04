<?php

namespace App\Factory;

use App\Contract\Repository\ExchangeRateRepository;
use App\Exception\FactoryLogicException;
use App\Repository\ApiExchangeRateRepository;
use App\Repository\CacheProxyExchangeRateRepository;
use App\Repository\StubExchangeRateRepository;
use GuzzleHttp\Client;

class ExchangeRateRepositoryFactory
{
    use Singleton;

    public function createInstance(): ExchangeRateRepository
    {
        $driver = ucfirst(ConfigFactory::create()->get('currencies.storage.driver', 'stub'));

        $factoryMethod = "create{$driver}Repository";

        if (method_exists($this, $factoryMethod)) {
            return $this->{$factoryMethod}();
        }

        throw new FactoryLogicException(ExchangeRateRepository::class);
    }

    private function createApiRepository(): ExchangeRateRepository
    {
        $client = new Client([
            'base_uri' => ConfigFactory::create()->get('services.exchangeratesapi.base_uri'),
        ]);

        $exchangeRateApi = new ApiExchangeRateRepository(
            $client,
            ConfigFactory::create()->get('services.exchangeratesapi.access_key')
        );

        return new CacheProxyExchangeRateRepository($exchangeRateApi);
    }

    private function createStubRepository(): ExchangeRateRepository
    {
        return new StubExchangeRateRepository();
    }
}
