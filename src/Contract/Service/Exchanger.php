<?php

namespace App\Contract\Service;

use App\Entity\Currency;
use App\Entity\Money;

interface Exchanger
{
    /**
     * @description converting a monetary value into a given currency.
     */
    public function exchange(Money $money, Currency $toCurrency): Money;
}
