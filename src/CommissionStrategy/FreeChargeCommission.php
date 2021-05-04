<?php

namespace App\CommissionStrategy;

use App\Contract\CommissionStrategy;
use App\Entity\Money;

class FreeChargeCommission extends Strategy
{
    private $freeStrategy;

    public function __construct(CommissionStrategy $commissionStrategy)
    {
        $this->freeStrategy = $commissionStrategy;
    }

    public function execute(Money $money, array $operations = []): Money
    {
        return parent::execute($this->freeStrategy->execute($money, $operations), $operations);
    }
}
