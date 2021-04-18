<?php

namespace App\Service;

use App\Entity\Money;
use App\Entity\Currency;
use App\Contract\Repository\ExchangeRateRepository;
use App\Contract\Service\Exchanger as ExchangerContract;

class Exchanger implements ExchangerContract
{
    private $exchangeRates;

    public function __construct(ExchangeRateRepository $exchangeRates)
    {
        $this->exchangeRates = $exchangeRates;
    }

    public function exchange(Money $money, Currency $toCurrency): Money
    {
        if ($money->getCurrency()->isSame($toCurrency)) {
            return $money;
        }

        $rate = $this->exchangeRates->getExchangeRateFor($money->getCurrency());
    }
}