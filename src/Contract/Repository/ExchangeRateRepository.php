<?php

namespace App\Contract\Repository;

use App\Entity\Currency;
use App\Entity\ExchangeRate;

interface ExchangeRateRepository
{
    /**
     * @description finds the exchange rate of two currencies.
     */
    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate;
}
