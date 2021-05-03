<?php

namespace App\Contract;

use App\Entity\Money;

interface CommissionStrategy
{
    public function execute(Money $money, array $operations = []): Money;

    public function setNext(CommissionStrategy $next): CommissionStrategy;
}
