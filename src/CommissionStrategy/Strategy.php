<?php

namespace App\CommissionStrategy;

use App\Entity\Money;
use App\Contract\CommissionStrategy;

abstract class Strategy implements CommissionStrategy
{
    private $next;

    public function setNext(CommissionStrategy $next): CommissionStrategy
    {
        $this->next = $next;

        return $next;
    }

    public function execute(Money $money, array $operations = []): Money
    {
        if ($this->next) {
            return $this->next->execute($money, $operations);
        }

        return $money;
    }
}
