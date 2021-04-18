<?php

namespace App\Contract\Service;

use App\Entity\Money;
use App\Entity\Currency;

interface Exchanger
{
    public function exchange(Money $money, Currency $toCurrency): Money;
}
