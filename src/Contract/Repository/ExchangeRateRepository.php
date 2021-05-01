<?php

namespace App\Contract\Repository;

use App\Entity\Currency;
use App\Entity\ExchangeRate;

interface ExchangeRateRepository
{
    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate;
}
