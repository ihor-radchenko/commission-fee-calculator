<?php

namespace App\Repository;

use App\Contract\Repository\ExchangeRateRepository;
use App\Entity\Currency;
use App\Entity\ExchangeRate;

class CacheProxyExchangeRateRepository implements ExchangeRateRepository
{
    private $exchangeRates;

    private $cache;

    public function __construct(ExchangeRateRepository $exchangeRates)
    {
        $this->exchangeRates = $exchangeRates;
        $this->cache = [];
    }

    /**
     * @description caches the exchange rate of two currencies at runtime.
     */
    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate
    {
        $cacheKey = $this->getCacheKey($fromCurrency, $toCurrency);

        if ($this->has($cacheKey)) {
            return $this->get($cacheKey);
        }

        return $this->set($cacheKey, $this->exchangeRates->getExchangeRate($fromCurrency, $toCurrency));
    }

    private function has(string $cacheKey): bool
    {
        return array_key_exists($cacheKey, $this->cache);
    }

    private function get(string $cacheKey): ExchangeRate
    {
        return $this->cache[$cacheKey];
    }

    private function set(string $cacheKey, ExchangeRate $exchangeRate): ExchangeRate
    {
        $this->cache[$cacheKey] = $exchangeRate;

        return $exchangeRate;
    }

    private function getCacheKey(Currency $fromCurrency, Currency $toCurrency): string
    {
        return "{$fromCurrency}{$toCurrency}";
    }
}
