<?php

namespace App\CommissionStrategy;

use App\Entity\Money;

class FreeLimitOperation extends Strategy
{
    private $freeLimit;

    public function __construct(int $freeLimit)
    {
        $this->freeLimit = $freeLimit;
    }

    /**
     * @description this handler checks the number of operations during the privileges period.
     */
    public function execute(Money $money, array $operations = []): Money
    {
        if (count($operations) >= $this->freeLimit) {
            return $money;
        }

        return parent::execute($money, $operations);
    }
}
