<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Money;
use App\Contract\Service\Math;

class BcMath implements Math
{
    private $scale;

    public function __construct(int $scale)
    {
        $this->scale = $scale;
    }

    public function add(Money $money, Money $anotherMoney): Money
    {
        $amount = bcadd($money, $anotherMoney, $this->scale);

        return new Money($amount, $money->getCurrency());
    }

    public function sub(Money $money, Money $anotherMoney): Money
    {
        $amount = bcsub($money, $anotherMoney, $this->scale);

        return new Money($amount, $money->getCurrency());
    }

    public function mul(Money $money, Money $anotherMoney): Money
    {
        $amount = bcmul($money, $anotherMoney, $this->scale);

        return new Money($amount, $money->getCurrency());
    }

    public function div(Money $money, Money $anotherMoney): Money
    {
        $amount = bcdiv($money, $anotherMoney, $this->scale);

        return new Money($amount, $money->getCurrency());
    }
}
