<?php

namespace App\CommissionStrategy;

use App\Contract\Service\Exchanger;
use App\Contract\Service\Math;
use App\Entity\Money;

class FreeLimitAmount extends Strategy
{
    private $exchanger;

    private $math;

    private $freeAmount;

    public function __construct(Exchanger $exchanger, Math $math, Money $freeAmount)
    {
        $this->exchanger = $exchanger;
        $this->math = $math;
        $this->freeAmount = $freeAmount;
    }

    public function execute(Money $money, array $operations = []): Money
    {
        $discount = $this->getRemainingDiscount($this->calculateSum($operations));

        return parent::execute($this->applyDiscount($money, $discount), $operations);
    }

    private function calculateSum(array $operations): Money
    {
        return array_reduce($operations, function ($prev, $current) {
            $amount = $this->math->add($prev, $this->convertToBaseCurrency($current));

            return new Money($amount, $this->freeAmount->getCurrency());
        }, new Money(0, $this->freeAmount->getCurrency()));
    }

    private function convertToBaseCurrency(Money $money): Money
    {
        return $this->exchanger->exchange($money, $this->freeAmount->getCurrency());
    }

    private function getRemainingDiscount(Money $sumOfOperations): Money
    {
        $amount = $this->math->sub($this->freeAmount, $sumOfOperations);

        return new Money(max($amount, 0), $this->freeAmount->getCurrency());
    }

    private function applyDiscount(Money $money, Money $discount): Money
    {
        $amount = $this->math->sub($this->convertToBaseCurrency($money), $discount);

        $result = new Money(max($amount, 0), $discount->getCurrency());

        return $this->exchanger->exchange($result, $money->getCurrency());
    }
}
