<?php

namespace App\Service;

use App\Entity\Money;
use App\Entity\Currency;
use App\Contract\Service\Math;
use App\Contract\Repository\ExchangeRateRepository;
use App\Contract\Service\Exchanger as ExchangerContract;

class Exchanger implements ExchangerContract
{
    private $exchangeRates;

    private $math;

    public function __construct(ExchangeRateRepository $exchangeRates, Math $math)
    {
        $this->exchangeRates = $exchangeRates;
        $this->math = $math;
    }

    public function exchange(Money $money, Currency $toCurrency): Money
    {
        if ($money->getCurrency()->isSame($toCurrency)) {
            return $money;
        }

        $rate = $this->exchangeRates->getExchangeRate($money->getCurrency(), $toCurrency);

        return new Money($this->math->div($money, $rate), $toCurrency);
    }
}