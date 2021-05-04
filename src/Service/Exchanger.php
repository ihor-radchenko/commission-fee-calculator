<?php

namespace App\Service;

use App\Contract\Repository\ExchangeRateRepository;
use App\Contract\Service\Exchanger as ExchangerContract;
use App\Contract\Service\Math;
use App\Entity\Currency;
use App\Entity\Money;
use App\Factory\CurrencyFactory;

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

        return $toCurrency->isSame(CurrencyFactory::createBase())
            ? $this->directExchange($money, $toCurrency)
            : $this->reverseExchange($money, $toCurrency);
    }

    private function directExchange(Money $money, Currency $toCurrency): Money
    {
        $rate = $this->exchangeRates->getExchangeRate($money->getCurrency(), $toCurrency);

        return new Money($this->math->div($money, $rate), $toCurrency);
    }

    private function reverseExchange(Money $money, Currency $toCurrency): Money
    {
        $rate = $this->exchangeRates->getExchangeRate($toCurrency, $money->getCurrency());

        return new Money($this->math->mul($money, $rate), $toCurrency);
    }
}
