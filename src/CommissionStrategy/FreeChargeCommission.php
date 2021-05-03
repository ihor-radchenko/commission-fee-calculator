<?php

namespace App\CommissionStrategy;

use App\Entity\Money;
use App\Contract\CommissionStrategy;

class FreeChargeCommission extends Strategy
{
    private $freeStrategy;

    public function __construct(CommissionStrategy $commissionStrategy)
    {
        $this->freeStrategy = $commissionStrategy;
    }

    public function execute(Money $money, array $operations = []): Money
    {
        $moneyWithDiscount = $this->freeStrategy->execute($money, $operations);

        return parent::execute($moneyWithDiscount, $operations);
    }
}
