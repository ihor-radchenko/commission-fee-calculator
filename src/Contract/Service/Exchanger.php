<?php

namespace App\Contract\Service;

use App\Entity\Currency;
use App\Entity\Money;

interface Exchanger
{
    public function exchange(Money $money, Currency $toCurrency): Money;
}
