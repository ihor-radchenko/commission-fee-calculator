<?php

namespace App\CommissionStrategy;

use App\Entity\Money;
use App\Contract\Service\Math;

class PercentCommission extends Strategy
{
    private $math;

    private $commissionPercent;

    public function __construct(Math $math, $commissionPercent)
    {
        $this->math = $math;
        $this->commissionPercent = $commissionPercent;
    }

    public function execute(Money $money, array $operations = []): Money
    {
        return new Money($this->math->mul($money, $this->commissionPercent), $money->getCurrency());
    }
}