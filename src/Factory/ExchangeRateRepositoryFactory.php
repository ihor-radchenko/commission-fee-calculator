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

    /**
     * @description ApiExchangeRateRepository - repository with access to exchange rates via API https://exchangeratesapi.io/
     * CacheProxyExchangeRateRepository - the repository caches the exchange rates received from the API for the runtime.
     */
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

    /**
     * @description repository with a fixed exchange rate, intended for development, testing and debugging.
     */
    private function createStubRepository(): ExchangeRateRepository
    {
        return new StubExchangeRateRepository();
    }
}
