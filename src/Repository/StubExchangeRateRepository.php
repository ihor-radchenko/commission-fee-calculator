<?php

namespace App\Repository;

use App\Contract\Repository\ExchangeRateRepository;
use App\Entity\Currency;
use App\Entity\ExchangeRate;
use App\Exception\InvalidCurrencyException;

class StubExchangeRateRepository implements ExchangeRateRepository
{
    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate
    {
        $rates = [
            'USD' => '1.1497',
            'JPY' => '129.53',
        ];

        if (!array_key_exists((string) $fromCurrency, $rates)) {
            throw new InvalidCurrencyException($fromCurrency);
        }

        return new ExchangeRate($fromCurrency, $toCurrency, $rates[(string) $fromCurrency]);
    }
}
