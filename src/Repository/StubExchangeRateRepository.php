<?php

namespace App\Repository;


use App\Entity\Currency;
use App\Entity\ExchangeRate;
use App\Contract\Repository\ExchangeRateRepository;

class StubExchangeRateRepository implements ExchangeRateRepository
{
    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate
    {
        $rates = [
            'USD' => '1.1497',
            'JPY' => '129.53',
        ];

        return new ExchangeRate($fromCurrency, $toCurrency, $rates[(string) $fromCurrency]);
    }
}
